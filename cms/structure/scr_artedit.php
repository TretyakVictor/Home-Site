<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = $_GET['page'];
}

try {
  $_GET['id_position'] = intval($_GET['id_position']);
  $_GET['id_catalog']  = intval($_GET['id_catalog']);
  if (empty($_POST)) {
    $data = $pdo->prepare("SELECT * FROM $tbl_position WHERE id_catalog = ? AND id_position = ? LIMIT 1");
    $data->bindValue(1, $_GET['id_catalog'], PDO::PARAM_INT);
    $data->bindValue(2, $_GET['id_position'], PDO::PARAM_INT);
    $data->execute();
    $_REQUEST = $data->fetch();
    if ($_REQUEST['hide'] == 'show') {
      $_REQUEST['hide'] = true;
    } else {
      $_REQUEST['hide'] = false;
    }
  }
  $name = new FieldText("name", "Название", $_REQUEST['name'], true);
  $keywords  = new FieldText("keywords", "Ключевые слова", $_REQUEST['keywords'], false);
  $hide = new FieldCheckbox("hide", "Отображать", $_REQUEST['hide']);
  $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], true);
  $id_position = new FieldHiddenInt("id_position",  $_REQUEST['id_position'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "keywords" => $keywords,
  "hide" => $hide, "id_catalog" => $id_catalog, "id_position" => $id_position, "page" => $page), "Изменить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      $data = $pdo->prepare("UPDATE $tbl_position SET name = ?, keywords = ?, hide = ? WHERE id_position = ? AND id_catalog = ?");
      $data->bindValue(1, $form->fields['name']->get_value(), PDO::PARAM_STR);
      $data->bindValue(2, $form->fields['keywords']->get_value(), PDO::PARAM_STR);
      $data->bindValue(3, $form->fields['hide']->get_value(), PDO::PARAM_STR);
      $data->bindValue(4, $form->fields['id_position']->get_value(), PDO::PARAM_INT);
      $data->bindValue(5, $form->fields['id_catalog']->get_value(), PDO::PARAM_INT);
      $data->execute();

      header("Location: index.php?"."id_parent={$form->fields[id_catalog]->get_value()}&"."page={$form->fields[page]->get_value()}");
      exit();
    }
  }
  require_once("../utils/head.php");
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
  if (!empty($error)) {
    foreach ($error as $err) {
      echo "<span style=\"color:red\">$err</span><br>";
    }
  }
  $form->print_form();

} catch (ExceptionMySQL $e) {
  require '../utils/exception_mysql.php';
} catch (ExceptionObject $e) {
  require '../utils/exception_object.php';
} catch (ExceptionMember $e) {
  require '../utils/exception_member.php';
}
require_once '../utils/bottom.php';
?>
