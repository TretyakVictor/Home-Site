<?php
function menu_navigation($id_catalog, $link, $catalog){
  global $pdo;
  $id_catalog = intval($id_catalog);
  $query = "SELECT * FROM $catalog WHERE id_catalog = $id_catalog";
  $cat = $pdo->query($query);
  if (!$cat) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении таблице каталога. [menu_navigation()]");
  }
  if ($cat->rowCount() > 0) {
    $data = $cat->fetchAll();
    $catalog_result = $data[0];
    $link = "<a href=index.php?id_parent=".$catalog_result['id_catalog'].">".$catalog_result['name']."</a> -&gt; ".$link;
    $link = menu_navigation($catalog_result['id_parent'], $link, $catalog);
  }
  return $link;
}
?>
