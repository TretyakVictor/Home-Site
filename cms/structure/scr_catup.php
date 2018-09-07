<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';
include_once '../utils/position.php';

try {
  $_GET['id_catalog'] = intval($_GET['id_catalog']);
  $_GET['id_parent']  = intval($_GET['id_parent']);

  up($_GET['id_catalog'], $tbl_catalog, "AND id_parent = $_GET[id_parent]", "id_catalog");
  header("Location: index.php?id_parent=$_GET[id_parent]&page=$_GET[page]");
  exit();
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
