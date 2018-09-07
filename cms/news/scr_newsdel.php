<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

$_GET['id_news'] = intval($_GET['id_news']);

try {
  $query = "SELECT * FROM $tbl_news WHERE id_news=$_GET[id_news]";
  $data = $pdo->query($query);
  $dataerr = $data->fetchAll();
  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(),  $query,"Ошибка при обращении к таблице новостей");
  }
  $news = $dataerr[0];
  if (file_exists("../../".$news['urlpict'])) {
    @unlink("../../".$news['urlpict']);
  }
  $query = "DELETE FROM $tbl_news WHERE id_news=$_GET[id_news] LIMIT 1";
  if ($pdo->query($query)) {
    header("Location: index.php?page=$_GET[page]");
    exit();
  }else {
    throw new ExceptionMySQL("", $query, "Ошибка удаления");
  }
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
