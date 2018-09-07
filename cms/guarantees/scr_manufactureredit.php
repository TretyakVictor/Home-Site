<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = intval($_GET['page']);
}

if (empty($_GET['id_manufacturer'])) {
  header("Location: indexmanufacturer.php?page={$_GET['page']}");
} else {
  $_REQUEST['id_manufacturer'] = intval($_GET['id_manufacturer']);
}
try {
  $data = $pdo_grnt->prepare("SELECT * FROM $tbl_manufacturer WHERE idmanufacturer=? LIMIT 1");
  $data->execute([intval($_GET['id_manufacturer'])]);
  if ($data->rowCount() > 0) {
    $dataerr = $data->fetchAll();
    $dataarr = $dataerr[0];
    if (empty($_POST)) {
      $_REQUEST = $dataarr;
    }
  }
  $name  = new FieldText("name", "Наименование", $_REQUEST['name'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $id_manufacturer = new FieldHiddenInt("id_manufacturer",  $_REQUEST['id_manufacturer'], false);
  $form = new Form(array("name" => $name,"id_manufacturer" => $id_manufacturer, "page" => $page), "Изменить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $datamanufacturer = $pdo_grnt->prepare("UPDATE $tbl_manufacturer SET name = ? WHERE idmanufacturer = ?");
        $datamanufacturer->bindValue(1, $form->fields['name']->get_value(), PDO::PARAM_STR);
        $datamanufacturer->bindValue(2, $form->fields['id_manufacturer']->get_value(), PDO::PARAM_INT);
        $datamanufacturer->execute();
      header("Location: indexmanufacturer.php?page={$form->fields[page]->get_value()}");
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
