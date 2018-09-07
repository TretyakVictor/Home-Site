<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';
require_once '../utils/print_page.php';

try {
  $page_link = 3;
  $page_number = 5;

  $obj = new PagerMySQL($pdo, $tbl_news, "", "ORDER BY putdate DESC", $page_number, $page_link);
  // echo "<a href=newsadd.php?page=$_GET[page] titlе='Добавить новость'>Добавить новость</a><br><br>";
  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
    echo "<a href=newsadd.php?page=$page titlе='Добавить новость'>Добавить новость</a><br><br>";
  } else {
    echo "<a href=newsadd.php?page=1 titlе='Добавить новость'>Добавить новость</a><br><br>";
  }
  $news = $obj->get_page();
  if (!empty($news)) {
    ?>
    <table class="table table-hover text-center">
      <caption>
        Новости
      </caption>
      <tr>
        <th>
          Дата
        </th>
        <th>
          Новость
        </th>
        <th>
          Изображение
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($news); $i++) {
      $class = "";
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
      $url = "?id_news={$news[$i]['id_news']}&page=$page";
      if ($news[$i]['hide'] == 'show') {
        $showhide = "<a href=scr_newshide.php$url title='Скрыть новость'>Скрыть</a>";
      }else {
        $showhide = "<a href=scr_newsshow.php$url title='Отобразить новость'>Отобразить</a>";
        $class = "class='bad'";
      }
      if ($news[$i]['urlpict'] != '' && $news[$i]['urlpict'] != '-' && is_file("../../".$news[$i]['urlpict'])) {
        $url_pict = "<b><a href=../../{$news[$i]['urlpict']}>есть</a></b>";
      }else {
        $url_pict = "нет";
      }
      $news_url="";
      if (!empty($news[$i]['url'])) {
        if (!preg_match("|^http://|i", $news[$i]['url'])) {
          $news[$i]['url'] = "http://{$news[$i]['url']}";
        }
        if (empty($news[$i]['urltext'])) {
          $news_url = "<br><b>Ссылка: </b><a href='{$news[$i]['url']}'>{$news[$i]['url']}</a>";
        }else {
          $news_url = "<br><b>Ссылка: </b><a href='{$news[$i]['url']}'>{$news[$i]['urltext']}</a>";
        }
      }
      list($date, $time) = explode(" ", $news[$i]['putdate']);
      list($year, $month, $day) = explode("-", $date);
      $news[$i]['putdate'] = "$day.$month.$year $time";
      echo "<tr $class>
        <td>
          {$news[$i]['putdate']}
        </td>
        <td class='text-left'>
          <a href='scr_newsedit.php'$url titlе='Редактировать текст новости'>{$news[$i]['name']}</a><br>
          ".nl2br(print_page($news[$i]['body']))." $news_url
        </td>
        <td>
          $url_pict
        </td>
        <td class='text-right'>
          $showhide<br>
          <a href=# onClick=\"delete_position('scr_newsdel.php$url',"."'Вы действительно хотите удалить новостное сообщение?');\"
          titlе='Удалить новость'>Удалить</а><br>
          <a href=scr_newsedit.php$url titlе='Редактировать текст новости'>Редактировать</а>
        </td>
      </tr>";
    }
    echo "</table><br>";
  }

  echo "<div class='text-center'>".$obj."</div>";
} catch (ExceptionMySQL $e) {
  echo "Error";
}
require_once '../utils/bottom.php';
?>
