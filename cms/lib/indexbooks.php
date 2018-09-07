<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
try {
  $page_link = 3;
  $page_number = 30;

  $obj = new PagerMySQL($pdo_Lib, $tbl_Lib_books, "", "ORDER BY name ASC", $page_number, $page_link);
  $name = "книгу";
  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
    echo "<a href=bookadd.php?page=$page titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  } else {
    echo "<a href=bookadd.php?page=1 titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  }
  $books = $obj->get_page();
  if (!empty($books)) {
    ?>
    <div class="table-responsive">
    <table class="table table-hover table-striped text-center">
      <caption>
        Книги
      </caption>
      <tr>
        <th>
          Наименование
        </th>
        <th>
          Обложка
        </th>
        <th>
          Описание
        </th>
        <th>
          Дата выпуска
        </th>
        <th>
          Статус
        </th>
        <th>
          Сайт
        </th>
        <th>
          Кол-во томов/номеров
        </th>
        <th>
          Издательство
        </th>
        <th>
          Жанр
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php


    $data = $pdo_Lib->prepare("SELECT * FROM $tbl_Lib_genre `T1` INNER JOIN $tbl_Lib_genries `T2` ON `T1`.`idgenre` = `T2`.`genre_idgenre` ORDER BY `T2`.`books_idbooks`");
    $data->execute();
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $genries[] = [$dat['books_idbooks'] => $dat['name']];
      };
    }

    $data = $pdo_Lib->prepare("SELECT * FROM $tbl_Lib_publishinghouse");
    $data->execute();
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $publishinghouse[$dat['idpublishinghouse']] = $dat['name'];
      };
    }
    $data = $pdo_Lib->prepare("SELECT * FROM $tbl_Lib_images");
    $data->execute();
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $imagesurl_big[$dat['idimages']] = $dat['big'];
        $imagesurl_small[$dat['idimages']] = $dat['small'];
      };
    }

    $arr_status = array('finished' => 'Выпуск завершен', 'unfinished' => 'Выпуск не завершен');
    for ($i=0; $i < count($books); $i++) {
      $class = "";
      if (!empty($_GET['page'])) {
        $page = intval($_GET['page']);
      }else {
        $page = 1;
      }
      $url = "?id_books={$books[$i]['idbooks']}&page=$page";
      $books_url="";
      if (!empty($books[$i]['url'])) {
        $books[$i]['url'] = "http://{$books[$i]['url']}";
      }
      if (!empty($books[$i]['urltext'])) {
        $books_url = "<br><b>Ссылка:</b><a href='{$books[$i]['url']}'>{$books[$i]['urltext']}</a>";
      } elseif (!empty($books[$i]['url'])) {
        $books_url = "<br><b>Ссылка:</b><a href='{$books[$i]['url']}'>{$books[$i]['url']}</a>";
      }
      // if (condition) {
      //   # code...
      // } else {
      //   $size = @getimagesize("../../".$image['big']);
      //   $img_arr[] = "<a href=# onClick=\"show_img('../../".$image['big']."',".$size[0].",".$size[1]."); return false \" >
      //   <img src=../../".$image['small']." border=0 vspace=2></a>$name";
      // }
      // $img = "<img width='100%' src='".$imagesurl[$books[$i]['images_idimages']]."'>";
      // $imagesurl_small

      // $size = @getimagesize("../../".$imagesurl_big[$books[$i]['images_idimages']]);
      $size = @getimagesize("../../".$imagesurl_big[$books[$i]['images_idimages']]);
      $img = "<a href=# onClick=\"show_img('../../".$imagesurl_big[$books[$i]['images_idimages']]."',".$size[0].",".$size[1]."); return false \" >
      <img src=../../".$imagesurl_small[$books[$i]['images_idimages']]." border=0 vspace=2></a>".$imagesurl_small[$books[$i]['images_idimages']];
      // echo $imagesurl_small[$books[$i]['images_idimages']];

      echo "<tr $class>
        <td>
          {$books[$i]['name']}
        </td>
        <td>
          {$img}
        </td>
        <td>
          {$books[$i]['discription']}
        </td>
        <td>";
          $date = new DateTime($books[$i]['date']);
          echo "{$date->format('d.m.Y')}
        </td>
        <td>";
          echo $arr_status[$books[$i]['status']]."
        </td>
        <td>
          {$books[$i]['site']}
        </td>
        <td>
          {$books[$i]['bookscol']}
        </td>
        <td>
          {$publishinghouse[$books[$i]['publishinghouse_idpublishinghouse']]}
        </td>
        <td>";
          foreach ($genries as $value) {
            if (isset($value[$books[$i]['idbooks']])) {
              echo $value[$books[$i]['idbooks']]."<br />";
            }
          }
          echo "
        </td>

        <td class='text-right'>
          <a href=# onClick=\"delete_position('scr_booksdel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
          titlе='Удалить {$name}'>Удалить</а><br>
          <a href=scr_booksedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
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
<script src="../../modules/templates/js/show_img.js"></script>
