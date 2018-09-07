<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';

try {
  $page_link = 3;
  $page_number = 5;

  $obj = new PagerMySQL($pdo_lib, $tbl_reader, "", "ORDER BY receiptdate DESC", $page_number, $page_link);
  $name = "читателя";
  echo "<a href=readeradd.php?page=$_GET[page] titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  $reader = $obj->get_page();
  if (!empty($reader)) {
    ?>
    <div class="table-responsive">
    <table class="table table-hover table-striped text-center">
      <caption>
        Читатели
      </caption>
      <tr>
        <th>
          Читатель
        </th>
        <th>
          Дата рождения
        </th>
        <th>
          Телефон(ы)
        </th>
        <th>
          Адрес
        </th>
        <th>
          Дата
        </th>
        <th>
          Класс
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($reader); $i++) {
      $colorrow = "";
      if (!$page) {
        if (!empty($_GET['page'])) {
          $page = $_GET['page'];
        }else {
          $page = 1;
        }
      }
      $url = "?id_reader={$reader[$i][idreader]}&page=$page";
      $reader_url="";
      if (!empty($reader[$i]['url'])) {
        $reader[$i]['url'] = "http://{$reader[$i][url]}";
      }
      $reader_url = "<br><b>Ссылка:</b><a href='{$reader[$i][url]}'>{$reader[$i][urltext]}</a>";
      if (empty($reader[$i]['urltext'])) {
        $reader_url = "<br><b>Ссылка:</b><a href='{$reader[$i][url]}'>{$reader[$i][url]}</a>";
      }
      echo "<tr $colorrow>
        <td>
          {$reader[$i][surname]}&nbsp;{$reader[$i][name]}&nbsp;{$reader[$i][patronymic]}
        </td>
        <td>
          {$reader[$i][dateofbirth]}
        </td>
        <td>
          {$reader[$i][phonenumber]}<br>
          {$reader[$i][homephonenumber]}
        </td>
        <td>
          {$reader[$i][address]}
        </td>
        <td>
          {$reader[$i][receiptdate]}
        </td>
        <td>";
        if ($reader[$i][receiptclass] == 0) {
          echo "-";
        }else {
          echo $reader[$i][receiptclass];
        }
        echo "</td>
        <td align='right'>
          <a href=# onClick=\"delete_position('scr_readerdel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
          titlе='Удалить {$name}'>Удалить</а><br>
          <a href=scr_readeredit.php$url titlе='Редактировать {$name}'>Редактировать</а>
        </td>
      </tr>";
    }
    echo "</table></div><br>";
  }
  echo "<div class='text-center'>".$obj."</div>";
} catch (ExceptionMySQL $e) {
  require("../utils/exception_mysql.php");
}
require_once '../utils/bottom.php';
?>
