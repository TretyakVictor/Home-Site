<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/head.php';

try {
  $page_link = 3;
  $page_number = 5;

  $obj = new PagerMySQL($pdo_lib, $tbl_inventorybooks, "", "ORDER BY idinventorybooks DESC", $page_number, $page_link);
  $name = "книгу";
  echo "<a href=inventorybooksadd.php?page=$_GET[page] titlе='Добавить {$name}'>Добавить {$name}</a>&nbsp;&nbsp;
  <a href=multiinventorybooksadd.php titlе='Добавить комплект'>Добавить комплект</a><br><br>";
  $inventorybooks = $obj->get_page();
  if (!empty($inventorybooks)) {
    ?>
    <div class="table-responsive">
    <table class="table table-hover table-striped text-center">
      <caption>
        Книги
      </caption>
      <tr>
        <th>
          Инвентарный номер
        </th>
        <th>
          Название
        </th>
        <th>
          Год издания
        </th>
        <th>
          Класс
        </th>
        <th>
          Тип
        </th>
        <th>
          Примечание
        </th>
        <th>
          Издательство
        </th>
        <th>
          Автор(ы)
        </th>
        <th>
          Накладная
        </th>
        <th>
          Товар по накладной
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    $query = "SELECT `idwaybill`, `number` FROM $tbl_waybill";
    $data = $pdo_lib->query($query);
    if (!$data) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице накладных.");
    }
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $waybills[$dat[idwaybill]] = $dat[number];
      };
    }
    $query = "SELECT `idpublishinghouse`, `name` FROM $tbl_publishinghouse ORDER BY `idpublishinghouse` DESC";
    $data = $pdo_lib->query($query);
    if (!$data) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице издательств.");
    }
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $publishinghouses[$dat[idpublishinghouse]] = $dat[name];
      };
    }
    $query = "SELECT `idauthors`, `name` FROM $tbl_authors ORDER BY `idauthors` DESC";
    $data = $pdo_lib->query($query);
    if (!$data) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице авторов.");
    }
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $author[$dat[idauthors]] = $dat[name];
      };
    }
    $query = "SELECT `idgoods`, `good` FROM $tbl_goods ORDER BY `idgoods` DESC";
    $data = $pdo_lib->query($query);
    if (!$data) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице авторов.");
    }
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $goods[$dat[idgoods]] = $dat[good];
      };
    }

    for ($i=0; $i < count($inventorybooks); $i++) {
      $colorrow = "";
      if (!$page) {
        if (!empty($_GET['page'])) {
          $page = $_GET['page'];
        }else {
          $page = 1;
        }
      }
      $url = "?id_inventorybooks={$inventorybooks[$i][idinventorybooks]}&page=$page";
      $inventorybooks_url="";
      if (!empty($inventorybooks[$i]['url'])) {
        $inventorybooks[$i]['url'] = "http://{$inventorybooks[$i][url]}";
      }
      $inventorybooks_url = "<br><b>Ссылка:</b><a href='{$inventorybooks[$i][url]}'>{$inventorybooks[$i][urltext]}</a>";
      if (empty($inventorybooks[$i]['urltext'])) {
        $inventorybooks_url = "<br><b>Ссылка:</b><a href='{$inventorybooks[$i][url]}'>{$inventorybooks[$i][url]}</a>";
      }
      echo "<tr $colorrow>
        <td>
          {$inventorybooks[$i][inventorynumber]}
        </td>
        <td>
          {$inventorybooks[$i][title]}
        </td>
        <td>
          {$inventorybooks[$i][yearofpublishing]}
        </td>
        <td>";
        if ($inventorybooks[$i]['class'] == 0) {
          echo "-";
        }else {
          echo $inventorybooks[$i]['class'];
        }
        echo "</td>
        <td>
          {$inventorybooks[$i][type]}
        </td>
        <td>
          {$inventorybooks[$i][notation]}
        </td>
        <td>
          {$publishinghouses[$inventorybooks[$i][publishinghouse_idpublishinghouse]]}
        </td>
        <td>
          {$author[$inventorybooks[$i][authors_idauthors]]}
        </td>
        <td>
          {$waybills[$inventorybooks[$i][goods_waybill_idwaybill]]}
        </td>
        <td>
          {$goods[$inventorybooks[$i][goods_idgoods]]}
        </td>
        <td align='right'>
          <a href=# onClick=\"delete_position('scr_inventorybooksdel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
          titlе='Удалить {$name}'>Удалить</а><br>
          <a href=scr_inventorybooksedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
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
