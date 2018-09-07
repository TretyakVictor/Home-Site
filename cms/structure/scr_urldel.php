<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

try {
  $_GET['id_catalog'] = intval($_GET['id_catalog']);
  $_GET['id_position'] = intval($_GET['id_position']);


  $query = "DELETE FROM $tbl_paragraph WHERE id_position=".$_GET['id_position']." AND id_catalog=".$_GET['id_catalog'];
  if (!$pdo->query($query)) {
    throw new ExceptionMySQL("", $query, "Ошибка удаления новости.");
  }
  $query = "DELETE FROM $tbl_position WHERE id_position=".$_GET['id_position']." AND id_catalog=".$_GET['id_catalog']." LIMIT 1";
  if (!$pdo->query($query)) {
    throw new ExceptionMySQL("", $query, "Ошибка удаления позиции каталога.");
  }

  header("Location: index.php?id_parent=$_GET[id_catalog]&page=$_GET[page]");
  exit();
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
