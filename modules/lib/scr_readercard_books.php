<?php
require_once '../../configs/classes.config.modules.php';
require_once '../../configs/mysql.config.php';

try {
  $query = "SELECT `idpublishinghouse`, `name` FROM $tbl_publishinghouse ORDER BY `idpublishinghouse` DESC";
  $pdo_lib = $pdo_lib->pdoGet();
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице издательств.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $publishinghouses[$dat[idpublishinghouse]] = $dat[name];
    };
  }
  $query = "SELECT `idauthors`, `name` FROM $tbl_authors ORDER BY `idauthors` DESC";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице авторов.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $author[$dat[idauthors]] = $dat[name];
    };
  }

  $query = "SELECT idinventorybooks, inventorynumber, title, yearofpublishing, authors_idauthors, publishinghouse_idpublishinghouse, type, class
   FROM $tbl_inventorybooks `T4` LEFT JOIN (SELECT `T1`.idinventorybooks AS
   `idinvbooks` FROM $tbl_inventorybooks `T1` LEFT JOIN $tbl_movement `T2` ON `T1`.idinventorybooks =
   `T2`.inventorybooks_idinventorybooks WHERE `T2`.switch = 1) `T5` ON `T4`.idinventorybooks =
   `T5`.idinvbooks WHERE `T5`.idinvbooks is NULL";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице читателей и книг.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $books[$dat[idinventorybooks]] = $dat[inventorynumber]." ".$dat[title]." ".$dat[type]." ".$dat['class']." ".$dat[yearofpublishing]." ".$author[$dat[authors_idauthors]]." ".$publishinghouses[$dat[publishinghouse_idpublishinghouse]];
    };
  }
  echo json_encode($books, JSON_UNESCAPED_UNICODE);
  exit;
} catch (ExceptionMySQL $e) {
  echo "error!";
}

?>
