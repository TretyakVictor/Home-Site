<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';
// require_once '../utils/print_page.php';

try {
  $page_link = 3;
  $page_number = 15;

  $obj = new PagerMySQL($pdo_lib, $tbl_publishinghouse, "", "ORDER BY name ASC", $page_number, $page_link);
  $name = "издательство";
  echo "<a href=publishinghouseadd.php?page=$_GET[page] titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  $publishinghouse = $obj->get_page();
  if (!empty($publishinghouse)) {
    ?>
    <table class="table table-hover table-striped text-center">
      <caption>
        Издательства
      </caption>
      <tr>
        <th>
          Издательство
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($publishinghouse); $i++) {
      $colorrow = "";
      if (!$page) {
        if (!empty($_GET['page'])) {
          $page = $_GET['page'];
        }else {
          $page = 1;
        }
      }
      $url = "?id_publishinghouse={$publishinghouse[$i][idpublishinghouse]}&page=$page";
      $publishinghouse_url="";
      if (!empty($publishinghouse[$i]['url'])) {
        $publishinghouse[$i]['url'] = "http://{$publishinghouse[$i][url]}";
      }
      $publishinghouse_url = "<br><b>Ссылка:</b><a href='{$publishinghouse[$i][url]}'>{$publishinghouse[$i][urltext]}</a>";
      if (empty($publishinghouse[$i]['urltext'])) {
        $publishinghouse_url = "<br><b>Ссылка:</b><a href='{$publishinghouse[$i][url]}'>{$publishinghouse[$i][url]}</a>";
      }
      echo "<tr $colorrow>
        <td>
          {$publishinghouse[$i][name]}
        </td>
        <td align='right'>
          <a href=# onClick=\"delete_position('scr_publishinghousedel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
          titlе='Удалить {$name}'>Удалить</а><br>
          <a href=scr_publishinghouseedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
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
