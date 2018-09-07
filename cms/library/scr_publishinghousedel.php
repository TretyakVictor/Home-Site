<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

$_GET['id_publishinghouse'] = intval($_GET['id_publishinghouse']);

try {
  $query = "SELECT * FROM $tbl_publishinghouse WHERE idpublishinghouse=$_GET[id_publishinghouse]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(),  $query,"Ошибка при обращении к таблице издательств");
  }
  $dataarr = $dataerr[0];
  // if (file_exists("../../".$dataarr['urlpict'])) {
  //   @unlink("../../".$dataarr['urlpict']);
  // }
  $query = "DELETE FROM $tbl_publishinghouse WHERE idpublishinghouse=$_GET[id_publishinghouse] LIMIT 1";
  if ($pdo_lib->query($query)) {
    header("Location: indexpublishinghouse.php?page=$_GET[page]");
    exit();
  }else {
    throw new ExceptionMySQL("", $query, "Ошибка удаления");
  }
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
