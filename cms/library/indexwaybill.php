<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';
// require_once '../utils/print_page.php';

try {
  $page_link = 3;
  $page_number = 5;

  $obj = new PagerMySQL($pdo_lib, $tbl_waybill, "", "ORDER BY date DESC", $page_number, $page_link);
  $name = "накладную";
  echo "<a href=waybilladd.php?page=$_GET[page] titlе='Добавить {$name}'>Добавить {$name}</a>&nbsp;&nbsp;<a href=goodsadd.php titlе='Добавить товар'>Добавить товар</a><br><br>";
  $waybill = $obj->get_page();
  if (!empty($waybill)) {
    ?>
    <div class="table-responsive">
    <table class="table table-hover table-striped text-center">
      <caption>
        Накладные
      </caption>
      <tr>
        <th>
          Номер
        </th>
        <th>
          Основание
        </th>
        <th>
          Дата
        </th>
        <th>
          Поставщик
        </th>
        <th>
          Плательщик
        </th>
        <th>
          Грузоотправитель
        </th>
        <th>
          Структурное подразделение
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    for ($i=0; $i < count($waybill); $i++) {
      $query = "SELECT * FROM $tbl_goods WHERE waybill_idwaybill = {$waybill[$i][idwaybill]} ORDER BY `idgoods` DESC";
      $data = $pdo_lib->query($query);
      if (!$data) {
        throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице товаров.");
      }

      // if ($data->rowCount()) {
      //   while ($dat = $data->fetch()){
      //     $goods[$dat[idgoods]] = $dat[good]." ".$dat[price];
      //   };
      // }
      $class = "class='success'";
      if (!$page) {
        if (!empty($_GET['page'])) {
          $page = $_GET['page'];
        }else {
          $page = 1;
        }
      }
      $url = "?id_waybill={$waybill[$i][idwaybill]}&page=$page";
      // if ($waybill[$i]['hide'] == 'show') {
      //   $showhide = "<a href=waybillhide.php$url title='Скрыть новость'>Скрыть</a>";
      // }else {
      //   $showhide = "<a href=waybillshow.php$url title='Отобразить новость'>Отобразить</a>";
      //   $class = "class='hiddenrow'";
      // }
      // if ($waybill[$i]['urlpict'] != '' && $waybill[$i]['urlpict'] != '-' && is_file("../../".$waybill[$i]['urlpict'])) {
      //   $url_pict = "<b><a href=../../{$waybill[$i][urlpict]}>есть</a></b>";
      // }else {
      //   $url_pict = "нет";
      // }
      $waybill_url="";
      if (!empty($waybill[$i]['url'])) {
        $waybill[$i]['url'] = "http://{$waybill[$i][url]}";
      }
      $waybill_url = "<br><b>Ссылка:</b><a href='{$waybill[$i][url]}'>{$waybill[$i][urltext]}</a>";
      if (empty($waybill[$i]['urltext'])) {
        $waybill_url = "<br><b>Ссылка:</b><a href='{$waybill[$i][url]}'>{$waybill[$i][url]}</a>";
      }
      list($date) = explode(" ", $waybill[$i]['date']);
      list($year, $month, $day) = explode("-", $date);
      $waybill[$i]['date'] = "$day.$month.$year";
      echo "<tr $class>
        <td>
          {$waybill[$i][number]}
        </td>
        <td>
          {$waybill[$i][base]}
        </td>
        <td>
          {$waybill[$i][date]}
        </td>
        <td>
          {$waybill[$i][provider]}
        </td>
        <td>
          {$waybill[$i][payer]}
        </td>
        <td>
          {$waybill[$i][consignor]}
        </td>
        <td>
          {$waybill[$i][subdivision]}
        </td>
        <td align='right'>
          <a href=# onClick=\"delete_position('scr_waybilldel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
          titlе='Удалить {$name}'>Удалить</а><br>
          <a href=scr_waybilledit.php$url titlе='Редактировать {$name}'>Редактировать</а>
        </td>
      </tr>";
      if ($data->rowCount()) {
        echo "<tr>
          <td colspan='8'>
            <table class='table table-striped table-condensed text-center'>
            <caption>
              Накладная № {$waybill[$i][number]}
            </caption>
            <tr>
              <th>
                №
              </th>
              <th>
                Товар
              </th>
              <th>
                ISBN
              </th>
              <th>
                НДС
              </th>
              <th>
                Цена
              </th>
              <th>
                Цена с НДС
              </th>
              <th>
                Количество
              </th>
              <th>
                Сумма без НДС
              </th>
              <th>
                Сумма с НДС
              </th>
            </tr>";
          $k = 0; $sumcol = 0; $sum = 0; $sumwithnds = 0;
          while ($dat = $data->fetch()){
            $sumcol += $dat[quantity];
            $withoutnds = $dat[quantity]*$dat[price];
            $withnds = (100+$dat[tax])/100*$dat[price]*$dat[quantity];
            $sum += $withoutnds;
            $sumwithnds += $withnds;
            echo "<tr>
              <td>
                ".++$k."
              </td>
              <td>
                {$dat[good]}
              </td>
              <td>
                {$dat[isbn]}
              </td>
              <td>";
                if ($dat[tax] == 0) {
                  echo "Без НДС";
                } else {
                  echo $dat[tax];
                }
              echo"</td>
              <td>
                {$dat[price]}
              </td>
              <td>
                ".((100+$dat[tax])/100*$dat[price])."
              </td>
              <td>
                {$dat[quantity]}
              </td>
              <td>
                {$withoutnds}
              </td>
              <td>
                {$withnds}
              </td>
            </tr>";
          };
          echo "<tr>
            <td colspan='6'>
              Итого по накладной:
            </td>
            <td>
              {$sumcol}
            </td>
            <td>
              {$sum}
            </td>
            <td>
              {$sumwithnds}
            </td>
          </tr>";
          echo "</table>
        </td>
      </tr>";
      }
    }
    echo "</table></div><br>";
  }

  echo "<div class='text-center'>".$obj."</div>";
} catch (ExceptionMySQL $e) {
  // require("../utils/exception_mysql.php");
}
require_once '../utils/bottom.php';
?>
