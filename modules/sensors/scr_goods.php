<?php
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';

try {

  if ($_POST) {
    $arr_sort = array(1 => 'daterec', 2 => 'idgoods', 3 => 'date',
    4 => 'name', 5 => 'price', 6 => 'symbols', 7 => 'ordernumber',
    8 => 'shops_idshops', 9 => 'manufacturer_idmanufacturer', 10 => 'quantity');
    $arr_order = array(1 => 'DESC', 2 => 'ASC');
    $sql_sort = $arr_sort[intval($_POST['sort'])];
    $sql_order = $arr_order[intval($_POST['order'])];
    $iduser = intval($_POST['user']);
    $sql_search = "WHERE `users_idusers` = ".$iduser."";

    if ($_POST['search_val']) {
      $arr_search = array(1 => 'name', 2 => 'ordernumber', 3 => 'date',
      4 => 'date');
      if ($_POST['search'] == 4) {
        $search_date = new DateTime($_POST['search_val']);
        $sql_search = "WHERE `".($arr_search[$_POST['search']])."` LIKE '%".($search_date->format("Y-m-d"))."%' AND `users_idusers` = ".$iduser." ";
      } else {
        $sql_search = "WHERE `".($arr_search[$_POST['search']])."` LIKE '%".($_POST['search_val'])."%' AND `users_idusers` = ".$iduser." ";
      }
    }

    $pdo_grnt = $pdo_grnt->pdoGet();
    $datagoods = $pdo_grnt->prepare("SELECT * FROM $tbl_goods {$sql_search} ORDER BY {$sql_sort} {$sql_order}");
    $datagoods->execute();

    if ($datagoods->rowCount()) {
      while ($dat = $datagoods->fetch()){
        $goods[] = array('idgoods' => $dat['idgoods'], 'name' => $dat['name'], 'price' => $dat['price'],
        'quantity' => $dat['quantity'], 'guarantee' => $dat['guarantee'], 'date' => $dat['date'],
        'ordernumber' => $dat['ordernumber'], 'symbols' => $dat['symbols'],
        'available' => $dat['available'], 'datarec' => $dat['datarec'],
        'shops' => $dat['shops_idshops'], 'manufacturer' => $dat['manufacturer_idmanufacturer'],
        'status' => $dat['status']);
      };
    }
    $goodscounter = count($goods);
    if ($goodscounter >= 1) {
      ?>
      <div class='table-responsive'>
        <table class="table table-hover table-striped text-center">
          <caption>
            Товары<?php echo " ($goodscounter)"; ?>
          </caption>
          <tr>
            <th>
              №
            </th>
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
              Сумма
            </th>
            <th>
              Гарантия
            </th>
            <th>
              Дата
            </th>
            <th>
              Дата окончания
            </th>
            <th>
              Статус
            </th>
            <th>
              Номер
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
          $totalcol = 0;
          $totalsum = 0;
          $arr_status = array('In stock' => 'В наличии', 'Returned' => 'Возврат по гарантии');
          for ($i=0; $i < $goodscounter; $i++) {
            $class = "class=";
            $subclassdate = $class;
            $subclasssum = $class;
            $sum = 0;
            $today = new DateTime();
            $timein = new DateTime($goods[$i]['date']);
            $timeout = new DateTime($goods[$i]['date']);
            switch ($goods[$i]['symbols']) {
              case 'd':
                $s = 'дн.';
                $timeout->modify("+{$goods[$i]['guarantee']} day");
                break;
              case 'w':
                $s = 'н.';
                $timeout->modify("+{$goods[$i]['guarantee']} week");
                break;
              case 'm':
                $s = 'мес.';
                $timeout->modify("+{$goods[$i]['guarantee']} month");
                break;
              case 'y':
                $s = 'г.';
                $timeout->modify("+{$goods[$i]['guarantee']} year");
                break;
              default:
                $s = 'дн.';
                $timeout->modify("+{$goods[$i]['guarantee']} day");
                break;
            }
            if ($goods[$i]['available']) {
              $totalcol += $goods[$i]['quantity'];
              if ($timeout <= $today) {
                $class .= "badtext ";
                $subclassdate .= "bad";
              } else {
                $class = "";
                $subclassdate .= "good ";
              }
              if ($goods[$i]['quantity'] > 1) {
                $subclasssum .= "normal";
                $sum = $goods[$i]['price']*$goods[$i]['quantity'];
                $totalsum += $sum;
              } else {
                $subclasssum = "";
                $sum = "-";
                $totalsum += $goods[$i]['price'];
              }
            } else {
              $class .= "not-available ";
              $subclassdate = "";
              $subclasssum = "";
              $sum = "-";
            }
            echo "<tr $class>
              <td>
                ".($i+1)."
              </td>
              <td>
                {$shops[$goods[$i]['shops']]}
              </td>
              <td>
                {$manufacturer[$goods[$i]['manufacturer']]}
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
              <td $subclasssum>
                {$sum}
              </td>
              <td>
                {$goods[$i]['guarantee']} {$s}
              </td>
              <td>
                ".($timein->format("d.m.Y"))."
              </td>
              <td $subclassdate>
                ".($timeout->format("d.m.Y"))."
              </td>
              <td>
                {$arr_status[$goods[$i]['status']]}
              </td>
              <td>
                {$goods[$i]['ordernumber']}
              </td>
            </tr>";
          }
          echo "<tr>
            <td colspan='4'>
              Итого:
            </td>
            <td>
              {$totalcol}
            </td>
            <td></td>
            <td>
              {$totalsum}
            </td>
            <td colspan='5'></td>
          </tr>";
          ?>
        </table>
      </div>
      <?php
    } else {
      echo "Нет данных.";
    }
  }

} catch (Exception $e) {

}
?>
