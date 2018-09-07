<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = intval($_GET['page']);
}

if (empty($_GET['id_genre'])) {
  header("Location: indexgenre.php?page={$_GET['page']}");
} else {
  $_REQUEST['id_genries'] = intval($_GET['id_genre']);
}
try {
  $data = $pdo_Lib->prepare("SELECT * FROM $tbl_Lib_genre WHERE idgenre=? LIMIT 1");
  $data->execute([intval($_GET['id_genre'])]);
  if ($data->rowCount() > 0) {
    $dataerr = $data->fetchAll();
    $dataarr = $dataerr[0];
    if (empty($_POST)) {
      $_REQUEST = $dataarr;
    }
  }
  $name  = new FieldText("name", "Новое наименование жанра", $_REQUEST['name'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $id_genries = new FieldHiddenInt("id_genries",  $_REQUEST['id_genries'], false);
  $form = new Form(array("name" => $name, "id_genries" => $id_genries, "page" => $page), "Изменить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $datashop = $pdo_Lib->prepare("UPDATE $tbl_Lib_genre SET name = ? WHERE idgenre = ?");
        $datashop->bindValue(1, $form->fields['name']->get_value(), PDO::PARAM_STR);
        $datashop->bindValue(2, $form->fields['id_genries']->get_value(), PDO::PARAM_INT);
        $datashop->execute();
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
