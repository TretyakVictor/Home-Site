<?php
require_once '../../configs/classes.config.modules.php';
require_once '../../configs/mysql.config.php';
try {
  $query = "SELECT `idValues`, `Temperature`, `Humidity`, `Carbon`, `Light`, STR_TO_DATE(CONCAT(`Date`, ' ', `Time`), '%Y-%m-%d %H:%i:%s') AS `Time`  FROM `Sensors`.`Values` ORDER BY `idValues` ASC";
  $pdo_sensors = $pdo_sensors->pdoGet();
  $data = $pdo_sensors->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $values[$dat['idValues']] = array($dat['Time'], $dat['Temperature'], $dat['Humidity'], $dat['Carbon'], $dat['Light']);
      // $values[$dat['idValues']] = strtotime($dat['Time'])." ".$dat['Temperature']." ".$dat['Humidity']." - ".$dat['Carbon'];
    };
  }
  echo json_encode($values, JSON_UNESCAPED_UNICODE);
  exit;
} catch (ExceptionMySQL $e) {
  echo "error!";
}
?>
