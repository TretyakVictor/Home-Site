<?php
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';
require_once '../../cms/utils/print_page.php';

function submenu($catalog_id, $table, $flag = false){
  global $pdo;
  $tags = "";
  $query = "SELECT * FROM $table WHERE hide = 'show' AND id_parent = $catalog_id ORDER BY pos";
  $sub = $pdo->query($query);
  if (!$sub) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к блоку статей");
  }
  if (!$flag) {
    return $sub->rowCount();
  }else {
    if ($sub->rowCount()) {
      $tags;
      while ($subcatalog = $sub->fetch()) {
        $tags .= "<li><a href=\"sections.php?id_catalog=".$subcatalog['id_catalog']."\" >".htmlspecialchars($subcatalog['name'])."</a></li>";
      }
      return $tags;
    }
  }
}
function submenuUrls($catalog_id, $table, $flag = false){
  global $pdo;
  $tags = "";
  $query = "SELECT * FROM $table WHERE hide = 'show' AND id_catalog = $catalog_id ORDER BY pos";
  $pos = $pdo->query($query);
  if (!$pos) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к блоку статей");
  }
  if ($flag) {
    return $pos->rowCount();
  }
  if ($pos->rowCount() > 0 && $flag != true) {
    if ($pos->rowCount() == 1 && !$pos->rowCount()) {
      $datapos = $pos->fetchAll();
      $position = $datapos[0];
      if ($position['url'] != 'article') {
        $tags .= "<HTML><HEAD><META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'></HEAD></HTML>";
        exit();
      }
      $_GET['id_position'] = $position['id_position'];
      $_GET['id_catalog'] = $position['id_catalog'];
    }else {
      while ($position = $pos->fetch()) {
        if ($position['url'] != 'article') {
          if (stristr($position['url'], 'http') === false) {
            $tags .= "<li><a href=\"".htmlspecialchars($position['url'])."?id_catalog={$position['id_catalog']}\" >".htmlspecialchars($position['name'])."</a></li>";
          } else {
            $tags .= "<li><a href=\"".htmlspecialchars($position['url'])."\" >".htmlspecialchars($position['name'])."</a></li>";
          }
        }else {
          $sectUrl = str_replace("index", "sections", $_SERVER['PHP_SELF']);
          $tags .= "<li><a href=\"{$sectUrl}?id_catalog=$catalog_id&"."id_position=$position[id_position]\" >".htmlspecialchars($position['name'])."</a></li>";
        }
      }
    }
    return $tags;
  }
  return "";
}

try {
  $flag = false;
  if (empty($_GET['id_catalog']) && empty($_GET['id_position'])) {
    $query = "SELECT * FROM $tbl_catalog WHERE hide = 'show' AND id_parent = 0 ORDER BY pos";
    $flag = true;
  }elseif (!empty($_GET['id_catalog'])) {
    $query = "SELECT * FROM $tbl_catalog WHERE hide = 'show' AND id_parent = $_GET[id_catalog] ORDER BY pos";
    $flag = true;
  }
  if (empty($_GET['id_catalog'])) {
    $_GET['id_catalog'] = 0;
  }
  if ($flag) {
    $cat = $pdo->query($query);
    if (!$cat) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к блоку статей");
    }
    if ($cat->rowCount()) {
      while ($catalog = $cat->fetch()) {
          echo "<li><a href=sections.php?id_catalog=$catalog[id_catalog] >$catalog[name]</a></li>";
      }
    }
    $query = "SELECT * FROM $tbl_position WHERE hide = 'show' AND id_catalog = ".$_GET['id_catalog']." ORDER BY pos";
    $pos = $pdo->query($query);
    if (!$pos) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к блоку статей");
    }
    if ($pos->rowCount() > 0) {
      if ($pos->rowCount() == 1 && !$pos->rowCount()) {
        $datapos = $pos->fetchAll();
        $position = $datapos[0];
        if ($position['url'] != 'article') {
          echo "<HTML><HEAD><META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'></HEAD></HTML>";
          exit();
        }
        $_GET['id_position'] = $position['id_position'];
        $_GET['id_catalog'] = $position['id_catalog'];
      }else {
        while ($position = $pos->fetch()) {
          if ($position['url'] != 'article') {
            if (stristr($position['url'], 'http') === false) {
              echo "<li><a href=\"".htmlspecialchars($position['url'])."?id_catalog={$_GET['id_catalog']}\" >".htmlspecialchars($position['name'])."</a></li>";
            } else {
              echo "<li><a href=\"".htmlspecialchars($position['url'])."\" >".htmlspecialchars($position['name'])."</a></li>";
            }
          } else {
            echo "<li><a href=\"$_SERVER[PHP_SELF]?id_catalog=$_GET[id_catalog]&"."id_position=$position[id_position]\" >".htmlspecialchars($position['name'])."</a></li>";
          }
        }
      }
    }
  }

} catch (Exception $e) {

}
?>
