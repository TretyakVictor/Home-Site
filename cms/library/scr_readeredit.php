<?php
include_once'../../configs/classes.config.cms.php';
include_once'../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = $_GET['page'];
}
if ($_GET['id_reader']) {
  $_REQUEST['id_reader'] = intval($_GET['id_reader']);
}
if (empty($_GET['id_reader'])) {
  $_REQUEST['id_reader'] = 0;
}
try {

  $query = "SELECT * FROM $tbl_reader WHERE idreader=$_GET[id_reader]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к таблице читателей");
  }
  $dataarr = $dataerr[0];
  if (empty($_POST)) {
    $_REQUEST = $dataarr;
    if (!empty($_GET['page'])) {
      $_REQUEST['page'] = $_GET['page'];
    }
  }

  $name = new FieldText("name", "Имя", $_REQUEST['name'], true);
  $patronymic = new FieldText("patronymic", "Отчество", $_REQUEST['patronymic'], true);
  $surname = new FieldText("surname", "Фамилия", $_REQUEST['surname'], true);
  $dateofbirth = new FieldDate("dateofbirth", "Дата рождения", $_REQUEST['dateofbirth'], true);
  $phonenumber = new FieldText("phonenumber", "Номер телефона", $_REQUEST['phonenumber'], false);
  $homephonenumber = new FieldText("homephonenumber", "Номер домашнего телефона", $_REQUEST['homephonenumber'], false);
  $address = new FieldText("address", "Адрес", $_REQUEST['address'], true);
  $receiptdate = new FieldText("receiptdate", "Дата поступления", $_REQUEST['receiptdate'], true);
  $receiptclass = new FieldInt("receiptclass", "Текущий класс", $_REQUEST['receiptclass'], false, 1, 12);
  $passport = new FieldText("passport", "Паспорт", $_REQUEST['passport'], false);
  $id_reader = new FieldHiddenInt("id_reader",  $_REQUEST['id_reader'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "patronymic" => $patronymic, "surname" => $surname,
  "dateofbirth" => $dateofbirth, "phonenumber" => $phonenumber, "homephonenumber" => $homephonenumber,
  "address" => $address, "receiptdate" => $receiptdate, "receiptclass" => $receiptclass, "passport" => $passport,
  "page" => $page, "id_reader" => $id_reader), "Изменить");


  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $query = "UPDATE $tbl_reader SET name = '{$form->fields[name]->get_value()}',
       `patronymic` = '{$form->fields[patronymic]->get_value()}', `surname` = '{$form->fields[surname]->get_value()}',
       dateofbirth = '{$form->fields[dateofbirth]->get_value()}', phonenumber = '{$form->fields[phonenumber]->get_value()}',
       homephonenumber = '{$form->fields[homephonenumber]->get_value()}', address = '{$form->fields[address]->get_value()}',
       receiptdate = '{$form->fields[receiptdate]->get_value()}', receiptclass = '{$form->fields[receiptclass]->get_value()}',
       passport = '{$form->fields[passport]->get_value()}'
       WHERE idreader=".$form->fields['id_reader']->get_value();
      if (!$pdo_lib->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка обновления");
      }
      header("Location: indexreader.php?page={$form->fields[page]->get_value()}");
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
