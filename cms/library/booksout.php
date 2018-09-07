<?php
require_once("../utils/head.php");
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

try {
  $query = "SELECT * FROM $tbl_inventorybooks `T4` LEFT JOIN (SELECT `T1`.idinventorybooks AS
    `idinvbooks` FROM $tbl_inventorybooks `T1` LEFT JOIN $tbl_movement `T2` ON `T1`.idinventorybooks =
    `T2`.inventorybooks_idinventorybooks WHERE `T2`.switch = 1) `T5` ON `T4`.idinventorybooks =
    `T5`.idinvbooks WHERE `T5`.idinvbooks is NULL";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице книг.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $dataerr[$dat[idinventorybooks]] = array('idpublishinghouse' => $dat[publishinghouse_idpublishinghouse],
        'idauthors' => $dat[authors_idauthors], 'idgoods' => $dat[goods_idgoods],
        'idwaybill' => $dat[goods_waybill_idwaybill]);
      $inventorybooks[$dat[idinventorybooks]] = $dat[title]." ".$dat[yearofpublishing];
    };
  }
  $query = "SELECT * FROM $tbl_reader ORDER BY `surname` ASC";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице читателей.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $readers[$dat[idreader]] = $dat[surname]." ".$dat[name]." ".$dat[patronymic];
    };
  }


  $inventorybooks = new FieldSelect("inventorybooks", "Книга", $_REQUEST['inventorybooks'], $inventorybooks);
  $reader = new FieldSelect("reader", "Читатель", $_REQUEST['reader'], $readers);
  $up = new FieldCheckbox("up", "Выдать", $_REQUEST['up']);
  $form = new Form(array("reader" => $reader, "inventorybooks" => $inventorybooks, "up" => $up), "Добавить");
  if ($form->fields[up]->get_value()) {
    $_REQUEST['up'] = false;
    $error = $form->check();
    $dataerr = $dataerr[$form->fields[inventorybooks]->get_value()];
    $datain = $dataerr;
    $query = "INSERT INTO $tbl_movement VALUES (NULL, '".date('Y-m-d H:i:s')."',
      1, '{$form->fields[reader]->get_value()}', '{$form->fields[inventorybooks]->get_value()}',
      '{$datain[idpublishinghouse]}', '{$datain[idauthors]}',
      '{$datain[idgoods]}', '{$datain[idwaybill]}')";

    if (!$pdo_lib->query($query)) {
      throw new ExceptionMySQL("", $query,"Ошибка добавления");
    }
    exit("<meta http-equiv='refresh' content='0; url= $_SERVER[PHP_SELF]'>");
  }
  if (!empty($error)) {
    foreach ($error as $err) {
      echo "<span style=\"color:red\">$err</span><br>";
    }
  }
  $form->print_form();

} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

require_once '../utils/bottom.php';
?>
