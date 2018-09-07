<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';
include_once '../utils/position.php';

try {
  $_GET['id_catalog'] = intval($_GET['id_catalog']);
  $_GET['id_position']  = intval($_GET['id_position']);
  $_GET['id_paragraph'] = intval($_GET['id_paragraph']);
  $_GET['id_image']  = intval($_GET['id_image']);

  down($_GET['id_image'], $tbl_paragraph_image, "AND id_position=$_GET[id_position] AND id_catalog=$_GET[id_catalog] AND id_paragraph=$_GET[id_paragraph]", "id_image");
  header("Location: scr_image.php?id_paragraph=$_GET[id_paragraph]&id_position=$_GET[id_position]&id_catalog=$_GET[id_catalog]&page=$_GET[page]");
  exit();
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
