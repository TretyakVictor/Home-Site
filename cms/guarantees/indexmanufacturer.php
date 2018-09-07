<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';

try {
  $page_link = 3;
  $page_number = 15;

  $obj = new PagerMySQL($pdo_grnt, $tbl_manufacturer, "", "ORDER BY idmanufacturer DESC", $page_number, $page_link);
  $name = "производителя";
  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
    echo "<a href=manufactureradd.php?page=$page titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  } else {
    echo "<a href=manufactureradd.php?page=1 titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  }
  // echo "<a href=manufactureradd.php?page=$_GET[page] titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  $manufacturer = $obj->get_page();
  if (!empty($manufacturer)) {
    ?>
    <table class="table table-hover table-striped text-center">
      <caption>
        Производители
      </caption>
      <tr>
        <th>
          Производитель
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($manufacturer); $i++) {
      $colorrow = "";
      if (!empty($_GET['page'])) {
        $page = intval($_GET['page']);
      }else {
        $page = 1;
      }
      // if (!$page) {
      //   if (!empty($_GET['page'])) {
      //     $page = $_GET['page'];
      //   }else {
      //     $page = 1;
      //   }
      // }
      $url = "?id_manufacturer={$manufacturer[$i]['idmanufacturer']}&page=$page";
      $manufacturer_url = "";
      if (!empty($manufacturer[$i]['url'])) {
        $manufacturer[$i]['url'] = "http://{$manufacturer[$i]['url']}";
      }
      if (!empty($manufacturer[$i]['urltext'])) {
        $manufacturer_url = "<br><b>Ссылка:</b><a href='{$manufacturer[$i]['url']}'>{$manufacturer[$i]['urltext']}</a>";
      } elseif (!empty($manufacturer[$i]['url'])) {
        $manufacturer_url = "<br><b>Ссылка:</b><a href='{$manufacturer[$i]['url']}'>{$manufacturer[$i]['url']}</a>";
      }
      echo "<tr $colorrow>
        <td>
          {$manufacturer[$i]['name']}
        </td>
        <td align='right'>
          <a href=# onClick=\"delete_position('scr_manufacturerdel.php$url',"."'Вы действительно хотите удалить {$name}?');\">Удалить</a><br>
          <a href=scr_manufactureredit.php$url titlе='Редактировать {$name}'>Редактировать</а>
        </td>
      </tr>";
    }
    echo "</table><br>";
  }
  echo "<div class='text-center'>".$obj."</div>";
} catch (ExceptionMySQL $e) {
  require("../utils/exception_mysql.php");
}
require_once '../utils/bottom.php';
?>
