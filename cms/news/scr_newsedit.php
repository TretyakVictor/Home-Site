<?php
include_once'../../configs/classes.config.cms.php';
include_once'../../configs/mysql.config.cms.php';

$_GET['id_news'] = intval($_GET['id_news']);

try {

  $query = "SELECT * FROM $tbl_news WHERE id_news=$_GET[id_news]";
  $data = $pdo->query($query);
  $dataerr = $data->fetchAll();

  if (empty($dataerr)) {
    throw new ExceptionMySQL(mysql_error(), $query, "Ошибка при обращении к таблице новостей");
  }
  $news = $dataerr[0];
  if (empty($_POST)) {
    $_REQUEST = $news;
    if (!empty($_GET['page'])) {
      $_REQUEST['page'] = $_GET['page'];
    }
    if ($news['hide'] == 'show') {
      $_REQUEST['hide'] = true;
    }else {
      $_REQUEST['hide'] = false;
    }
  }


  $name = new FieldText("name", "Название", $_REQUEST['name'], true);
  $body = new FieldTextarea("body", "Содержимое", $_REQUEST['body'], true);
  $url  = new FieldURL("url", "Ссылка", $_REQUEST['url'], false);
  $urltext = new FieldText("urltext", "Текст ссылки", $_REQUEST['urltext'], false);
  $putdate = new FieldDateTime("putdate", "Дата новости", $_REQUEST['putdate'], false);
  $hide = new FieldCheckbox("hide", "Отображать", $_REQUEST['hide']);
  $filename = new FieldFile("filename", "Изображение", $_FILES, false, "../../files/news/");
  $id_news = new FieldHiddenInt("id_news",  $_REQUEST['id_news'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);

  if (empty($news['urlpict'])) {
    $form = new Form(array("name" => $name, "body" => $body, "url" => $url,
    "urltext" => $urltext, "putdate" => $putdate, "hide" => $hide,
    "filename" => $filename, "id_news" => $id_news, "page" => $page), "Изменить");
  }else {
    $delimg = new field_checkbox("delimg", "Удалить изображение", $_REQUEST['delimg']);
    $form = new Form(array("name" => $name, "body" => $body, "url" => $url,
    "urltext" => $urltext, "putdate" => $putdate, "hide" => $hide, "delimg" => $delimg,
    "filename" => $filename, "id_news" => $id_news, "page" => $page), "Изменить");
  }


  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      $url_pict = "";
      $str = $form->fields['delimg']->value;
      if (!empty($str) || !empty($_FILES['filename']['name'])) {
        $path = str_replace("//","/","../../".$news['urlpict']);
        if (file_exists($path)) {
          @unlink($path);
        }
        $url_pict = "urlpict = '',";
      }
      if (!empty($_FILES['filename']['name'])) {
        $url_pict = "urlpict = 'files/news/".$form->fields['filename']->get_filename()."',";
      }
      $query = "UPDATE $tbl_news SET name = '{$form->fields['name']->get_value()}', body =
      '{$form->fields['body']->get_value()}', putdate = '{$form->fields['putdate']->get_value()}',
      url = '{$form->fields['url']->get_value()}', urltext = '{$form->fields['urltext']->get_value()}',
      $url_pict hide = '{$showhide}' WHERE id_news=".$form->fields['id_news']->get_value();
      if (!$pdo->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка обновления");
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
require_once("../utils/bottom.php");
?>
