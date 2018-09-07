<?php
require_once '../../cms/utils/print_page.php';
if (!preg_match("|^[\d]+$|",$_GET['id_position'])) {
  return;
}
if (!preg_match("|^[\d]+$|",$_GET['id_catalog'])) {
  return;
}

$par = $pdo->prepare("SELECT * FROM $tbl_paragraph WHERE id_position = ? AND id_catalog = ? AND hide = 'show' ORDER BY pos");
$par->bindValue(1, $_GET['id_position'], PDO::PARAM_INT);
$par->bindValue(2, $_GET['id_catalog'], PDO::PARAM_INT);
$par->execute();

if ($par->rowCount() > 0) {
  while ($paragraph = $par->fetch()) {
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

    $image_print = "";
    $img = $pdo->prepare("SELECT * FROM $tbl_paragraph_image WHERE id_paragraph = ? AND id_position = ? AND id_catalog = ? AND hide = 'show'");
    $img->bindValue(1, $paragraph['id_paragraph'], PDO::PARAM_INT);
    $img->bindValue(2, $_GET['id_position'], PDO::PARAM_INT);
    $img->bindValue(3, $_GET['id_catalog'], PDO::PARAM_INT);
    $img->execute();
    if ($img->rowCount() > 0) {
      unset($img_arr);
      while ($image = $img->fetch()) {
        if (!empty($image['alt'])) {
          $alt = "alt='".$image['alt']."'";
        } else {
          $alt = "";
        }
        if (!empty($image['name'])) {
          $name = "<br><b>".$image['name']."</b>";
        } else {
          $name = "";
        }
        if ($image['mode'] == true) {
          $img_arr[] = "<img $alt width='100%' src='../../$image[big]'>$name";
        } else {
          $size = @getimagesize("../../".$image['big']);
          $img_arr[] = "<a href=# onClick=\"show_img('../../".$image['big']."',".$size[0].",".$size[1]."); return false \" >
          <img src=../../".$image['small']." border=0 vspace=2></a>$name";
        }
      }
      for ($i = 0; $i < (count($img_arr) % 3); $i++) {
        $img_arr[] = "";
      }
      for ($i = 0, $k = 0; $i < count($img_arr); $i++, $k++) {
        if ($k == 0) {
          $image_print .= "<table class='text-center'><tr valign=top>";
        }
        $image_print .= "<td>".$img_arr[$i]."</td>";
        if ($k == 2) {
          $k = -1;
          $image_print .= "</tr></table><br>";
        }
      }
    }

    switch ($paragraph['type']) {
      case 'text':
        echo "<div align=$align>".nl2br(print_page($paragraph['name']))."</br>$image_print</div>";
        break;
      case 'title_h1':
        echo "<h1 align=$align>".print_page($paragraph['name'])."</h1>";
        break;
      case 'title_h2':
        echo "<h2 align=$align>".print_page($paragraph['name'])."</h2>";
        break;
      case 'title_h3':
        echo "<h3 align=$align>".print_page($paragraph['name'])."</h3>";
        break;
      case 'title_h4':
        echo "<h4 align=$align>".print_page($paragraph['name'])."</h4>";
        break;
      case 'title_h5':
        echo "<h5 align=$align>".print_page($paragraph['name'])."</h5>";
        break;
      case 'listnum':
        $arr = explode("\r\n", $paragraph['name']);
        if (!empty($arr)) {
          echo "<div align=$align><ol>";
          for ($i=0; $i < count($arr); $i++) {
            echo "<li>".print_page($arr[$i])."</li>";
          }
          echo "</ol></div><br>";
        }
        break;
      case 'table':
        $arr = explode("\r\n", $paragraph['name']);
        if (!empty($arr)) {
          echo "<div align=$align><table class='table table-striped table-hover'>";
          for ($i=0; $i < count($arr); $i++) {
            echo print_page($arr[$i]);
          }
          echo "</table></div><br>";
        }
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
      case 'code':
        echo print_page($paragraph['name']);
        break;
    }
  }
}
?>
<script src="../templates/js/show_img.js"></script>
<br>
