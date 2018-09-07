<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

try {
  $stmt = $pdo->prepare("DELETE FROM $tbl_users WHERE id_users = ? LIMIT 1");
  if ($stmt->execute([intval($_GET[id_users])])) {
    header("Location: indexusers.php?page=$_GET[page]");
    exit();
  }else {
    throw new ExceptionMySQL("", $stmt, "Ошибка удаления");
  }
}catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
}
?>
