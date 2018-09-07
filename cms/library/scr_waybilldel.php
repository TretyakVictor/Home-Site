<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

$_GET['id_waybill'] = intval($_GET['id_waybill']);

try {
  $query = "SELECT * FROM $tbl_waybill WHERE idwaybill=$_GET[id_waybill]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(),  $query,"Ошибка при обращении к таблице накладных");
  }
  $dataarr = $dataerr[0];
  // if (file_exists("../../".$dataarr['urlpict'])) {
  //   @unlink("../../".$dataarr['urlpict']);
  // }
  $query = "DELETE FROM $tbl_waybill WHERE idwaybill=$_GET[id_waybill] LIMIT 1";
  if ($pdo_lib->query($query)) {
    header("Location: indexwaybill.php?page=$_GET[page]");
    exit();
  }else {
    throw new ExceptionMySQL("", $query, "Ошибка удаления");
  }
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
