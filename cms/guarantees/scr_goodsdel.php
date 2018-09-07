<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_GET['page'] = 0;
}else {
  $_GET['page'] = intval($_GET['page']);
}

if (empty($_GET['id_goods'])) {
  header("Location: indexgoods.php?page={$_GET['page']}");
}

try {
  $delrow = $pdo_grnt->prepare("DELETE FROM $tbl_goods WHERE idgoods = ?");
  $delrow->execute([intval($_GET['id_goods'])]);
  header("Location: indexgoods.php?page={$_GET['page']}");
  exit();
} catch (Exception $e) {
  echo $e;
}
?>
