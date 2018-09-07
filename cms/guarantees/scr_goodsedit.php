<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = intval($_GET['page']);
}

if (empty($_GET['id_goods'])) {
  header("Location: indexgoods.php?page={$_GET['page']}");
} else {
  $_REQUEST['id_goods'] = intval($_GET['id_goods']);
}
try {
  if (empty($_REQUEST['quantity'])) {
    $_REQUEST['quantity'] = 1;
  }
  if (empty($_REQUEST['quantity'])) {
    $_REQUEST['quantity'] = 0;
  }

  $data = $pdo_grnt->prepare("SELECT * FROM $tbl_goods WHERE idgoods=? LIMIT 1");
  $data->execute([intval($_GET['id_goods'])]);
  if ($data->rowCount() > 0) {
    $dataerr = $data->fetchAll();
    $dataarr = $dataerr[0];
    if (empty($_POST)) {
      $_REQUEST = $dataarr;
    }
  }

  $datashops = $pdo_grnt->prepare("SELECT * FROM $tbl_shops ORDER BY `idshops` DESC");
  $datashops->execute();
  if ($datashops->rowCount()) {
    while ($dat = $datashops->fetch()){
      $shops[$dat[idshops]] = $dat[name]."  ".$dat[address];
    };
  }

  $datamanufacturers = $pdo_grnt->prepare("SELECT * FROM $tbl_manufacturer ORDER BY `idmanufacturer` DESC");
  $datamanufacturers->execute();
  if ($datamanufacturers->rowCount()) {
    while ($dat = $datamanufacturers->fetch()){
      $manufacturers[$dat[idmanufacturer]] = $dat[name];
    };
  }

  $symbols = array(1 => 'Дни', 2 => 'Недели', 3 => 'Месяца', 4 => 'Годы');
  $today = new DateTime();
  $name = new FieldTextarea("name", "Название", $_REQUEST['name'], true);
  $price = new FieldDouble("price", "Цена", $_REQUEST['price'], true, 1, 10000000);
  $quantity = new FieldInt("quantity", "Количество", $_REQUEST['quantity'], true, 1, 100);
  $guarantee = new FieldInt("guarantee", "Гарантия", $_REQUEST['guarantee'], true, 0, 100000);
  $symbol = new FieldSelect("symbol", "", $_REQUEST['symbol'], $symbols);
  $date = new FieldDate("date", "Дата", $_REQUEST['date'], true);
  $number  = new FieldText("number", "Номер", $_REQUEST['ordernumber'], false);
  $shop = new FieldSelect("shop", "Магазин", $_REQUEST['shops_idshops'], $shops);
  $manufacturer = new FieldSelect("manufacturer", "Производитель", $_REQUEST['manufacturer_idmanufacturer'], $manufacturers);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $available = new FieldHiddenInt("available",  1, true);
  $id_goods = new FieldHiddenInt("id_goods",  $_REQUEST['id_goods'], true);
  $form = new Form(array("name" => $name, "price" => $price, "quantity" => $quantity,
  "guarantee" => $guarantee, "symbol" => $symbol, "date" => $date,
  "number" => $number, "shop" => $shop, "manufacturer" => $manufacturer,
  "available" => $available, "page" => $page, "id_goods" => $id_goods), "Изменить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $datagoods = $pdo_grnt->prepare("UPDATE $tbl_goods SET name = ?, price = ?, quantity = ?, guarantee = ?, `date` = ?, ordernumber = ?,
        symbols = ?, available = ?, daterec = ?, shops_idshops = ?, manufacturer_idmanufacturer = ?
        WHERE idgoods = ?");
      $datagoods->bindValue(1, $form->fields['name']->get_value(), PDO::PARAM_STR);
      $datagoods->bindValue(2, $form->fields['price']->get_value(), PDO::PARAM_STR);
      $datagoods->bindValue(3, $form->fields['quantity']->get_value(), PDO::PARAM_INT);
      $datagoods->bindValue(4, $form->fields['guarantee']->get_value(), PDO::PARAM_INT);
      $datagoods->bindValue(5, $form->fields['date']->get_value(), PDO::PARAM_STR);
      $datagoods->bindValue(6, $form->fields['number']->get_value(), PDO::PARAM_STR);
      $datagoods->bindValue(7, $form->fields['symbol']->get_value(), PDO::PARAM_STR);
      $datagoods->bindValue(8, $form->fields['available']->get_value(), PDO::PARAM_BOOL);
      $datagoods->bindValue(9, $today->format("Y-m-d"), PDO::PARAM_STR);
      $datagoods->bindValue(10, $form->fields['shop']->get_value(), PDO::PARAM_INT);
      $datagoods->bindValue(11, $form->fields['manufacturer']->get_value(), PDO::PARAM_INT);
      $datagoods->bindValue(12, $form->fields['id_goods']->get_value(), PDO::PARAM_INT);
      $datagoods->execute();
      header("Location: indexgoods.php?page={$form->fields[page]->get_value()}");
      exit();
    }
  }
  require_once '../utils/head.php';
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
  if (!empty($error)) {
    foreach ($error as $err) {
      echo "<span style=\"color:red\">$err</span><br>";
    }
  }
  $form->print_form();

} catch (Exception $e) {
  echo $e;
}
require_once '../utils/bottom.php';
?>
