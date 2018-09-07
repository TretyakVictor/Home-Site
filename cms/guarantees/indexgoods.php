<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';

try {
  $page_link = 3;
  $page_number = 30;

  $obj = new PagerMySQL($pdo_grnt, $tbl_goods, "", "ORDER BY idgoods DESC", $page_number, $page_link);
  $name = "товар";
  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
    echo "<a href=goodsadd.php?page=$page titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  } else {
    echo "<a href=goodsadd.php?page=1 titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
  }
  $goods = $obj->get_page();
  if (!empty($goods)) {
    ?>
    <div class="table-responsive">
    <table class="table table-hover table-striped text-center">
      <caption>
        Товары
      </caption>
      <tr>
        <th>
          Магазин
        </th>
        <th>
          Производитель
        </th>
        <th>
          Товар(наименование)
        </th>
        <th>
          Кол-во
        </th>
        <th>
          Цена
        </th>
        <th>
          Гарантия
        </th>
        <th>
          Дата
        </th>
        <th>
          Номер
        </th>
        <th>
          Доступность
        </th>
        <th>
          Статус
        </th>
        <th>
          Дата добавления
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php


    $query = "SELECT `idmanufacturer`, `name` FROM $tbl_manufacturer";
    $data = $pdo_grnt->query($query);
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $manufacturer[$dat['idmanufacturer']] = $dat['name'];
      };
    }
    $query = "SELECT `idshops`, `name` FROM $tbl_shops";
    $data = $pdo_grnt->query($query);
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $shops[$dat['idshops']] = $dat['name'];
      };
    }

    $arr_status = array('In stock' => 'В наличии', 'Returned' => 'Возврат по гарантии');
    for ($i=0; $i < count($goods); $i++) {
      $class = "";
      // if (!$page) {
      //   if (!empty($_GET['page'])) {
      //     $page = $_GET['page'];
      //   }else {
      //     $page = 1;
      //   }
      // }
      if (!empty($_GET['page'])) {
        $page = intval($_GET['page']);
      }else {
        $page = 1;
      }
      $url = "?id_goods={$goods[$i]['idgoods']}&page=$page";
      $goods_url="";
      if (!empty($goods[$i]['url'])) {
        $goods[$i]['url'] = "http://{$goods[$i]['url']}";
      }
      // $goods_url = "<br><b>Ссылка:</b><a href='{$goods[$i]['url']}'>{$goods[$i]['urltext']}</a>";
      if (!empty($goods[$i]['urltext'])) {
        $goods_url = "<br><b>Ссылка:</b><a href='{$goods[$i]['url']}'>{$goods[$i]['urltext']}</a>";
        // $goods_url = "<br><b>Ссылка:</b><a href='{$goods[$i]['url']}'>{$goods[$i]['url']}</a>";
      } elseif (!empty($goods[$i]['url'])) {
        $goods_url = "<br><b>Ссылка:</b><a href='{$goods[$i]['url']}'>{$goods[$i]['url']}</a>";
      }
      echo "<tr $class>
        <td>
          {$shops[$goods[$i]['shops_idshops']]}
        </td>
        <td>
          {$manufacturer[$goods[$i]['manufacturer_idmanufacturer']]}
        </td>
        <td>
          {$goods[$i]['name']}
        </td>
        <td>
          {$goods[$i]['quantity']}
        </td>
        <td>
          {$goods[$i]['price']}
        </td>
        <td>
          {$goods[$i]['guarantee']}";
          switch ($goods[$i]['symbols']) {
            case 'd':
              $s = 'дн.';
              break;
            case 'w':
              $s = 'н.';
              break;
            case 'm':
              $s = 'мес.';
              break;
            case 'y':
              $s = 'г.';
              break;
            default:
              $s = 'дн.';
              break;
          }
        echo " {$s}</td>
        <td>";
          $date = new DateTime($goods[$i]['date']);
        echo "{$date->format('d.m.Y')}</td>
        <td>
          {$goods[$i]['ordernumber']}
        </td>
        <td>";
          if ($goods[$i]['available']) {
            echo "В наличии";
          } else {
            echo "Отсутствует";
          }
        echo "</td>
        <td>";
          echo $arr_status[$goods[$i]['status']];
        echo "</td>
        <td>";
          $date = new DateTime($goods[$i]['daterec']);
        echo "{$date->format('d.m.Y')}</td>
        <td class='text-right'>
          <a href=# onClick=\"delete_position('scr_goodsdel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
          titlе='Удалить {$name}'>Удалить</а><br>
          <a href=scr_goodsedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
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
