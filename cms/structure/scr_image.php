<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/navigation.php';
require_once '../utils/print_page.php';

$_GET['id_catalog']   = intval($_GET['id_catalog']);
$_GET['id_position']  = intval($_GET['id_position']);
$_GET['id_paragraph'] = intval($_GET['id_paragraph']);

try {
  $stmt = $pdo->prepare("SELECT * FROM $tbl_catalog WHERE id_catalog = ? LIMIT 1");
  $stmt->execute([$_GET['id_catalog']]);
  $stmt = $stmt->fetchAll();
  $catalog = $stmt[0];

  $stmt = $pdo->prepare("SELECT * FROM $tbl_position WHERE id_position = ? LIMIT 1");
  $stmt->execute([$_GET['id_position']]);
  $stmt = $stmt->fetchAll();
  $position = $stmt[0];

  require_once '../utils/head.php';
  echo '<h1>Администрирование изображений.</h1>';

  if ($_GET['id_catalog'] != 0) {
    echo '<table class="table-hover" cellspacing="0" border=0>
      <tr>
        <td>
        <a class=menu href=index.php?id_parent=0>Корневой каталог</a> -&gt; '.menu_navigation($_GET['id_catalog'], "", $tbl_catalog).
          '<a href=scr_paragraph.php?id_position='.$_GET[id_position].'&id_catalog='.$_GET[id_catalog].'&page='.$_GET[page].'>'.$position[name].'</a>
        </td>
      </tr>
    </table>';
  }

  echo "<a class=menu href=scr_imageadd.php?id_catalog=$_GET[id_catalog]&id_position=$_GET[id_position]&id_paragraph=$_GET[id_paragraph]>Добавить изображение</a><br><br>";

  $page_link = 3;
  $page_number = 10;
  $obj = new PagerMySQL($pdo, $tbl_paragraph_image, "WHERE id_position = $_GET[id_position] AND id_catalog=$_GET[id_catalog] AND id_paragraph = $_GET[id_paragraph]",
  "ORDER BY pos", $page_number, $page_link, "&id_position=$_GET[id_position]&id_catalog=$_GET[id_catalog]&id_paragraph=$_GET[id_paragraph]");
  $name = "изображение";
  $image = $obj->get_page();
  if (!empty($image)) {
    ?>
    <div class="table-responsive">
    <table class="table table-hover table-striped text-center">
      <caption>
        Изображения
      </caption>
      <tr>
        <th>
          Название
        </th>
        <th>
          Размер отображения
        </th>
        <th>
          Описание
        </th>
        <th>
          Позиция
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($image); $i++) {
      $class = "";
      if (!$page) {
        if (!empty($_GET['page'])) {
          $page = $_GET['page'];
        }else {
          $page = 1;
        }
      }
      $url = "id_image={$image[$i][id_image]}&id_paragraph=$_GET[id_paragraph]&id_position=$_GET[id_position]&id_catalog=$_GET[id_catalog]&page=$_GET[page]";
      if ($image[$i]['hide'] == 'hide') {
        $class .= "class='bad '";
        $strhide = "<a href=scr_imageshow.php?$url><span class='glyphicon glyphicon-eye-open'></span></a>";
      } else {
        $class .= "class='good '";
        $strhide = "<a href=scr_imagehide.php?$url><span class='glyphicon glyphicon-eye-close'></span></a>";
      }

      if ($image[$i]['mode'] == 0) {
        $mode = "<a href=scr_imagemode.php?flag=1&$url><span class='glyphicon glyphicon-open-file'></span></a>";
      } else {
        $mode = "<a href=scr_imagemode.php?flag=0&$url><span class='glyphicon glyphicon-save-file'></span></a>";
      }

      $image_print = "<img src=../../".$image[$i]['small']." border=0 vspace=2>";
      if (!empty($image[$i]['big'])) {
        if (file_exists("../../".$image[$i]['big'])) {
          $size = @getimagesize("../../".$image[$i]['big']);
          $image_print = "<a href=# onClick=\"show_img('../../".$image[$i][big]."',".$size[0].",".$size[1]."); return false \" >
          <img src=../../".$image[$i]['small']." border=0 vspace=2></a>";
        }
      }

      echo "<tr $class>
        <td>
          ".$image_print."</br>".print_page($image[$i]['name'])."&nbsp;
        </td>
        <td>";
        if ($image[$i]['mode']) {
          echo "Большой";
        } else {
          echo "Маленький";
        }
        echo "</td>
        <td>
          ".print_page($image[$i]['alt'])."
        </td>
        <td>
          ".$image[$i]['pos']."
        </td>
        <td align='right'>
          <a href=scr_imageup.php?$url><span class='glyphicon glyphicon-chevron-up'></span></a><br>
          $strhide<br>
          $mode<br>
          <a href=# onClick=\"delete_position('scr_imagedel.php?$url',"."'Вы действительно хотите удалить {$name}?');\"><span class='glyphicon glyphicon-trash'></span></a><br>
          <a href=scr_imagedown.php?$url><span class='glyphicon glyphicon-chevron-down'></span></a>
        </td>
      </tr>";
    }
    echo "</table></div><br>";
  }
} catch (Exception $e) {

}
require_once '../utils/bottom.php';
?>
<script src="../utils/js/show_img.js"></script>
