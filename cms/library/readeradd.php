<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 1;
} else {
  $_REQUEST['page'] = intval($_GET['page']);
}
try {
  // $today = date("d-m-Y");
  $today = date("Y-m-d");

  $name = new FieldText("name", "Имя", $_POST['name'], true);
  $patronymic = new FieldText("patronymic", "Отчество", $_POST['patronymic'], true);
  $surname = new FieldText("surname", "Фамилия", $_POST['surname'], true);
  $dateofbirth = new FieldDate("dateofbirth", "Дата рождения", $_POST['dateofbirth'], true);
  $phonenumber = new FieldText("phonenumber", "Номер телефона", $_POST['phonenumber'], false);
  $homephonenumber = new FieldText("homephonenumber", "Номер домашнего телефона", $_POST['homephonenumber'], false);
  $address = new FieldText("address", "Адрес", $_POST['address'], true);
  $receiptdate = new FieldDate("receiptdate", "Дата поступления", $today, true);
  $receiptclass = new FieldInt("receiptclass", "Текущий класс", $_REQUEST['receiptclass'], false, 1, 12);
  $passport = new FieldText("passport", "Паспорт", $_POST['passport'], false);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "patronymic" => $patronymic, "surname" => $surname,
  "dateofbirth" => $dateofbirth, "phonenumber" => $phonenumber, "homephonenumber" => $homephonenumber,
  "address" => $address, "receiptdate" => $receiptdate, "receiptclass" => $receiptclass,
  "passport" => $passport, "page" => $page), "Добавить");

  if (!empty($_POST)) {
    $error = $form->check();
    $query = "INSERT INTO $tbl_reader VALUES (NULL, '{$form->fields[name]->get_value()}',
      '{$form->fields[patronymic]->get_value()}', '{$form->fields[surname]->get_value()}',
      '{$form->fields[dateofbirth]->get_value()}', '{$form->fields[phonenumber]->get_value()}',
      '{$form->fields[homephonenumber]->get_value()}', '{$form->fields[address]->get_value()}',
      '{$form->fields[receiptdate]->get_value()}', '{$form->fields[receiptclass]->get_value()}',
      '{$form->fields[passport]->get_value()}')";

    if (!$pdo_lib->query($query)) {
      throw new ExceptionMySQL("", $query,"Ошибка добавления");
    }
    header("Location: indexreader.php?page={$form->fields[page]->get_value()}");
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
 ?>

 <?php
require_once '../utils/bottom.php';
?>
