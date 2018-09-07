<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 1;
} else {
  $_REQUEST['page'] = intval($_GET['page']);
}
try {

  $name = new FieldText("name", "Автор(ы)", $_POST['name'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "page" => $page), "Добавить");

  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $query = "INSERT INTO $tbl_authors VALUES (NULL, '{$form->fields[name]->get_value()}')";

      if (!$pdo_lib->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка добавления");
      }
      header("Location: indexauthors.php?page={$form->fields[page]->get_value()}");
      exit();
    }
  }
  require_once("../utils/head.php");
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
  if (!empty($error)) {
    foreach ($error as $err) {
      echo "<span style=\"color:red\">$err</span><br>";
    }
  }
  $form->print_form();

} catch (ExceptionObject $e) {
  require("../utils/exception_object.php");
} catch (ExceptionMySQL $e) {
  require("../utils/exception_mysql.php");
} catch (ExceptionMember $e) {
  require("../utils/exception_member.php");
}
require_once '../utils/bottom.php';
?>
