<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

$_GET['id_inventorybooks'] = intval($_GET['id_inventorybooks']);

try {
  $query = "SELECT * FROM $tbl_inventorybooks WHERE idinventorybooks=$_GET[id_inventorybooks]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(),  $query,"Ошибка при обращении к таблице читателей");
  }
  $dataarr = $dataerr[0];

  $query = "DELETE FROM $tbl_inventorybooks WHERE idinventorybooks=$_GET[id_inventorybooks] LIMIT 1";
  if ($pdo_lib->query($query)) {
    header("Location: indexinventorybooks.php?page=$_GET[page]");
    exit();
  }else {
    throw new ExceptionMySQL("", $query, "Ошибка удаления");
  }
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
