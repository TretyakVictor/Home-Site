<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

try {
  $_GET['id_catalog'] = intval($_GET['id_catalog']);
  $_GET['id_position']  = intval($_GET['id_position']);
  $_GET['id_paragraph'] = intval($_GET['id_paragraph']);
  $_GET['id_image']  = intval($_GET['id_image']);

  $img = $pdo->prepare("SELECT * FROM $tbl_paragraph_image WHERE id_position=? AND id_catalog=? AND id_paragraph=? AND id_image=? LIMIT 1");
  $img->bindValue(1, $_GET['id_position'], PDO::PARAM_INT);
  $img->bindValue(2, $_GET['id_catalog'], PDO::PARAM_INT);
  $img->bindValue(3, $_GET['id_paragraph'], PDO::PARAM_INT);
  $img->bindValue(4, $_GET['id_image'], PDO::PARAM_INT);
  $img->execute();
  if ($img->rowCount() > 0) {
    $image = $img->fetch();
    if (file_exists("../../".$image['big'])) {
      @unlink("../../".$image['big']);
    }
    if (file_exists("../../".$image['small'])) {
      @unlink("../../".$image['small']);
    }
  }

  $img = $pdo->prepare("DELETE FROM $tbl_paragraph_image WHERE id_position=? AND id_catalog=? AND id_paragraph=? AND id_image=? LIMIT 1");
  $img->bindValue(1, $_GET['id_position'], PDO::PARAM_INT);
  $img->bindValue(2, $_GET['id_catalog'], PDO::PARAM_INT);
  $img->bindValue(3, $_GET['id_paragraph'], PDO::PARAM_INT);
  $img->bindValue(4, $_GET['id_image'], PDO::PARAM_INT);
  $img->execute();

  header("Location: scr_image.php?id_paragraph=$_GET[id_paragraph]&id_position=$_GET[id_position]&id_catalog=$_GET[id_catalog]&page=$_GET[page]");
  exit();
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
