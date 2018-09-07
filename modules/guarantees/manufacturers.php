<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.php';

try {
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
  if (USER_LOGGED) {
    $pdo_grnt = $pdo_grnt->pdoGet();
    $datasmanufacturers = $pdo_grnt->prepare("SELECT `T1`.`name`, (SELECT COUNT(`idgoods`) FROM $tbl_goods `T2`
    WHERE `T1`.`idmanufacturer` = `T2`.`manufacturer_idmanufacturer` AND `T2`.`users_idusers` = $UserID) AS `quantity` FROM $tbl_manufacturer `T1`");
    $datasmanufacturers->execute();

    if ($datasmanufacturers->rowCount()) {
      $data = $datasmanufacturers->fetchAll();
      $manufacturercounter = count($data);
    }
    if ($manufacturercounter >= 1) {
      ?>
      <div class='table-responsive'>
        <table class="table table-hover table-striped text-center">
          <caption>
            Производители<?php echo " ($manufacturercounter)"; ?>
          </caption>
          <tr>
            <th>
              Производитель
            </th>
            <th>
              Количество товаров
            </th>
          </tr>
          <?php
          $totalcol = 0;
          $class = "class='good'";
          for ($i=0; $i < $manufacturercounter; $i++) {
            $totalcol += $data[$i]['quantity'];
            echo "<tr>
              <td>
                {$data[$i]['name']}
              </td>
              <td>
                {$data[$i]['quantity']}
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
