<?php
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';

function tranlateDayDate($fdate) {
  $day = strftime("%a", strtotime($fdate));
  switch ($day) {
    case 'Sun':
      return "Воскресенье";
      break;
    case 'Mon':
      return "Понедельник";
      break;
    case 'Tue':
      return "Вторник";
      break;
    case 'Wed':
      return "Среда";
      break;
    case 'Thu':
      return "Четверг";
      break;
    case 'Fri':
      return "Пятница";
      break;
    case 'Sat':
      return "Суббота";
      break;
    default:
      return "";
      break;
  }
}

try {
  if (!empty($_POST['id_catalog'])) {
    $_POST['id_catalog'] = intval($_POST['id_catalog']);
  }


  if ($_POST[selectrdr] == 1) {
    $today = date('Y-m-d H:i:s');
    $firstday = date('Y-m-1 00:00:00');
  } elseif ($_POST[selectrdr] == 2) {
    $today = date('Y-m-d H:i:s');
    $firstday = date('Y-01-01 00:00:00');
  } elseif ($_POST[selectrdr] == 3) {
    $today =date('Y-m-d 00:00:00',  mktime(0, 0, 0, date('n'), 1, date('Y')));
    $firstday = date('Y-m-1 00:00:00', mktime(0, 0, 0, date('m') - 1, 1));
  } elseif ($_POST[selectrdr] == 4) {
    $today = date('Y-01-01 00:00:00');
    $day = new DateTime($today);
    $day->modify("-1 sec");
    $today = $day->format("Y-m-d H:i:s");
    $firstday = date('Y-01-01 00:00:00', mktime(0, 0, 0, 1, 1, date('Y') - 1));
  }
  if ($firstday && $today) {
    $query = "SELECT DATE_FORMAT(`T1`.dateandtime, '%d.%m.%Y') AS group_date, COUNT(`T1`.dateandtime) AS col FROM $tbl_movement `T1`
    WHERE `T1`.dateandtime BETWEEN  '{$firstday}' AND '{$today}' GROUP BY group_date";
    $pdo_lib = $pdo_lib->pdoGet();
    $dataactivity = $pdo_lib->query($query);
    if (!$dataactivity) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице книг или читателей.");
    }

    if ($dataactivity->rowCount()) {
      while ($dat = $dataactivity->fetch()){
        $activ[] = array('col' => $dat[col], 'group_date' => $dat[group_date]);
      };
      if ($_POST[selectrdr] == 1 || $_POST[selectrdr] == 3) {
        if ($_POST[selectrdr] == 3) {
          $now = mktime(0, 0, 0, date('n'), 1, date('Y'));
          $firstday = mktime(0, 0, 0, date('m') - 1, 1);
          $days = floor(($now - $firstday) / 86400)-1;
          $firstdata = date('.m.Y', mktime(0, 0, 0, date('m') - 1, 1));
        } else {
          $firstday = mktime(0, 0, 0, date('n'), 1, date('Y'));
          $now = time();
          $days = floor(($now - $firstday) / 86400);
          $firstdata = date('.m.Y');
        }
        for ($i=0; $i <= $days; $i++) {
          $col = "-";
          for ($j=0; $j < count($activ) ; $j++) {
            if (strtotime($activ[$j][group_date]) == strtotime(($i+1).$firstdata)) {
              $col = $activ[$j][col];
            }
          }
          $activity[$i] = array('day' => ($i+1), 'col' => $col, 'group_date' => ($i+1).$firstdata);
        }
      } elseif ($_POST[selectrdr] == 2 || $_POST[selectrdr] == 4) {
        $day = new DateTime($firstday);
        $days = floor((strtotime($today )- strtotime($firstday)) / 86400)+1;
        $firstday = $day->format("d.m.Y");
        for ($i=0; $i < $days; $i++) {
          $col = "-";
          for ($j=0; $j < count($activ) ; $j++) {
            if (strtotime($activ[$j][group_date]) == strtotime($firstday)) {
              $col = $activ[$j][col];
            }
          }
          $activity[$i] = array('day' => $i, 'col' => $col, 'group_date' => $firstday);
          $day->modify("+1 day");
          $firstday = $day->format("d.m.Y");
        }
      }

    }
  }


  if (count($activity) >= 1) {
    ?>
    <div class='table-responsive'>
      <table class="table table-hover table-striped text-center">
        <caption>
          Журнал выдачи
        </caption>
        <tr>
          <th>
            №
          </th>
          <th>
            День
          </th>
          <th>
            Дата
          </th>
          <th>
            Количество
          </th>
        </tr>
        <?php
        for ($i=0; $i < count($activity); $i++) {
          $fdate = tranlateDayDate($activity[$i][group_date]);
            if (!empty($_POST[selectrdr])) {
              if ($activity[$i][col] != "-") {
                $class = "class='success'";
              } elseif ($fdate == "Воскресенье") {
                $class = "class='danger'";
              } else {
                $class = "";
              }
                echo "
                <tr $class>
                  <td>
                    ".($i+1)."
                  </td>
                  <td>
                    ".$fdate."
                  </td>
                  <td>
                    {$activity[$i][group_date]}
                  </td>
                  <td>
                    {$activity[$i][col]}
                  </td>
                </tr>
                ";
            }
        }
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
