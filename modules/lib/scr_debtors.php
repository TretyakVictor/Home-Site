<?php
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';

try {
  if (!empty($_POST['id_catalog'])) {
    $_POST['id_catalog'] = intval($_POST['id_catalog']);
  }

  $pdo_lib = $pdo_lib->pdoGet();
  $query = "SELECT `idreader`, `patronymic`, `surname`, `name` FROM $tbl_reader ORDER BY `idreader` DESC";
  $data = $pdo_lib->query($query);
  if (!$data) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице авторов.");
  }
  if ($data->rowCount()) {
    while ($dat = $data->fetch()){
      $readers[$dat[idreader]] = $dat[surname]." ".$dat[name]." ".$dat[patronymic];
    };
  }

  $query = "SELECT * FROM $tbl_inventorybooks `T4`, (SELECT `T1`.idinventorybooks, `T2`.reader_idreader,
    `T2`.dateandtime, `T2`.dateandtimereturn FROM $tbl_inventorybooks `T1`
    LEFT JOIN $tbl_movement `T2` ON `T1`.idinventorybooks = `T2`.inventorybooks_idinventorybooks WHERE `T2`.switch = 1) `T3`
    WHERE `T4`.idinventorybooks = `T3`.idinventorybooks ORDER BY `T3`.reader_idreader, `T3`.dateandtime";

  // echo $query;
  $databooks = $pdo_lib->query($query);
  if (!$databooks) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице книг или читателей.");
  }
  if ($databooks->rowCount()) {
    while ($dat = $databooks->fetch()){
      $books[] = array('idinventorybooks' => $dat[idinventorybooks],
      'inventorynumber' => $dat[inventorynumber], 'title' => $dat[title],
      'yearofpublishing' => $dat[yearofpublishing], 'notation' => $dat[notation],
      'publishinghouse' => $dat[publishinghouse_idpublishinghouse],
      'authors' => $dat[authors_idauthors], 'reader' => $dat[reader_idreader],
      'date' => $dat[dateandtime], 'datereturn' => $dat[dateandtimereturn],
      'class' => $dat['class']);
    };
  }
  // print_r($books);
  // echo "<br>".count($books);
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
          Список книг
        </caption>
        <tr>
          <th>
            Читатель
          </th>
          <th>
            Название
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
        $nowdate = strtotime("now");
        for ($i=0; $i < count($books); $i++) {
            $dateout = strtotime($books[$i][date]);
            $datereturn = $dateout+3600*24*15;
            if ($datereturn < $nowdate) {
              $class = "class='danger'";
            }else {
              $class = "class='success'";
            }
            if (!empty($_POST[selectrdr])) {
              $_POST[selectrdr] = intval($_POST[selectrdr]);
              if ($_POST[selectrdr] == 1) {
                echo "
                <tr $class>
                  <td>
                    <a href='readercard.php?id_catalog={$_POST[id_catalog]}&readertarget={$books[$i][reader]}'>".$readers[$books[$i][reader]]."</a>
                  </td>
                  <td>
                    {$books[$i][title]}
                  </td>
                  <td>
                    {$books[$i]['class']}
                  </td>
                  <td>
                    {$author[$books[$i][authors]]}
                  </td>
                  <td>
                    {$publishinghouses[$books[$i][publishinghouse]]}
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
                    ".date("d.m.Y", $datereturn)."
                  </td>
                </tr>
                ";
              } elseif ($_POST[selectrdr] == 2 && $class == "class='danger'") {
                echo "
                <tr $class>
                  <td>
                    <a href='readercard.php?id_catalog={$_POST[id_catalog]}&readertarget={$books[$i][reader]}'>".$readers[$books[$i][reader]]."</a>
                  </td>
                  <td>
                    {$books[$i][title]}
                  </td>
                  <td>
                    {$books[$i]['class']}
                  </td>
                  <td>
                    {$author[$books[$i][authors]]}
                  </td>
                  <td>
                    {$publishinghouses[$books[$i][publishinghouse]]}
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
                    ".date("d.m.Y", $datereturn)."
                  </td>
                </tr>
                ";
              } elseif ($_POST[selectrdr] == 3 && $class == "class='success'") {
                echo "
                <tr $class>
                  <td>
                    <a href='readercard.php?id_catalog={$_POST[id_catalog]}&readertarget={$books[$i][reader]}'>".$readers[$books[$i][reader]]."</a>
                  </td>
                  <td>
                    {$books[$i][title]}
                  </td>
                  <td>
                    {$books[$i]['class']}
                  </td>
                  <td>
                    {$author[$books[$i][authors]]}
                  </td>
                  <td>
                    {$publishinghouses[$books[$i][publishinghouse]]}
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
                    ".date("d.m.Y", $datereturn)."
                  </td>
                </tr>
                ";
              }
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
