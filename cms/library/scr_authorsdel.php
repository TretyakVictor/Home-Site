<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

$_GET['id_authors'] = intval($_GET['id_authors']);

try {
  $query = "SELECT * FROM $tbl_authors WHERE idauthors=$_GET[id_authors]";
  $data = $pdo_lib->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(),  $query,"Ошибка при обращении к таблице авторов");
  }
  $dataarr = $dataerr[0];
  // if (file_exists("../../".$dataarr['urlpict'])) {
  //   @unlink("../../".$dataarr['urlpict']);
  // }
  $query = "DELETE FROM $tbl_authors WHERE idauthors=$_GET[id_authors] LIMIT 1";
  if ($pdo_lib->query($query)) {
    header("Location: indexauthors.php?page=$_GET[page]");
    exit();
  }else {
    throw new ExceptionMySQL("", $query, "Ошибка удаления");
  }
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
