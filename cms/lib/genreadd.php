<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = intval($_GET['page']);
}
try {
  $name  = new FieldText("name", "Жанр", $_REQUEST['name'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "page" => $page), "Добавить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $datagoods = $pdo_Lib->prepare("INSERT INTO $tbl_Lib_genre SET name = ?");
      $datagoods->bindValue(1, $form->fields['name']->get_value(), PDO::PARAM_STR);
      $datagoods->execute();
      header("Location: indexgenre.php?page={$form->fields['page']->get_value()}");
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
