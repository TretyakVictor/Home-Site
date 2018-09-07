<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_GET['page'] = 0;
}else {
  $_GET['page'] = intval($_GET['page']);
}

if (empty($_GET['id_genre'])) {
  header("Location: indexshop.php?page={$_GET['page']}");
  exit();
}

try {
  $delrow = $pdo_Lib->prepare("DELETE FROM $tbl_Lib_genre WHERE idgenre = ? LIMIT 1");
  $delrow->execute([intval($_GET['id_genre'])]);
  header("Location: indexgenre.php?page={$_GET['page']}");
  exit();
} catch (Exception $e) {
  echo $e;
}
?>
