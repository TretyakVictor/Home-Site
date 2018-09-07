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
$_GET['pos']  = intval($_GET['pos']);

try {
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
  $pos = new FieldHidden("pos",  $_REQUEST['pos'], false);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "type" => $type, "align" => $align,
  "hide" => $hide, "id_catalog" => $id_catalog, "id_position" => $id_position, "page" => $page, "pos" => $pos), "Добавить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      if (!$form->fields['pos']->get_value()) {
        $query = "SELECT MAX(pos) FROM $tbl_paragraph WHERE id_catalog={$form->fields[id_catalog]->get_value()} AND id_position={$form->fields[id_position]->get_value()}";
        $pos = $pdo->query($query);
        if (!$pos) {
          throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при извлечении текущей позиции");
        }
        $pos = $pos->fetchColumn() + 1;
      }elseif ($form->fields['pos']->get_value() < 0) {
        $query = "UPDATE $tbl_paragraph SET pos = pos + 1 WHERE id_catalog={$form->fields[id_catalog]->get_value()}
        AND id_position={$form->fields[id_position]->get_value()}";
        if (!$pdo->query($query)) {
          throw new ExceptionMySQL("", $query, "Ошибка при редактировании позиции параграфа.");
        }
        $pos = 1;
      }else {
        $query = "UPDATE $tbl_paragraph SET pos = pos + 1 WHERE id_catalog={$form->fields[id_catalog]->get_value()} AND id_position={$form->fields[id_position]->get_value()} AND pos > {$form->fields[pos]->get_value()}";
        if (!$pdo->query($query)) {
          throw new ExceptionMySQL("", $query, "Ошибка при редактировании позиции параграфа.");
        }
        $pos = $form->fields['pos']->get_value() + 1;
      }
      $query = "INSERT INTO $tbl_paragraph VALUES (NULL, '{$form->fields['name']->get_value()}',
      '{$form->fields['type']->get_value()}', '{$form->fields['align']->get_value()}',
      '$showhide', $pos, {$form->fields['id_position']->get_value()}, {$form->fields['id_catalog']->get_value()})";
      if (!$pdo->query($query)) {
        throw new ExceptionMySQL("", $query, "Ошибка при добавлении нового параграфа.");
      }

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
