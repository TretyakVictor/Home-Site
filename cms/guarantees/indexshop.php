<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';

try {
  $page_link = 3;
  $page_number = 15;

  $obj = new PagerMySQL($pdo_grnt, $tbl_shops, "", "ORDER BY idshops DESC", $page_number, $page_link);
  $name = "магазин";
  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
    echo "<a href=shopadd.php?page=$page titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  } else {
    echo "<a href=shopadd.php?page=1 titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  }
  $shop = $obj->get_page();
  if (!empty($shop)) {
    ?>
    <table class="table table-hover table-striped text-center">
      <caption>
        Магазины
      </caption>
      <tr>
        <th>
          Магазин
        </th>
        <th>
          Адрес
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($shop); $i++) {
      $colorrow = "";
      // if (!$page) {
      //   if (!empty($_GET['page'])) {
      //     $page = $_GET['page'];
      //   }else {
      //     $page = 1;
      //   }
      // }
      if (!empty($_GET['page'])) {
        $page = $_GET['page'];
      }else {
        $page = 1;
      }
      $url = "?id_shop={$shop[$i]['idshops']}&page=$page";
      $shop_url="";
      if (!empty($shop[$i]['url'])) {
        $shop[$i]['url'] = "http://{$shop[$i]['url']}";
      }
      // $shop_url = "<br><b>Ссылка:</b><a href='{$shop[$i]['url']}'>{$shop[$i]['urltext']}</a>";
      // if (empty($shop[$i]['urltext'])) {
      //   $shop_url = "<br><b>Ссылка:</b><a href='{$shop[$i]['url']}'>{$shop[$i]['url']}</a>";
      // }
      if (!empty($shop[$i]['urltext'])) {
        $shop_url = "<br><b>Ссылка:</b><a href='{$shop[$i]['url']}'>{$shop[$i]['urltext']}</a>";
      } elseif (!empty($shop[$i]['url'])) {
        $shop_url = "<br><b>Ссылка:</b><a href='{$shop[$i]['url']}'>{$shop[$i]['url']}</a>";
      }
      echo "<tr $colorrow>
        <td>
          {$shop[$i]['name']}
        </td>
        <td>
          {$shop[$i]['address']}
        </td>
        <td align='right'>
          <a href=# onClick=\"delete_position('scr_shopdel.php$url',"."'Вы действительно хотите удалить {$name}?');\">Удалить</a><br>
          <a href=scr_shopedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
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
