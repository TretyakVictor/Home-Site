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
if ($_GET['id_parent']) {
  $_REQUEST['id_parent'] = $_GET['id_parent'];
}
if (empty($_GET['id_parent'])) {
  $_REQUEST['id_parent'] = 0;
}
try {
  $name = new FieldText("name", "Название", $_REQUEST['name'], true);
  $url = new FieldText("url", "URL", $_REQUEST['url'], true);
  $keywords  = new FieldText("keywords", "Ключевые слова", $_REQUEST['keywords'], false);
  $hide = new FieldCheckbox("hide", "Отображать", $_REQUEST['hide']);
  $id_parent = new FieldHiddenInt("id_parent",  $_REQUEST['id_parent'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "url" => $url, "keywords" => $keywords,
  "hide" => $hide, "id_parent" => $id_parent, "page" => $page), "Добавить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $query = "SELECT MAX(pos) FROM $tbl_position WHERE id_catalog = {$form->fields[id_parent]->get_value()}";
      $pos = $pdo->query($query);
      if (!$pos) {
        throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при извлечении текущей позиции");
      }
      $position = $pos->fetchColumn() + 1;
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      $query = "INSERT INTO $tbl_position VALUES (NULL, '{$form->fields[name]->get_value()}',
        '{$form->fields[url]->get_value()}', '{$form->fields[keywords]->get_value()}',
        $position, '$showhide', {$form->fields[id_parent]->get_value()})";
      if (!$pdo->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка при добавлении новой позиции");
      }
      header("Location: index.php?"."id_parent={$form->fields[id_parent]->get_value()}&"."page={$form->fields[page]->get_value()}");
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
