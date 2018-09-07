<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

if (empty($_POST)) {
  $_REQUEST['hide'] = true;
}
if (empty($_GET['page'])) {
  $_REQUEST['page'] = 1;
} else {
  $_REQUEST['page'] = intval($_GET['page']);
}
try {
  $today = date("d.m.Y");

  $base = new FieldText("base", "Основание", $_POST['base'], true);
  $number = new FieldText("number", "Номер", $_POST['number'], true);
  $date = new FieldDate("date", "Дата", $today, true);
  $provider = new FieldTextarea("provider", "Поставщик", $_POST['provider'], true);
  $payer = new FieldTextarea("payer", "Плательщик", $_POST['payer'], true);
  $consignor = new FieldTextarea("consignor", "Грузоотправитель", $_POST['consignor'], true);
  $subdivision = new FieldTextarea("subdivision", "Структурное подразделение", $_POST['subdivision'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("base" => $base, "number" => $number, "date" => $date,
  "provider" => $provider, "payer" => $payer, "consignor" => $consignor,
  "subdivision" => $subdivision, "page" => $page), "Добавить");

  if (!empty($_POST)) {
    $error = $form->check();
    $query = "INSERT INTO $tbl_waybill VALUES (NULL, '{$form->fields[base]->get_value()}',
      '{$form->fields[number]->get_value()}', '{$form->fields[date]->get_value()}',
      '{$form->fields[provider]->get_value()}', '{$form->fields[payer]->get_value()}',
      '{$form->fields[consignor]->get_value()}', '{$form->fields[subdivision]->get_value()}')";

    if (!$pdo_lib->query($query)) {
      throw new ExceptionMySQL("", $query,"Ошибка добавления");
    }
    header("Location: indexwaybill.php?page={$form->fields[page]->get_value()}");
    exit();
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
