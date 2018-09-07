<?php
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';

try {

  $datein = intval($_POST[datein]);
  $dateout = intval($_POST[dateout]);

  $query = "SELECT title, class, yearofpublishing, COUNT(yearofpublishing) AS `counter`, authors_idauthors, publishinghouse_idpublishinghouse
  FROM $tbl_inventorybooks WHERE type='Учебник' AND yearofpublishing BETWEEN $datein AND $dateout GROUP BY class, title, yearofpublishing";
  $pdo_lib = $pdo_lib->pdoGet();
  $databooks = $pdo_lib->query($query);
  if (!$databooks) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице книг или читателей.");
  }
  if ($databooks->rowCount()) {
    while ($dat = $databooks->fetch()){
      $books[] = array('title' => $dat[title], 'yearofpublishing' => $dat[yearofpublishing],
      'publishinghouse' => $dat[publishinghouse_idpublishinghouse], 'authors' => $dat[authors_idauthors],
      'class' => $dat['class'], 'type' => $dat['type'] , 'counter' => $dat['counter']);
    };
  }
  if (count($books) >= 1) {
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
    ?>
    <div class='table-responsive'>
      <table class="table table-hover table-striped text-center">
        <caption>
          Список учебников
        </caption>
        <tr>
          <th>
            Автор и название
          </th>
          <th>
            Издательство
          </th>
          <th>
            Класс
          </th>
            <?php
            $year = DateTime::createFromFormat('Y', $datein); $yearout = DateTime::createFromFormat('Y', $dateout);
            while ($year <= $yearout) {
              echo "<th>".$year->format('Y')."</th>";
              $years[] = $year->format('Y');
              $year->modify("+1 year");
            }
            ?>
          <th>
            Общее кол-во
          </th>
        </tr>

        <?php
        $k = 0; $mainsum = 0;
        for ($i=0; $i < count($books); $i++) {
          if ($k > 1) {
            --$k;
            continue;
          } elseif ($k > 0) {
            $k = 0;
          }
          $sum = 0;
          echo "
          <tr $class>
            <td>
              {$author[$books[$i][authors]]} {$books[$i][title]}
            </td>
            <td>
              {$publishinghouses[$books[$i][publishinghouse]]}
            </td>
            <td>
              {$books[$i]['class']}
            </td>";
          if (($author[$books[$i][authors]] == $author[$books[$i+1][authors]]) && ($books[$i+1][title] == $books[$i][title]) && ($books[$i]['class'] == $books[$i+1]['class'])) {
            for ($j=0; $j < count($years); $j++) {
              if ($books[$i+$k][yearofpublishing] == $years[$j]) {
                $sum += $books[$i+$k][counter];
                echo "<td>".$books[$i+$k][counter]."</td>";
                ++$k;
                $karr[] = "(".$k." - ".$books[$i][title].")";
              } else {
                echo "<td>"."-"."</td>";
              }
            }
          } else {
            for ($j=0; $j < count($years); $j++) {
              if ($books[$i][yearofpublishing] == $years[$j]) {
                $sum += $books[$i][counter];
                echo "<td>".$books[$i][counter]."</td>";
              } else {
                echo "<td>"."-"."</td>";
              }
            }
          }
          $mainsum += $sum;
          echo "
          <td>
            {$sum}
          </td>
        </tr>
        ";
        }
        echo "<tr>
          <td colspan=".(3+count($years)).">
            Итого:
          </td>
          <td>
            {$mainsum}
          </td>
        </tr>";
        ?>
      </table>
    </div>
    <?php
  } else {
    echo "Нет данных.";
  }
} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}
?>
