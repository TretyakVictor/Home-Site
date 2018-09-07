<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

try {
  $_GET['page'] = intval($_GET['page']);
  $_GET['id_news'] = intval($_GET['id_news']);

  $sql = "UPDATE $tbl_news SET hide = 'hide' WHERE id_news = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$_GET['id_news']]);

  header("Location: index.php?page=$_GET[page]");
  exit();
}catch (ExceptionMySQL $e) {
  echo "Error";
}
?>
