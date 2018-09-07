<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

try {

  $query = "SELECT `idwaybill`, `number`, `date`, `base` FROM $tbl_waybill ORDER BY `date` DESC";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице позиций.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $waybills[$dat[idwaybill]] = "№".$dat[number]." от ".$dat[date].". Основание: ".$dat[base];
    };
  }

  $good = new FieldText("good", "Книга(и)", $_POST['good'], true);
  $price = new FieldDouble("price", "Стоимость", $_POST['price'], true);
  $quantity = new FieldInt("quantity", "Количество", $_POST['quantity'], true);
  $isbn = new FieldText("isbn", "ISBN", $_POST['isbn'], true);
  $tax = new FieldDouble("tax", "НДС", $_POST['tax'], false, 0);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $waybill = new FieldSelect("waybill", "Накладная", $_REQUEST['waybill'], $waybills);
  $form = new Form(array("good" => $good, "price" => $price, "quantity" => $quantity,
  "isbn" => $isbn, "tax" => $tax, "waybill" => $waybill, "page" => $page), "Добавить");

  if (!empty($_POST)) {
    $error = $form->check();
    $query = "INSERT INTO $tbl_goods VALUES (NULL, '{$form->fields[good]->get_value()}',
      '{$form->fields[price]->get_value()}', '{$form->fields[quantity]->get_value()}',
      '{$form->fields[isbn]->get_value()}', '{$form->fields[tax]->get_value()}',
      '{$form->fields[waybill]->get_value()}')";

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
