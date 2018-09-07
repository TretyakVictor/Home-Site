<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

$_GET['id_reader'] = intval($_GET['id_reader']);

try {
  $query = "SELECT * FROM $tbl_reader WHERE idreader=$_GET[id_reader]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(),  $query,"Ошибка при обращении к таблице читателей");
  }
  $dataarr = $dataerr[0];
  // if (file_exists("../../".$dataarr['urlpict'])) {
  //   @unlink("../../".$dataarr['urlpict']);
  // }
  $query = "DELETE FROM $tbl_reader WHERE idreader=$_GET[id_reader] LIMIT 1";
  if ($pdo_lib->query($query)) {
    header("Location: indexreader.php?page=$_GET[page]");
    exit();
  }else {
    throw new ExceptionMySQL("", $query, "Ошибка удаления");
  }
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
