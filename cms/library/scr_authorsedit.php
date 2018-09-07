<?php
include_once'../../configs/classes.config.cms.php';
include_once'../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = $_GET['page'];
}
if ($_GET['id_authors']) {
  $_REQUEST['id_authors'] = intval($_GET['id_authors']);
}
if (empty($_GET['id_authors'])) {
  $_REQUEST['id_authors'] = 0;
}
try {

  $query = "SELECT * FROM $tbl_authors WHERE idauthors=$_GET[id_authors]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к таблице авторов");
  }
  $dataarr = $dataerr[0];
  if (empty($_POST)) {
    $_REQUEST = $dataarr;
    if (!empty($_GET['page'])) {
      $_REQUEST['page'] = $_GET['page'];
    }
  }


  $name = new FieldText("name", "Автор", $_REQUEST['name'], true);
  $id_authors = new FieldHiddenInt("id_authors",  $_REQUEST['id_authors'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "id_authors" => $id_authors, "page" => $page), "Изменить");


  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $query = "UPDATE $tbl_authors SET name = '{$form->fields[name]->get_value()}'
       WHERE idauthors=".$form->fields['id_authors']->get_value();
      if (!$pdo_lib->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка обновления");
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
require_once("../utils/bottom.php");
?>
