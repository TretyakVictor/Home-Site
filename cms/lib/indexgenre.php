<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';

try {
  $page_link = 3;
  $page_number = 15;

  $obj = new PagerMySQL($pdo_Lib, $tbl_Lib_genre, "", "ORDER BY name ASC", $page_number, $page_link);
  $name = "жанр";
  echo "<a href=genreadd.php?page=$_GET[page] titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  $genre = $obj->get_page();
  if (!empty($genre)) {
    ?>
    <table class="table table-hover table-striped text-center">
      <caption>
        Жанры
      </caption>
      <tr>
        <th>
          Жанр
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($genre); $i++) {
      $colorrow = "";
      if (!$page) {
        if (!empty($_GET['page'])) {
          $page = $_GET['page'];
        }else {
          $page = 1;
        }
      }
      $url = "?id_genre={$genre[$i]['idgenre']}&page=$page";
      $genre_url="";
      if (!empty($genre[$i]['url'])) {
        $genre[$i]['url'] = "http://{$genre[$i]['url']}";
      }
      $genre_url = "<br><b>Ссылка:</b><a href='{$genre[$i]['url']}'>{$genre[$i]['urltext']}</a>";
      if (empty($genre[$i]['urltext'])) {
        $genre_url = "<br><b>Ссылка:</b><a href='{$genre[$i]['url']}'>{$genre[$i]['url']}</a>";
      }
      echo "<tr $colorrow>
        <td>
          {$genre[$i]['name']}
        </td>
        <td align='right'>
          <a href=# onClick=\"delete_position('scr_genredel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
          titlе='Удалить {$name}'>Удалить</а><br>
          <a href=scr_genreedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
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
