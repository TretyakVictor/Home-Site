<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

$_GET['page'] = intval($_GET['page']);
$_GET['id_users'] = intval($_GET['id_users']);

try {
  $sql = "UPDATE $tbl_users SET block = ? WHERE id_users = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, 'block', PDO::PARAM_STR);
  $stmt->bindValue(2, $_GET['id_users'], PDO::PARAM_INT);
  if ($stmt->execute()) {
    header("Location: indexusers.php?page=$_GET[page]");
  }
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}

 ?>
