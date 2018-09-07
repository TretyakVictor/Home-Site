<?php
include_once'../../configs/classes.config.cms.php';
include_once'../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = $_GET['page'];
}
if ($_GET['id_inventorybooks']) {
  $_REQUEST['id_inventorybooks'] = intval($_GET['id_inventorybooks']);
}
if (empty($_GET['id_inventorybooks'])) {
  $_REQUEST['id_inventorybooks'] = 0;
}
try {
  $query = "SELECT * FROM $tbl_inventorybooks WHERE idinventorybooks=$_GET[id_inventorybooks]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к таблице книг");
  }
  $dataarr = $dataerr[0];
  if (empty($_POST)) {
    $_REQUEST = $dataarr;
    if (!empty($_GET['page'])) {
      $_REQUEST['page'] = $_GET['page'];
    }
  }
  // if (empty($_REQUEST[goods_waybill_idwaybill])) {
  //   $_REQUEST[goods_waybill_idwaybill] = $_REQUEST['good'];
  // }
  if (!empty($_REQUEST['good'])) {
    $_REQUEST[goods_waybill_idwaybill] = $_REQUEST['good'];
  }
  $query = "SELECT `idgoods`, `good`, `isbn` FROM $tbl_goods WHERE waybill_idwaybill={$_REQUEST[goods_waybill_idwaybill]}  ORDER BY `idgoods` DESC";
  // echo "$query<br>";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице накладных.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $goods[$dat[idgoods]] = $dat[good]." ".$dat[isbn];
    };
  }
  $query = "SELECT `idpublishinghouse`, `name` FROM $tbl_publishinghouse ORDER BY `idpublishinghouse` DESC";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице издательств.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $publishinghouses[$dat[idpublishinghouse]] = $dat[name];
    };
  }
  $query = "SELECT `idauthors`, `name` FROM $tbl_authors ORDER BY `idauthors` DESC";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице авторов.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $author[$dat[idauthors]] = $dat[name];
    };
  }


  $inventorynumber = new FieldText("inventorynumber", "Инвентарный номер", $_REQUEST['inventorynumber'], true);
  $title = new FieldText("title", "Название", $_REQUEST['title'], true);
  $yearofpublishing = new FieldInt("yearofpublishing", "Год издания", $_REQUEST['yearofpublishing'], true);
  $class = new FieldInt("class", "Класс", $_REQUEST['class'], false, 1, 12);
  $notation = new FieldTextarea("notation", "Примечание", $_REQUEST['notation'], false);
  $publishinghouse = new FieldSelect("publishinghouse", "Издательство", $_REQUEST['publishinghouse_idpublishinghouse'], $publishinghouses);
  $authors = new FieldSelect("authors", "Автор(ы)", $_REQUEST['authors_idauthors'], $author);
  $good = new FieldSelect("good", "Накладная", $_REQUEST['goods_idgoods'], $goods);
  $id_inventorybooks = new FieldHiddenInt("id_inventorybooks",  $_REQUEST['id_inventorybooks'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("inventorynumber" => $inventorynumber, "title" => $title,
   "yearofpublishing" => $yearofpublishing, "notation" => $notation, "class" => $class,
   "publishinghouse" => $publishinghouse, "authors" => $authors, "good" => $good,
   "page" => $page, "id_inventorybooks" => $id_inventorybooks), "Изменить");


  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $query = "UPDATE $tbl_inventorybooks SET inventorynumber = '{$form->fields[inventorynumber]->get_value()}',
      title = '{$form->fields[title]->get_value()}', yearofpublishing = '{$form->fields[yearofpublishing]->get_value()}',
      notation = '{$form->fields[notation]->get_value()}', class = '{$form->fields['class']->get_value()}',
      publishinghouse_idpublishinghouse = '{$form->fields[publishinghouse]->get_value()}',
      authors_idauthors = '{$form->fields[authors]->get_value()}',
      goods_idgoods = '{$form->fields[good]->get_value()}'
       WHERE idinventorybooks=".$form->fields['id_inventorybooks']->get_value();
      if (!$pdo_lib->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка обновления");
      }
      // exit("<meta http-equiv='refresh' content='0; url=
      // $_SERVER[PHP_SELF]?page={$form->fields[page]->get_value()}'>");
      header("Location: indexinventorybooks.php?page={$form->fields[page]->get_value()}");
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
  // require("../utils/exception_object.php");
} catch (ExceptionMySQL $e) {
  // require("../utils/exception_mysql.php");
} catch (ExceptionMember $e) {
  // require("../utils/exception_member.php");
}
require_once("../utils/bottom.php");
?>
