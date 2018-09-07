<?php
include_once'../../configs/classes.config.cms.php';
include_once'../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = $_GET['page'];
}
if ($_GET['id_publishinghouse']) {
  $_REQUEST['id_publishinghouse'] = intval($_GET['id_publishinghouse']);
}
if (empty($_GET['id_publishinghouse'])) {
  $_REQUEST['id_publishinghouse'] = 0;
}
try {

  $query = "SELECT * FROM $tbl_publishinghouse WHERE idpublishinghouse=$_GET[id_publishinghouse]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к таблице издательств");
  }
  $dataarr = $dataerr[0];
  if (empty($_POST)) {
    $_REQUEST = $dataarr;
    if (!empty($_GET['page'])) {
      $_REQUEST['page'] = $_GET['page'];
    }
  }


  $name = new FieldText("name", "Издательство", $_REQUEST['name'], true);
  $id_publishinghouse = new FieldHiddenInt("id_publishinghouse",  $_REQUEST['id_publishinghouse'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "id_publishinghouse" => $id_publishinghouse, "page" => $page), "Изменить");


  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $query = "UPDATE $tbl_publishinghouse SET name = '{$form->fields[name]->get_value()}'
       WHERE idpublishinghouse=".$form->fields['id_publishinghouse']->get_value();
      if (!$pdo_lib->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка обновления");
      }
      header("Location: indexpublishinghouse.php?page={$form->fields[page]->get_value()}");
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
