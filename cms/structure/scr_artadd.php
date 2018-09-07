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

if (empty($_GET['id_parent'])) {
  $_REQUEST['id_parent'] = 0;
} else {
  $_REQUEST['id_parent'] = $_GET['id_parent'];
}
try {
  $name = new FieldText("name", "Название", $_REQUEST['name'], true);
  $description = new FieldTextarea("description", "Содержимое статьи", $_REQUEST['description'], false);
  $keywords  = new FieldText("keywords", "Ключевые слова", $_REQUEST['keywords'], false);
  $hide = new FieldCheckbox("hide", "Отображать", $_REQUEST['hide']);
  $id_parent = new FieldHiddenInt("id_parent",  $_REQUEST['id_parent'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "description" => $description, "keywords" => $keywords,
  "hide" => $hide, "id_parent" => $id_parent, "page" => $page), "Добавить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $pos = $pdo->prepare("SELECT MAX(pos) FROM $tbl_position WHERE id_catalog = ?");
      $pos->execute([$form->fields['id_parent']->get_value()]);
      $position = $pos->fetchColumn() + 1;
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      $query = "INSERT INTO $tbl_position VALUES (NULL, '{$form->fields[name]->get_value()}',
        'article', '{$form->fields[keywords]->get_value()}',
        $position, '$showhide', {$form->fields[id_parent]->get_value()})";
      if (!$pdo->query($query)) {
        throw new ExceptionMySQL("", $query,"Ошибка при добавлении новой позиции");
      }

      $id_position = $pdo->lastInsertId();
      $par = preg_split("|\r\n|", $form->fields['description']->get_value());
      if (!empty($par)) {
        $i = 0;
        foreach ($par as $parag) {
          $i++;
          $sql[] = "(NULL, '$parag', 'text', 'left', 'show', $i, $id_position, {$form->fields[id_parent]->get_value()})";
        }
        $data = $pdo->prepare("INSERT INTO $tbl_paragraph VALUES ".implode(",", $sql));
        $data->execute();
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
