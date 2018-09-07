<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

if (empty($_POST)) {
  $_REQUEST['hide'] = true;
}
if (!empty($_GET['page'])) {
  $_GET['page'] = intval($_GET['page']);
}else {
  $_GET['page'] = 1;
}

try {
  $today = date("Y-m-d G:i:s");

  $name = new FieldText("name", "Название", $_POST['name'], true);
  $body = new FieldTextarea("body", "Содержимое", $_POST['body'], true);
  $url  = new FieldURL("url", "Ссылка", $_POST['url'], false);
  $urltext = new FieldText("urltext", "Текст ссылки", $_POST['urltext'], false);
  $date = new FieldDateTime("date", "Дата новости", $today, false);
  $hide = new FieldCheckbox("hide", "Отображать", $_REQUEST['hide']);
  $urlpict = new FieldFile("urlpict", "Изображение", $_FILES, false, "../../files/news/");
  $page = new FieldHiddenInt("page",  $_GET['page'], false);
  $form = new Form(array("name" => $name, "body" => $body, "url" => $url,
  "urltext" => $urltext, "date" => $date, "hide" => $hide,
  "urlpict" => $urlpict, "page" => $page), "Добавить");

  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      $str = $form->fields['urlpict']->get_filename();
      if (!empty($str)) {
        $img = "files/news/".$form->fields['urlpict']->get_filename();
      }else {
        $img = '';
      }
      $query = "INSERT INTO $tbl_news VALUES (NULL, '{$form->fields[name]->get_value()}',
        '{$form->fields[body]->get_value()}', '{$form->fields[date]->get_value()}',
        '{$form->fields[url]->get_value()}', '{$form->fields[urltext]->get_value()}', '$img', '$showhide')";
      if (!$pdo->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка добавления");
      }
      header("Location: index.php?page={$form->fields[page]->get_value()}");
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

} catch (ExceptionObject $e) {
  require("../utils/exception_object.php");
} catch (ExceptionMySQL $e) {
  require("../utils/exception_mysql.php");
} catch (ExceptionMember $e) {
  require("../utils/exception_member.php");
}
require_once '../utils/bottom.php';
?>
