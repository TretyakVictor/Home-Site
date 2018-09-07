<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
require_once '../utils/image_resize.php';

if (empty($_POST)) {
  $_REQUEST['hide'] = true;
  $_REQUEST['size'] = true;
}

$_GET['id_catalog']   = intval($_GET['id_catalog']);
$_GET['id_position']  = intval($_GET['id_position']);
$_GET['id_paragraph'] = intval($_GET['id_paragraph']);
if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = $_GET['page'];
}
try {
  $path_big = "files/article/big/";
  $path_small = "files/article/small/";
  $resolution_width = '0';
  $resolution_height = '200';

  $name = new FieldText("name", "Название", $_REQUEST['name'], false);
  $alt = new FieldText("alt", "Описание", $_REQUEST['alt'], false);
  $big = new FieldFile("big", "Крупное изображение", $_FILES, true, "../../".$path_big);
  $size = new FieldCheckbox("size", "Отображать большую", $_REQUEST['size']);
  $hide = new FieldCheckbox("hide", "Отображать", $_REQUEST['hide']);
  $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], true);
  $id_position = new FieldHiddenInt("id_position",  $_REQUEST['id_position'], true);
  $id_paragraph = new FieldHiddenInt("id_paragraph",  $_REQUEST['id_paragraph'], true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "alt" => $alt, "big" => $big, "size" => $size,
  "hide" => $hide, "page" => $page, "id_catalog" => $id_catalog,
  "id_position" => $id_position, "id_paragraph" => $id_paragraph,), "Добавить");

  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $query = "SELECT MAX(pos) FROM $tbl_paragraph_image WHERE id_catalog=? AND id_position=? AND id_paragraph=?";
      $stmt = $pdo->prepare($query);
      $stmt->bindValue(1, $form->fields['id_catalog']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(2, $form->fields['id_position']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(3, $form->fields['id_paragraph']->get_value(), PDO::PARAM_STR);
      $stmt->execute();

      $pos = $stmt->fetchColumn() + 1;
      if ($form->fields['hide']->get_value()) {
        $showhide = "show";
      }else {
        $showhide = "hide";
      }
      if ($form->fields['size']->get_value()) {
        $sizeflag = true;
      }else {
        $sizeflag = false;
      }

      if ($_FILES['big']) {
        $image_name = "../../".$path_big.$_FILES['big']['name'];
        $image_name_small = "../../".$path_small."small_".$_FILES['big']['name'];
        image_resize($image_name, $image_name_small, $resolution_width, $resolution_height);
      }
      $small = $path_small."small_".$_FILES['big']['name'];

      $var = $form->fields['big']->get_filename();
      if (!empty($var)) {
        $big = $path_big.$var;
      } else {
        $big = "";
      }

      $sql = "INSERT INTO $tbl_paragraph_image SET name = ?, alt = ?, small = ?, big = ?,
      hide = ?, pos = ?, id_position = ?, id_catalog = ?, id_paragraph = ?, mode = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $form->fields[name]->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(2, $form->fields[alt]->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(3, $small, PDO::PARAM_STR);
      $stmt->bindValue(4, $big, PDO::PARAM_STR);
      $stmt->bindValue(5, $showhide, PDO::PARAM_STR);
      $stmt->bindValue(6, $pos, PDO::PARAM_INT);
      $stmt->bindValue(7, $form->fields[id_position]->get_value(), PDO::PARAM_INT);
      $stmt->bindValue(8, $form->fields[id_catalog]->get_value(), PDO::PARAM_INT);
      $stmt->bindValue(9, $form->fields[id_paragraph]->get_value(), PDO::PARAM_INT);
      $stmt->bindValue(10, $sizeflag, PDO::PARAM_BOOL);
      $stmt->execute();
      exit("<meta http-equiv='refresh' content='0; url=scr_image.php?id_paragraph={$form->fields[id_paragraph]->get_value()}&
      id_position={$form->fields[id_position]->get_value()}&id_catalog={$form->fields[id_catalog]->get_value()}&page=
      {$form->fields[page]->get_value()}'>");
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

}
require_once '../utils/bottom.php';
?>
