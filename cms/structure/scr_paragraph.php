<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/print_page.php';
require_once '../utils/navigation.php';

try {
  $_GET['id_catalog']  = intval($_GET['id_catalog']);
  $_GET['id_position'] = intval($_GET['id_position']);

  $cat = $pdo->prepare("SELECT * FROM $tbl_catalog WHERE id_catalog = ? LIMIT 1");
  $cat->execute([$_GET[id_catalog]]);
  $caterr = $cat->fetchAll();
  $catalog = $caterr[0];

  $pos = $pdo->prepare("SELECT * FROM $tbl_position WHERE id_position = ?");
  $pos->execute([$_GET[id_position]]);
  $poserr = $pos->fetchAll();
  $position = $poserr[0];

  $page_link = 3;
  $page_number = 15;
  $obj = new PagerMySQL($pdo, $tbl_paragraph, "WHERE id_position = $_GET[id_position] AND id_catalog=$_GET[id_catalog]",
  "ORDER BY pos", $page_number, $page_link, "&id_position=$_GET[id_position]&id_catalog=$_GET[id_catalog]");
  $name = "параграф";
  $paragraph = $obj->get_page();

  require_once '../utils/head.php';

  echo '<h1>Администрирование элементов статьи.</h1>';

  if ($_GET['id_catalog'] != 0) {
    echo '<table class="table-hover" cellspacing="0" border=0>
      <tr>
        <td>
        <a class=menu href=index.php?id_parent=0>Корневой каталог</a> -&gt; '.menu_navigation($_GET['id_catalog'], "", $tbl_catalog).$position[name].'
        </td>
      </tr>
    </table>';
  }

  echo "<form action=scr_paradd.php>
    <input class='btn btn-default btn-sm' type=submit value='Добавить параграф'>
    <input type=hidden name=page value='$_GET[page]'>
    <input type=hidden name=id_catalog value=$_GET[id_catalog]>
    <input type=hidden name=id_position value=$_GET[id_position]>
    <table width='100%' class='table table-hover' border='0'>
      <tr class='header' align='center'>
        <td width=20 align=center><input type=radio name=pos value=-1></td>
        <td align=center>Заголовок</td>
        <td width=100 align=center>Изображения<br> и файлы</td>
        <td width=100 align=center>Тип</td>
        <td width=20 align=center>Позиция</td>
        <td width=50>Действия</td>
      </tr>";
  if (!empty($paragraph)) {
    for ($i=0; $i < count($paragraph); $i++) {
      $class = "";
      $url = "id_paragraph={$paragraph[$i][id_paragraph]}&id_position=$_GET[id_position]&id_catalog=$_GET[id_catalog]&page=$_GET[page]";
      $type = "Параграф";
      switch ($paragraph[$i]['type']) {
        case 'text':
          $type = "Параграф";
          break;
        case 'title_h1':
          $type = "Заголовок H1";
          break;
        case 'title_h2':
          $type = "Заголовок H2";
          break;
        case 'title_h3':
          $type = "Заголовок H3";
          break;
        case 'title_h4':
          $type = "Заголовок H4";
          break;
        case 'title_h5':
          $type = "Заголовок H5";
          break;
        case 'list':
          $type = "Список";
          break;
        case 'listnum':
          $type = "Список нумерованный";
          break;
        case 'table':
          $type = "Таблица";
          break;
        case 'code':
          $type = "Код";
          break;
        default:
          $type = "Параграф";
          break;
      }
      $align = "";
      switch ($paragraph[$i]['align']) {
        case 'left':
          $align = "align=left";
          break;
        case 'center':
          $align = "align=center";
          break;
        case 'right':
          $align = "align=right";
          break;
      }
      if ($paragraph[$i]['hide'] == 'hide') {
        $class .= "class='bad '";
        $strhide = "<a href=scr_parshow.php?$url><span class='glyphicon glyphicon-eye-open'></span></a>";
      }else {
        $class .= "class=' '";
        $strhide = "<a href=scr_parhide.php?$url><span class='glyphicon glyphicon-eye-close'></span></a>";
      }
      $query = "SELECT COUNT(*) FROM $tbl_paragraph_image WHERE id_paragraph = ? AND id_position = ? AND id_catalog = ?";
      $stmt = $pdo->prepare($query);
      $stmt->bindValue(1, $paragraph[$i]['id_paragraph'], PDO::PARAM_INT);
      $stmt->bindValue(2, $_GET['id_position'], PDO::PARAM_INT);
      $stmt->bindValue(3, $_GET['id_catalog'], PDO::PARAM_INT);
      $stmt->execute();
      $total_image = $stmt->fetchColumn();
      if ($total_image) {
        $print_image = " ($total_image)";
      } else {
        $print_image = "";
      }

      echo "<tr $class>
        <td class='text-center'>
          <input type=radio name=pos value=".$paragraph[$i]['pos']." checked>
        </td>
        <td>
          <p $align>";
          if ($type == "Таблица") {
            echo "<table class='table table-inverse table-hover'>".nl2br(print_page($paragraph[$i]['name']))."</table>";
          } elseif ($type == "Код") {
            echo print_page($paragraph[$i]['name']);
          } else {
            echo nl2br(print_page($paragraph[$i]['name']));
          }
          echo "</p>
        </td>
        <td class='text-center'>
          <a href=scr_image.php?$url>Изображения$print_image</a>
        </td>
        <td class='text-center'>".print_page($type)."</td>
        <td class='text-center'>".$paragraph[$i]['pos']."</td>
        <td class='text-center'>
          <a href=scr_parup.php?$url><span class='glyphicon glyphicon-chevron-up'></span></a><br>
          $strhide<br>
          <a href=scr_paredit.php?$url><span class='glyphicon glyphicon-edit'></span></a><br>
          <a href=# onClick=\"delete_position('scr_pardel.php?$url',"."'Вы действительно хотите удалить {$name}?');\"><span class='glyphicon glyphicon-trash'></span></a><br>
          <a href=scr_pardown.php?$url><span class='glyphicon glyphicon-chevron-down'></span></a>
        </td>
      </tr>";
    }
    echo "</table>";
  }
  echo "</form>";
  echo $obj->print_page();

} catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
} catch (ExceptionObject $e) {
  require '../utils/exception_object.php';
} catch (ExceptionMember $e) {
  require '../utils/exception_member.php';
}
require_once '../utils/bottom.php';
?>
