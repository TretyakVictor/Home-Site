<?php
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';
require_once '../templates/head.php';
require_once '../templates/navigation.php';

try {
  if (empty($_GET['id_position'])) {
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    $title; $description;
    $links = '<table class="table-hover " cellspacing="0" border=0>
      <tr>
        <td>
          <a href=index.php?id_catalog=0>Главная</a> -&gt; '.menu_navigation($_GET['id_catalog'], "", $tbl_catalog, true).'
        </td>
      </tr>
    </table>';
    echo $links.$title.$description;
    $par = $pdo->prepare("SELECT `T1`.`id_paragraph`,`T1`.`name`,`T1`.`type`,`T1`.`align`,`T1`.`hide`,`T1`.`pos`,`T1`.`id_position`,`T1`.`id_catalog`, `T2`.`name` AS `article_name`
      FROM $tbl_paragraph `T1` LEFT JOIN $tbl_position `T2` ON `T1`.id_position = `T2`.id_position
    WHERE `T1`.id_catalog = ? AND  `T1`.`pos` < 3 GROUP BY `T1`.`name`,`T1`.`type`,`T1`.`align`,`T1`.`hide`,`T1`.`pos`,`T1`.`id_position`,`T1`.`id_paragraph` ORDER BY `T1`.`id_position`, `T1`.`pos`");
    // $par = $pdo->prepare("SELECT `T1`.*, `T2`.`name` AS `article_name` FROM $tbl_paragraph `T1` LEFT JOIN $tbl_position `T2` ON `T1`.id_position = `T2`.id_position
    // WHERE `T1`.id_catalog = ? AND  `T1`.`pos` < 3 ");
    $par->execute([$_GET['id_catalog']]);

    if ($par->rowCount() > 0) {
      $i = 0;
      echo "<h1>Статьи раздела:</h1>";
      while ($paragraph = $par->fetch()) {
        $position[$i] = $paragraph['id_position'];
        $artcl = "<a href=\"?id_catalog=".$paragraph['id_catalog']."&id_position=".$paragraph['id_position']."\" ><h2>".$paragraph['article_name']."</h2></a>";
        if ($i == 0) {
          echo $artcl."<div class='well well-sm'>";
        }
        if ($i > 1) {
          if ($position[$i] != $position[$i-1]) {
            echo "</div>".$artcl."<div class='well well-sm'>";
          }
        }
        // if ($position[$i] != $position[$i-1] && $i != 0 && $i != 1) {
        //   echo "</div>".$artcl."<div class='well well-sm'>";
        // }
        ++$i;
        $align = "";
        switch ($paragraph['align']) {
          case 'left':
            $align = "left";
            break;
          case 'center':
            $align = "center";
            break;
          case 'right':
            $align = "right";
            break;
        }

        switch ($paragraph['type']) {
          case 'text':
            echo "<div align=$align>".
                    // nl2br(print_page($paragraph['name']))."</br>$image_print</div>";
                    nl2br(print_page($paragraph['name']))."</br></div>";
            break;
          case 'title_h1':
            echo "<h1 align=$align>".
                    print_page($paragraph['name']).
                 "</h1>";
            break;
          case 'title_h2':
            echo "<h2 align=$align>".
                    print_page($paragraph['name']).
                 "</h2>";
            break;
          case 'title_h3':
            echo "<h3 align=$align>".
                  print_page($paragraph['name']).
               "</h3>";
            break;
          case 'title_h4':
            echo "<h4 align=$align>".
                  print_page($paragraph['name']).
               "</h4>";
            break;
          case 'title_h5':
            echo "<h5 align=$align>".
                  print_page($paragraph['name']).
               "</h5>";
            break;
          case 'list':
            $arr = explode("\r\n", $paragraph['name']);
            if (!empty($arr)) {
              echo "<div align=$align><ul>";
              for ($i=0; $i < count($arr); $i++) {
                echo "<li>".print_page($arr[$i])."</li>";
              }
              echo "</ul></div><br>";
            }
            break;
        }
      }
      echo "</div>";
    }
  }

  if (!empty($_GET['id_position'])) {
    $_GET['id_position'] = intval($_GET['id_position']);

    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    $links = '<table class="table-hover " cellspacing="0" border=0>
      <tr>
        <td>
          <a href=index.php?id_catalog=0>Главная</a> -&gt; '.menu_navigation($_GET['id_catalog'], "", $tbl_catalog, true).'
        </td>
      </tr>
    </table>';
    echo $links;

    $pos = $pdo->prepare("SELECT * FROM $tbl_position WHERE hide = 'show' AND id_position = ?");
    $pos->execute([$_GET['id_position']]);
    if ($pos->rowCount()) {
      $datapos = $pos->fetchAll();
      $position = $datapos[0];
      if ($position['url'] != 'article') {
        echo "<HTML><HEAD><META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'></HEAD></HTML>";
        exit();
      }

      $_GET['id_catalog'] = $position['id_catalog'];
      $keywords = $position['keywords'];

      require_once 'scr_article_print.php';
    }
  }

} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

require_once '../templates/bottom.php';
?>
