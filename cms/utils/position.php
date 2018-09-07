<?php

function show($id_position, $tbl_name, $where = "", $fld_name = "id_position")
{
  global $pdo;
  $id_position = intval($id_position);
  $query = "UPDATE $tbl_name SET hide='show' WHERE $fld_name=$id_position $where";
  if (!$pdo->query($query)) {
    throw new ExceptionMySQL("", $query, "Ошибка при отображении позиции.");
  }
}
function hide($id_position, $tbl_name, $where = "", $fld_name = "id_position")
{
  global $pdo;
  $id_position = intval($id_position);
  $query = "UPDATE $tbl_name SET hide='hide' WHERE $fld_name=$id_position $where";
  if (!$pdo->query($query)) {
    throw new ExceptionMySQL("", $query, "Ошибка при сокрытии позиции.");
  }
}
function up($id_position, $tbl_name, $where = "", $fld_name = "id_position")
{
  global $pdo;
  $id_position = intval($id_position);
  $query = "SELECT pos FROM $tbl_name WHERE $fld_name = $id_position LIMIT 1";
  // echo $query."</br>";
  $pos = $pdo->query($query);
  // if (!$pos) {
  //   throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при извлечении текущей позиции");
  // }
  if ($pos->rowCount()) {
    $pos_current = $pos->fetchColumn();
  }
  $query = "SELECT pos FROM $tbl_name WHERE pos < $pos_current $where ORDER BY pos DESC LIMIT 1";
  // echo $query."</br>";
  $pos = $pdo->query($query);
  // if (!$pos) {
  //   throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при извлечении предыдущей позиции");
  // }
  if ($pos->rowCount()) {
    $pos_preview = $pos->fetchColumn();
    $query = "UPDATE $tbl_name SET pos = $pos_current + $pos_preview - pos WHERE pos IN ($pos_current, $pos_preview) $where";
    if (!$pdo->query($query)) {
      throw new ExceptionMySQL("", $query, "Ошибка изменения позиции.");
    }
  }
}
function down($id_position, $tbl_name, $where = "", $fld_name = "id_position")
{
  global $pdo;
  $id_position = intval($id_position);
  $query = "SELECT pos FROM $tbl_name WHERE $fld_name = $id_position LIMIT 1";
  $pos = $pdo->query($query);
  // if (!$pos) {
  //   throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при извлечении текущей позиции");
  // }
  if ($pos->rowCount()) {
    $pos_current = $pos->fetchColumn();
  }
  $query = "SELECT pos FROM $tbl_name WHERE pos > $pos_current $where ORDER BY pos LIMIT 1";
  $pos = $pdo->query($query);
  // if (!$pos) {
  //   throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при извлечении предыдущей позиции");
  // }
  if ($pos->rowCount()) {
    $pos_next = $pos->fetchColumn();
    $query = "UPDATE $tbl_name SET pos = $pos_next + $pos_current - pos WHERE pos IN ($pos_next, $pos_current) $where";
    if (!$pdo->query($query)) {
      throw new ExceptionMySQL("", $query, "Ошибка изменения позиции.");
    }
  }
}
?>
