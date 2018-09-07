<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';
include_once '../utils/position.php';

try {
  $_GET['id_catalog'] = intval($_GET['id_catalog']);
  $_GET['id_position']  = intval($_GET['id_position']);
  $_GET['id_paragraph'] = intval($_GET['id_paragraph']);

  hide($_GET['id_paragraph'], $tbl_paragraph, "AND id_position=$_GET[id_position] AND id_catalog=$_GET[id_catalog]", "id_paragraph");
  header("Location: scr_paragraph.php?id_position=$_GET[id_position]&id_catalog=$_GET[id_catalog]&page=$_GET[page]");
  exit();
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
