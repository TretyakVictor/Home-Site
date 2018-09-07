<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

if (empty($_POST)) {
  $_REQUEST['hide'] = true;
}

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = $_GET['page'];
}
$_GET['id_position'] = intval($_GET['id_position']);
$_GET['id_catalog']  = intval($_GET['id_catalog']);
$_GET['id_paragraph'] = intval($_GET['id_paragraph']);

try {
  if (empty($_POST)) {
    $par = $pdo->prepare("SELECT * FROM $tbl_paragraph WHERE id_catalog=? AND id_position=? AND id_paragraph=? LIMIT 1");
    $par->bindValue(1, $_GET['id_catalog'], PDO::PARAM_INT);
    $par->bindValue(2, $_GET['id_position'], PDO::PARAM_INT);
    $par->bindValue(3, $_GET['id_paragraph'], PDO::PARAM_INT);
    $par->execute();
    $data = $par->fetchAll();
    $_REQUEST = $data[0];
    if ($_REQUEST['hide'] == 'show') {
      $_REQUEST['hide'] = true;
    }else {
      $_REQUEST['hide'] = false;
    }
  }

  $typearr = array("text" => "Параграф", "title_h1" => "Заголовок H1",
  "title_h2" => "Заголовок H2", "title_h3" => "Заголовок H3",
  "title_h4" => "Заголовок H4", "title_h5" => "Заголовок H5",
  "list" => "Список", "listnum" => "Список нумерованный", "table" => "Таблица",
  "code" => "Код");

  $name = new FieldTextarea("name", "Содержимое", $_REQUEST['name'], true);
  $type = new FieldSelect("type", "Тип параграфа", $_REQUEST['type'], $typearr);
  $align = new FieldSelect("align", "Тип выравнивания параграфа", $_REQUEST['align'], array("left" => "Слева", "center" => "По центру", "right" => "Справа"));
  $hide = new FieldCheckbox("hide", "Отображать", $_REQUEST['hide']);
  $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], true);
  $id_position = new FieldHiddenInt("id_position",  $_REQUEST['id_position'], true);
  $id_paragraph = new FieldHiddenInt("id_paragraph",  $_REQUEST['id_paragraph'], true);
  $pos = new FieldHidden("pos",  $_REQUEST['pos'], false);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "type" => $type, "align" => $align,
  "hide" => $hide, "id_catalog" => $id_catalog, "id_position" => $id_position, "id_paragraph" => $id_paragraph, "page" => $page, "pos" => $pos), "Изменить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      $query = "UPDATE $tbl_paragraph SET name = ?, type = ?, align = ?, hide = ?
      WHERE id_catalog = ? AND id_position = ? AND id_paragraph = ?";
      $parupdate = $pdo->prepare($query);
      $parupdate->bindValue(1, $form->fields[name]->get_value(), PDO::PARAM_STR);
      $parupdate->bindValue(2, $form->fields[type]->get_value(), PDO::PARAM_STR);
      $parupdate->bindValue(3, $form->fields[align]->get_value(), PDO::PARAM_STR);
      $parupdate->bindValue(4, $showhide, PDO::PARAM_STR);
      $parupdate->bindValue(5, $form->fields[id_catalog]->get_value(), PDO::PARAM_INT);
      $parupdate->bindValue(6, $form->fields[id_position]->get_value(), PDO::PARAM_INT);
      $parupdate->bindValue(7, $form->fields[id_paragraph]->get_value(), PDO::PARAM_INT);
      $parupdate->execute();

      header("Location: scr_paragraph.php?id_position={$form->fields[id_position]->get_value()}&".
      "id_catalog={$form->fields[id_catalog]->get_value()}&page={$form->fields[page]->get_value()}");
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
  require_once '../utils/scr_bb.php';

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
<script src="../utils/js/scr_bb.js"></script>
