<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.modules.php';
require_once '../../configs/mysql.config.php';

$pdo_lib = $pdo_lib->pdoGet();
function rdate($param, $time=0) {
  if (intval($time) == 0) {
    $time=time();
  }
	$MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
  if (strpos($param,'M') === false) {
    return date($param, $time);
  }else {
    return date(str_replace('M', $MonthNames[date('n', $time)-1], $param), $time);
  }
}
if (!empty($_GET['id_catalog'])) {
  $_REQUEST['id_catalog'] = intval($_GET['id_catalog']);
}
if (!empty($_GET['readertarget'])) {
  $_REQUEST['readertarget'] = intval($_GET['readertarget']);
}
try {
  if (!empty($_REQUEST['booktarget']) && !empty($_REQUEST['readertarget'])) {
    $_REQUEST['booktarget'] = intval($_REQUEST['booktarget']);

    $query = "SELECT * FROM $tbl_inventorybooks WHERE idinventorybooks = {$_REQUEST[booktarget]} ";
    $databooksins = $pdo_lib->query($query);
    if (!$databooksins) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице книг.");
    }
    $databooksins = $databooksins->fetchAll();
    $databooksins = $databooksins[0];

    $query = "INSERT INTO $tbl_movement VALUES (NULL, '".date('Y-m-d H:i:s')."',
      NULL, 1, '{$_REQUEST[readertarget]}', '{$databooksins[idinventorybooks]}',
      '{$databooksins[publishinghouse_idpublishinghouse]}', '{$databooksins[authors_idauthors]}',
      '{$databooksins[goods_idgoods]}', '{$databooksins[goods_waybill_idwaybill]}')";

    if (!$pdo_lib->query($query)) {
      throw new ExceptionMySQL("", $query, "Ошибка выдачи");
    }
    exit("<meta http-equiv='refresh' content='0; url=
    $_SERVER[PHP_SELF]?readertarget={$_REQUEST[readertarget]}&id_catalog={$_REQUEST[id_catalog]}'>");
  }


  if (!empty($_REQUEST['booksreturn']) && !empty($_REQUEST['readertarget'])) {
    $_REQUEST['booksreturn'] = intval($_REQUEST['booksreturn']);

    $query = "UPDATE $tbl_movement SET switch = false, dateandtimereturn = '".date('Y-m-d H:i:s')."'
     WHERE idmovement={$_REQUEST[booksreturn]}";
    if (!$pdo_lib->query($query)) {
      throw new ExceptionMySQL("", $query,"Ошибка возврата");
    }

    exit("<meta http-equiv='refresh' content='0; url=
    $_SERVER[PHP_SELF]?readertarget={$_REQUEST[readertarget]}&id_catalog={$_REQUEST[id_catalog]}'>");
  }

  if (!empty($_REQUEST['readertarget'])) {
    $_GET[readertarget] = intval($_GET[readertarget]);
    $query = "SELECT * FROM $tbl_reader WHERE idreader = $_GET[readertarget] ";
    $datareader = $pdo_lib->query($query);
    if (!$datareader) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице читателей.");
    }
    $datareader = $datareader->fetchAll();
    $datareader = $datareader[0];

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

    $query = "SELECT * FROM $tbl_inventorybooks `T1`, (SELECT `T2`.inventorybooks_idinventorybooks,
      `T2`.dateandtime, `T2`.dateandtimereturn,  `T2`.switch, `T2`.idmovement
      FROM $tbl_movement `T2` LEFT JOIN $tbl_reader `T3` ON `T2`.reader_idreader = `T3`.idreader
      WHERE `T3`.idreader = $_GET[readertarget]) `T4` WHERE `T1`.idinventorybooks = `T4`.inventorybooks_idinventorybooks
       ORDER BY `T4`.dateandtime DESC";
    $databooks = $pdo_lib->query($query);
    if (!$databooks) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице книг или читателей.");
    }
    if ($databooks->rowCount()) {
      while ($dat = $databooks->fetch()){
        $books[] = array('idinventorybooks' => $dat[idinventorybooks],
        'inventorynumber' => $dat[inventorynumber], 'title' => $dat[title],
        'yearofpublishing' => $dat[yearofpublishing], 'notation' => $dat[notation],
        'class' => $dat['class'], 'type' => $dat[type],
        'publishinghouse' => $publishinghouses[$dat[publishinghouse_idpublishinghouse]],
        'authors' => $author[$dat[authors_idauthors]], 'switch' => $dat['switch'],
        'date' => $dat[dateandtime], 'datereturn' => $dat[dateandtimereturn],
        'idmovement' => $dat[idmovement]);
      };
    }
    $dateofbirth = rdate("d M Y", strtotime($datareader[dateofbirth]));
    $receiptdate = date("d.m.Y", strtotime($datareader[receiptdate]));
    ?>
    <div class='table-responsive'>
      <table class="table table-hover table-striped text-left">
        <caption>
          Формуляр читателя
        </caption>
        <?php
        echo "
        <tr>
          <td>
            Фамилия
          </td>
          <td>
            $datareader[surname]
          </td>
        </tr>
        <tr>
          <td>
            Имя
          </td>
          <td>
            $datareader[name]
          </td>
        </tr>
        <tr>
          <td>
            Отчество
          </td>
          <td>
            $datareader[patronymic]
          </td>
        </tr>
        <tr>
          <td>
            Класс
          </td>
          <td>";
          if (!empty($datareader[receiptclass])) {
            $classnow = (date("Y")-date("Y", strtotime($receiptdate))+$datareader[receiptclass]);
            if ($classnow > 12) {
              echo "-";
            } else {
              echo $classnow;
            }
          } else {
            echo "-";
          }
          echo "</td>
        </tr>
        <tr>
          <td>
            Дата рождения
          </td>
          <td>
            $dateofbirth года
          </td>
        </tr>
        <tr>
          <td>
            Домашний адрес
          </td>
          <td>
            $datareader[address]
          </td>
        </tr>
        <tr>
          <td>
            Телефон(ы)
          </td>
          <td>
            $datareader[phonenumber] | $datareader[homephonenumber]
          </td>
        </tr>
        <tr>
          <td>
            Состоит читателем с
          </td>
          <td>
            $receiptdate ($datareader[receiptclass] кл.)
          </td>
        </tr>
        <tr>
          <td>
            Паспорт
          </td>
          <td>
            $datareader[passport]
          </td>
        </tr>
        ";
        ?>
      </table>
    </div>
    <?php if (count($books) >= 1) {
     ?>
    <div class='table-responsive'>
      <table class="table table-hover table-striped text-center">
        <caption>
          Список книг
        </caption>
        <tr>
          <th>
            Название
          </th>
          <th>
            Тип
          </th>
          <th>
            Класс
          </th>
          <th>
            Автор(ы)
          </th>
          <th>
            Издательство
          </th>
          <th>
            Год издания
          </th>
          <th>
            Примечание
          </th>
          <th>
            Инвентарный номер
          </th>
          <th>
            Дата выдачи
          </th>
          <th>
            Дата возврата
          </th>
        </tr>
        <?php
        for ($i=0; $i < count($books); $i++) {
          if ($books[$i]['switch']) {
            $booksreturnarr[$books[$i][idmovement]] = $books[$i][title]." ".$books[$i][type]." ".$books[$i][authors]." ".$books[$i][publishinghouse]." ".$books[$i][yearofpublishing]." ".$books[$i][inventorynumber];
            $dateout = strtotime($books[$i][date]);
            if ($books[$i][type] == "Учебник") {
              $startdate = date('d-06-Y');
              $datereturn = date("d.m.Y", strtotime(date("Y-m-d", strtotime($startdate)) . " + 1 year"));
            } else {
              $datereturn = $dateout+3600*24*15;
              $datereturn = date("d.m.Y", $datereturn);
            }
            if (strtotime($datereturn) < strtotime("now")) {
              $class = "class='danger'";
            }else {
              $class = "class='success'";
            }
            echo "
            <tr $class>
              <td>
                {$books[$i][title]}
              </td>
              <td>
                {$books[$i][type]}
              </td>
              <td>
                {$books[$i]['class']}
              </td>
              <td>
                {$books[$i][authors]}
              </td>
              <td>
                {$books[$i][publishinghouse]}
              </td>
              <td>
                {$books[$i][yearofpublishing]}
              </td>
              <td>
                {$books[$i][notation]}
              </td>
              <td>
                {$books[$i][inventorynumber]}
              </td>
              <td>
                ".date("d.m.Y", $dateout)."
              </td>
              <td>
                {$datereturn}
              </td>
            </tr>
            ";
          } else {
            $datereturn = strtotime($books[$i][datereturn]);
            echo "
            <tr>
              <td>
                {$books[$i][title]}
              </td>
              <td>
                {$books[$i][type]}
              </td>
              <td>
                {$books[$i]['class']}
              </td>
              <td>
                {$books[$i][authors]}
              </td>
              <td>
                {$books[$i][publishinghouse]}
              </td>
              <td>
                {$books[$i][yearofpublishing]}
              </td>
              <td>
                {$books[$i][notation]}
              </td>
              <td>
                {$books[$i][inventorynumber]}
              </td>
              <td>
                ".date("d.m.Y", strtotime($books[$i][date]))."
              </td>
              <td>
                ".date("d.m.Y", $datereturn)."
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
    echo "Читатель ещё не брал книг.";
  }

  if (USER_LOGGED) {
    $booktarget = new FieldText("booktarget", "Книга", "", true);
    $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], false);
    $readertarget = new FieldHiddenInt("readertarget",  $_REQUEST['readertarget'], false);
    $formout = new Form(array("booktarget" => $booktarget, "id_catalog" => $id_catalog,
    "readertarget" => $readertarget), "Выдать", "GET", "readercard.php");

    $booksreturn = new FieldSelect("booksreturn", "Книга", "", $booksreturnarr);
    $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], false);
    $readertarget = new FieldHiddenInt("readertarget",  $_REQUEST['readertarget'], false);
    $formin = new Form(array("booksreturn" => $booksreturn, "id_catalog" => $id_catalog,
    "readertarget" => $readertarget), "Вернуть", "GET", "readercard.php");
    ?><div class='ui-widget'>
      [<a data-toggle="collapse" data-target="#hiddenf1">Выдача книг</a>]
      <div id="hiddenf1" class="collapse">
    <?php
    $formout->print_form();
    ?>
      </div></div>
      [<a data-toggle="collapse" data-target="#hiddenf2">Возврат книг</a>]
      <div id="hiddenf2" class="collapse">
    <?php
      $formin->print_form();
      echo "</div><br>";
   }



  } else {
    echo "Ошибка при поиске читателя.";
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
<script type="text/javascript" src="js/readercard.js"></script>
<script type="text/javascript" src="../../js/jqueryUI-1.11.4.min.js"></script>
