<?php
function menu_navigation($id_catalog, $link, $catalog, $current = false){
  global $pdo, $title, $description;
  $id_catalog = intval($id_catalog);

  $cat = $pdo->prepare("SELECT * FROM $catalog WHERE id_catalog = ?");
  $cat->execute([$id_catalog]);

  if ($cat->rowCount() > 0) {
    $data = $cat->fetchAll();
    $catalog_result = $data[0];
    if ($current) {
      $title = "<h1>".$catalog_result['name']."</h1>";
      $description = $catalog_result['description']."</br></br>";
      $link = $catalog_result['name'];
    } else {
      $link = "<a href=sections.php?id_catalog=".$catalog_result['id_catalog']."> ".$catalog_result['name']."</a> -&gt; ".$link;
    }
    $link = menu_navigation($catalog_result['id_parent'], $link, $catalog);
  }
  return $link;
}
?>
