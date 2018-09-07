<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';
require_once("../utils/head.php");

try {
  $today = date("Y");

  $query = "SELECT `idwaybill`, `number`, `date` FROM $tbl_waybill ORDER BY `date` DESC";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице накладных.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $waybills[$dat[idwaybill]] = $dat[number]." ".$dat[date];
    };
  }

  $waybill = new FieldSelect("waybill", "Накладная", $_REQUEST['waybill'], $waybills);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("waybill" => $waybill, "page" => $page), "Отобразить");

  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
  if (!empty($error)) {
    foreach ($error as $err) {
      echo "<span style=\"color:red\">$err</span><br>";
    }
  }
  $form->print_form();

  if ((!empty($_POST['waybill']) && isset($_POST['waybill'])) || (!empty($_POST['good']) && isset($_POST['good']))) {
    if (isset($_REQUEST['waybill'])) {
      $_REQUEST['waybiilrequest'] = $_REQUEST['waybill'];
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

    $query = "SELECT `idgoods`, `good`, `price`, `quantity` FROM $tbl_goods WHERE `waybill_idwaybill` =
    {$_REQUEST['waybiilrequest']} ORDER BY `idgoods` DESC";
    // echo "$query<br>";
    $data = $pdo_lib->query($query);
    if (!$data) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице авторов.");
    }
    if ($data->rowCount()) {
      while ($dat = $data->fetch()){
        $goods[$dat[idgoods]] = $dat[good]." ".$dat[price]." ".$dat[quantity];
      };
    }

    if (sizeof($goods)) {
      if (empty($_REQUEST['quantity'])) {
        $_REQUEST['quantity'] = 1;
      }
      if (empty($_REQUEST['begin'])) {
        $_REQUEST['begin'] = 1;
      }
      if (empty($_REQUEST['separator'])) {
        $_REQUEST['separator'] = "/";
      }
      if (empty($_REQUEST['inventorynumber'])) {
        $query = "SELECT `inventorynumber` FROM $tbl_inventorybooks ORDER BY `idinventorybooks` DESC LIMIT 1";
        $data = $pdo_lib->query($query);
        $_REQUEST['inventorynumber'] = $data->fetchColumn();
      }
      $types = array(1 => 'Учебник', 2 => 'Книга', 3 => 'Энциклопедия');

      $inventorynumber = new FieldText("inventorynumber", "Инвентарный номер", $_REQUEST['inventorynumber'], true);
      $title = new FieldText("title", "Название", $_REQUEST['title'], true);
      $yearofpublishing = new FieldInt("yearofpublishing", "Год издания", $_REQUEST['yearofpublishing'], true);
      $notation = new FieldTextarea("notation", "Примечание", $_REQUEST['notation'], false);
      $class = new FieldInt("class", "Класс", $_REQUEST['class'], false, 1, 12);
      $type = new FieldSelect("type", "Тип", $_REQUEST['type'], $types);
      $quantity = new FieldInt("quantity", "Количество книг", $_REQUEST['quantity'], true, 1, 200);
      $separator = new FieldText("separator", "Разделитель", $_REQUEST['separator'], true);
      $begin = new FieldInt("begin", "Начать с", $_REQUEST['begin'], true, 1, 10000);
      $publishinghouse = new FieldSelect("publishinghouse", "Издательство", $_REQUEST['publishinghouse'], $publishinghouses);
      $authors = new FieldSelect("authors", "Автор(ы)", $_REQUEST['authors'], $author);
      $good = new FieldSelect("good", "Товар", $_REQUEST['good'], $goods);
      $waybiilrequest = new FieldHiddenInt("waybiilrequest",  $_REQUEST['waybiilrequest'], false);
      $inform = new Form(array("inventorynumber" => $inventorynumber, "title" => $title,
       "yearofpublishing" => $yearofpublishing, "notation" => $notation, "class" => $class,
       "publishinghouse" => $publishinghouse, "authors" => $authors, "good" => $good, "type" => $type,
       "waybiilrequest" => $waybiilrequest, "quantity" => $quantity,
       "separator" => $separator, "begin" => $begin), "Добавить");

      if (!empty($_POST['good']) && isset($_POST['inventorynumber'])) {
        $error = $inform->check();
        if (empty($error)) {
          $first = $inform->fields[begin]->get_value();
          // echo "quant:{$inform->fields[quantity]->get_value()} {$first}<br>";
          $query = "";
          for ($i=0; $i < ($inform->fields[quantity]->get_value()); $i++) {
            $number = $inform->fields[inventorynumber]->get_value().$inform->fields[separator]->get_value().$first;
            ++$first;
            $query .= "INSERT INTO $tbl_inventorybooks VALUES (NULL, '{$number}',
              '{$inform->fields[title]->get_value()}', '{$inform->fields[yearofpublishing]->get_value()}',
              '{$inform->fields[notation]->get_value()}', '{$inform->fields['class']->get_value()}', '{$types[$inform->fields[type]->get_value()]}',
              '{$inform->fields[publishinghouse]->get_value()}', '{$inform->fields[authors]->get_value()}',
              '{$inform->fields[good]->get_value()}', '$_REQUEST[waybiilrequest]') ; ";
            // if (!$pdo_lib->query($query)) {
            //   throw new ExceptionMySQL("", $query,"Ошибка добавления");
            // }
          }
          // echo "$query<br>";
          if (!$pdo_lib->query($query)) {
            throw new ExceptionMySQL("", $query,"Ошибка добавления");
          }
          exit("<meta http-equiv='refresh' content='0; url= indexinventorybooks.php?page={$form->fields[page]->get_value()}'>");
        }

      }

      $query = "SELECT * FROM $tbl_goods WHERE waybill_idwaybill = {$_REQUEST[waybiilrequest]} ORDER BY `idgoods` DESC";
      $goodsdata = $pdo_lib->query($query);
      if (!$goodsdata) {
        throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице товаров.");
      }
      if ($goodsdata->rowCount()) {
        echo "<div class='table-responsive'>
            <table class='table table-striped table-hover table-condensed text-center'>
            <caption>
              Товры по накладной
            </caption>
            <tr>
              <th>
                №
              </th>
              <th>
                Товар
              </th>
              <th>
                Цена
              </th>
              <th>
                Количество
              </th>
              <th>
                ISBN
              </th>
            </tr>";
          $k = 0;
          while ($dat = $goodsdata->fetch()){
            echo "<tr>
              <td>
                ".++$k."
              </td>
              <td>
                {$dat[good]}
              </td>
              <td>
                {$dat[price]}
              </td>
              <td>
                {$dat[quantity]}
              </td>
              <td>
                {$dat[isbn]}
              </td>
            </tr>";
          };
          echo "</table></div>";
      }
      if (!empty($error)) {
        foreach ($error as $err) {
          echo "<span style=\"color:red\">$err</span><br>";
        }
      }
      $inform->print_form();
    }else {
      echo "<br> Данная накладная пуста. <br>";
    }

  } else {
    echo "Выбирите накладную.";
  }

} catch (ExceptionObject $e) {
  require("../utils/exception_object.php");
} catch (ExceptionMySQL $e) {
  require("../utils/exception_mysql.php");
} catch (ExceptionMember $e) {
  require("../utils/exception_member.php");
}
require_once '../utils/bottom.php';
?>
