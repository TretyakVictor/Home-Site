<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = intval($_GET['page']);
}

if (empty($_GET['id_shop'])) {
  header("Location: indexshop.php?page={$_GET['page']}");
} else {
  $_REQUEST['id_shops'] = intval($_GET['id_shop']);
}
try {
  $data = $pdo_grnt->prepare("SELECT * FROM $tbl_shops WHERE idshops=? LIMIT 1");
  $data->execute([intval($_GET['id_shop'])]);
  if ($data->rowCount() > 0) {
    $dataerr = $data->fetchAll();
    $dataarr = $dataerr[0];
    if (empty($_POST)) {
      $_REQUEST = $dataarr;
    }
  }
  $name  = new FieldText("name", "Наименование", $_REQUEST['name'], true);
  $address  = new FieldText("address", "Адрес", $_REQUEST['address'], false);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $id_shops = new FieldHiddenInt("id_shops",  $_REQUEST['id_shops'], false);
  $form = new Form(array("name" => $name, "address" => $address, "id_shops" => $id_shops, "page" => $page), "Изменить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $datashop = $pdo_grnt->prepare("UPDATE $tbl_shops SET name = ?, address = ? WHERE idshops = ?");
        $datashop->bindValue(1, $form->fields['name']->get_value(), PDO::PARAM_STR);
        $datashop->bindValue(2, $form->fields['address']->get_value(), PDO::PARAM_STR);
        $datashop->bindValue(3, $form->fields['id_shops']->get_value(), PDO::PARAM_INT);
        $datashop->execute();
      header("Location: indexshop.php?page={$form->fields[page]->get_value()}");
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
