<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.php';

try {
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";

  if (USER_LOGGED) {
    $pdo_grnt = $pdo_grnt->pdoGet();
    $datashops = $pdo_grnt->prepare("SELECT `T1`.`name`, `T1`.`address`, (SELECT COUNT(`idgoods`) FROM $tbl_goods `T2` WHERE `T1`.`idshops` = `T2`.`shops_idshops` AND `T2`.`users_idusers` = $UserID) AS `quantity`,
    (SELECT SUM(quantity*price) FROM $tbl_goods `T2` WHERE `T1`.`idshops` = `T2`.`shops_idshops` AND `T2`.`users_idusers` = $UserID) AS `sum` FROM $tbl_shops `T1`");
    $datashops->execute();

    if ($datashops->rowCount()) {
      $data = $datashops->fetchAll();
      $shopscounter = count($data);
    }
    if ($shopscounter >= 1) {
      ?>
      <div class='table-responsive'>
        <table class="table table-hover table-striped text-center">
          <caption>
            Магазины<?php echo " ($shopscounter)"; ?>
          </caption>
          <tr>
            <th>
              Магазин
            </th>
            <th>
              Количество товаров
            </th>
            <th>
              Общая сумма
            </th>
          </tr>
          <?php
          $totalcol = 0;
          $totalsum = 0;
          $class = "class='good'";
          for ($i=0; $i < $shopscounter; $i++) {
            $totalcol += $data[$i]['quantity'];
            $totalsum += $data[$i]['sum'];
            echo "<tr>
              <td>";
                if (empty($data[$i]['address'])) {
                  echo $data[$i]['name'];
                } else {
                  echo $data[$i]['name']." (".$data[$i]['address'].")";
                }
              echo "</td>
              <td>
                {$data[$i]['quantity']}
              </td>
              <td>
                ".round($data[$i]['sum'])."
              </td>
            </tr>";
          }
          echo "<tr>
            <td>
              Итого:
            </td>
            <td $class>
              {$totalcol}
            </td>
            <td $class>
              ".round($totalsum)."
            </td>
          </tr>";
          ?>
        </table>
      </div>
      <?php
    } else {
      echo "Нет данных.";
    }
  } else {
    echo "Необходимо войти, что бы просматривать эту страницу.";
  }

} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

require_once '../templates/bottom.php';
?>
