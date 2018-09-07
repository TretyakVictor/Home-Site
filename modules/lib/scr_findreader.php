<?php
require_once '../../configs/classes.config.modules.php';
require_once '../../configs/mysql.config.php';
try {
  $query = "SELECT `idreader`, `name`, `patronymic`, `surname`, `dateofbirth` FROM $tbl_reader ORDER BY `surname` ASC";
  $pdo_lib = $pdo_lib->pdoGet();
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице читателей.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $readers[$dat[idreader]] = $dat[surname]." ".$dat[name]." ".$dat[patronymic]." - ".$dat[dateofbirth];
    };
  }
  echo json_encode($readers, JSON_UNESCAPED_UNICODE);
  exit;
} catch (ExceptionMySQL $e) {
  echo "error!";
}
?>
