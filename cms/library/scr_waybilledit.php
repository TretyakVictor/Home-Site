<?php
include_once'../../configs/classes.config.cms.php';
include_once'../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = intval($_GET['page']);
}
if ($_GET['id_waybill']) {
  $_REQUEST['id_waybill'] = intval($_GET['id_waybill']);
}
if (empty($_GET['id_waybill'])) {
  $_REQUEST['id_waybill'] = 0;
}
try {

  $query = "SELECT * FROM $tbl_waybill WHERE idwaybill=$_GET[id_waybill]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к таблице накладных");
  }
  $dataarr = $dataerr[0];
  if (empty($_POST)) {
    $_REQUEST = $dataarr;
    // print_r($_REQUEST);
    if (!empty($_GET['page'])) {
      $_REQUEST['page'] = $_GET['page'];
    }
  }

  $base = new FieldText("base", "Основание", $_REQUEST['base'], true);
  $number = new FieldText("number", "Номер", $_REQUEST['number'], true);
  $date = new FieldText("date", "Дата", $_REQUEST['date'], true);
  $provider = new FieldTextarea("provider", "Поставщик", $_REQUEST['provider'], true);
  $payer = new FieldTextarea("payer", "Плательщик", $_REQUEST['payer'], true);
  $consignor = new FieldTextarea("consignor", "Грузоотправитель", $_REQUEST['consignor'], true);
  $subdivision = new FieldTextarea("subdivision", "Структурное подразделение", $_REQUEST['subdivision'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $id_waybill = new FieldHiddenInt("id_waybill",  $_REQUEST['id_waybill'], true);
  $form = new Form(array("base" => $base, "number" => $number, "date" => $date,
  "provider" => $provider, "payer" => $payer, "consignor" => $consignor,
  "subdivision" => $subdivision, "page" => $page, "id_waybill" => $id_waybill), "Изменить");


  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $query = "UPDATE $tbl_waybill SET base = '{$form->fields[base]->get_value()}',
      `number` = '{$form->fields[number]->get_value()}', `date` = '{$form->fields[date]->get_value()}',
      provider = '{$form->fields[provider]->get_value()}', payer = '{$form->fields[payer]->get_value()}',
      consignor = '{$form->fields[consignor]->get_value()}', subdivision = '{$form->fields[subdivision]->get_value()}'
       WHERE idwaybill=".$form->fields['id_waybill']->get_value();
      if (!$pdo_lib->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка обновления");
      }
      header("Location: indexwaybill.php?page={$form->fields[page]->get_value()}");
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
