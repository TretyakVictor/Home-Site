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
  $_GET['id_position'] = intval($_GET['id_position']);
  $_GET['id_catalog'] = intval($_GET['id_catalog']);
  if (empty($_POST)) {
    $query = "SELECT * FROM $tbl_position WHERE id_catalog=$_GET[id_catalog] AND id_position=$_GET[id_position] LIMIT 1";
    $pos = $pdo->query($query);
    $poserr = $pos->fetchAll();
    // print_r($news);
    if (empty($poserr)) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к таблице новостей");
    }
    $_REQUEST = $poserr[0];
    if ($_REQUEST['hide'] == 'show') {
      $_REQUEST['hide'] = true;
    }else {
      $_REQUEST['hide'] = false;
    }
  }
  $name = new FieldText("name", "Название", $_REQUEST['name'], true);
  $url = new FieldText("url", "URL", $_REQUEST['url'], true);
  $keywords  = new FieldText("keywords", "Ключевые слова", $_REQUEST['keywords'], false);
  $hide = new FieldCheckbox("hide", "Отображать", $_REQUEST['hide']);
  $id_position = new FieldHiddenInt("id_position",  $_REQUEST['id_position'], true);
  $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "url" => $url, "keywords" => $keywords,
  "hide" => $hide, "id_catalog" => $id_catalog, "id_position" => $id_position, "page" => $page), "Изменить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      $query = "UPDATE $tbl_position SET name = '{$form->fields[name]->get_value()}',
      url = '{$form->fields[url]->get_value()}', keywords = '{$form->fields[keywords]->get_value()}',
      hide = '$showhide' WHERE id_position = {$form->fields[id_position]->get_value()} AND id_catalog ={$form->fields[id_catalog]->get_value()}";
      if (!$pdo->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка при обновлении каталога");
      }
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
