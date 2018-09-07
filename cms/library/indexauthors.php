<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';
// require_once '../utils/print_page.php';

try {
  $page_link = 3;
  $page_number = 15;

  $obj = new PagerMySQL($pdo_lib, $tbl_authors, "", "ORDER BY name ASC", $page_number, $page_link);
  $name = "автора";
  echo "<a href=authorsadd.php?page=$_GET[page] titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  $authors = $obj->get_page();
  if (!empty($authors)) {
    ?>
    <table class="table table-hover table-striped text-center">
      <caption>
        Авторы
      </caption>
      <tr>
        <th>
          Автор
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($authors); $i++) {
      $colorrow = "";
      if (!$page) {
        if (!empty($_GET['page'])) {
          $page = $_GET['page'];
        }else {
          $page = 1;
        }
      }
      $url = "?id_authors={$authors[$i][idauthors]}&page=$page";
      $authors_url="";
      if (!empty($authors[$i]['url'])) {
        $authors[$i]['url'] = "http://{$authors[$i][url]}";
      }
      $authors_url = "<br><b>Ссылка:</b><a href='{$authors[$i][url]}'>{$authors[$i][urltext]}</a>";
      if (empty($authors[$i]['urltext'])) {
        $authors_url = "<br><b>Ссылка:</b><a href='{$authors[$i][url]}'>{$authors[$i][url]}</a>";
      }
      echo "<tr $colorrow>
        <td>
          {$authors[$i][name]}
        </td>
        <td align='right'>
          <a href=# onClick=\"delete_position('scr_authorsdel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
          titlе='Удалить {$name}'>Удалить</а><br>
          <a href=scr_authorsedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
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
