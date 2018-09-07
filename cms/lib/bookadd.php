<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
require_once '../utils/image_resize.php';

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = intval($_GET['page']);
}
try {
  $today = new DateTime();
  if (empty($_REQUEST['quantity'])) {
    $_REQUEST['quantity'] = 1;
  }
  if (empty($_REQUEST['date'])) {
    $_REQUEST['date'] = $today->format("Y-m-d");
  }
  if (empty($_REQUEST['genre']) || $_REQUEST['error'] = true) {
    $datagenres = $pdo_Lib->prepare("SELECT * FROM $tbl_Lib_genre ORDER BY `name` ASC");
    $datagenres->execute();
    if ($datagenres->rowCount()) {
      while ($dat = $datagenres->fetch()){
        $genres[$dat['idgenre']] = $dat['name'];
      };
    }
  } else {
    $genres = array(1 => ' ');
  }
  if (empty($_REQUEST['publishinghouse']) || $_REQUEST['error'] = true) {
    $datapublishinghouse = $pdo_Lib->prepare("SELECT * FROM $tbl_Lib_publishinghouse ORDER BY `name` ASC");
    $datapublishinghouse->execute();
    if ($datapublishinghouse->rowCount()) {
      while ($dat = $datapublishinghouse->fetch()){
        $publishinghouse[$dat['idpublishinghouse']] = $dat['name'];
      };
    }
  } else {
    $publishinghouse = array(1 => ' ');
  }
  // Убрать из путей ../../
  $path_small_img = "files/lib/small/";
  $path_big_img = "files/lib/big/";
  $img_name_prefix = uniqid('', true);
  $statuses = array('finished' => 'Выпуск завершен', 'unfinished' => 'Выпуск не завершен');
  $name = new FieldTextarea("name", "Название произведения", $_REQUEST['name'], true);
  $quantity = new FieldInt("quantity", "Количество томов/номеров", $_REQUEST['quantity'], true, 1, 1000);
  $date = new FieldDate("date", "Дата выпуска", $_REQUEST['date'], true);
  $status = new FieldSelect("status", "Статус", $_REQUEST['status'], $statuses);
  $discription  = new FieldTextarea("discription", "Описание", $_REQUEST['discription'], false);
  $site  = new FieldText("site", "Ссылка на сайт", $_REQUEST['site'], false);
  $genre = new FieldSelect("genre", "Жанр", $_REQUEST['genre'], $genres);
  $publishinghouse = new FieldSelect("publishinghouse", "Издательство", $_REQUEST['publishinghouse'], $publishinghouse);
  $picture = new FieldFile("picture", "Изображение", $_FILES, false, "../../".$path_big_img, $img_name_prefix);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("name" => $name, "quantity" => $quantity, "date" => $date, "status" => $status,
  "discription" => $discription, "site" => $site, "genre" => $genre, "publishinghouse" => $publishinghouse,
  "picture" => $picture, "page" => $page), "Добавить");
  if (!empty($_POST)) {
    $error = $form->check();
    if (empty($error)) {
      $resolution_width = '0';
      $resolution_height = '200';

      // if ($_FILES['picture']) {
      //   $image_name = $path_big_img.$_FILES['picture']['name'];
      //   $image_name_small = $path_small_img."small_".$_FILES['picture']['name'];
      //   image_resize($image_name, $image_name_small, $resolution_width, $resolution_height);
      // } else {
      //   $image_name_small = $path_small_img."small_".$_FILES['picture']['name'];
      // }
      // $big_img = $form->fields['picture']->get_filename();
      // if (!empty($big_img)) {
      //   $image_name_big = $path_big_img.$big_img;
      // } else {
      //   $image_name_big = "";
      // }
      // $image_name = $form->fields['name']->get_value();
      $validimg  = $big_img = $form->fields['picture']->get_filename();
      if (!empty($validimg)) {
        $image_name_big = $path_big_img.$form->fields['picture']->get_filename();
        $image_name_small = $path_small_img."small_".$form->fields['picture']->get_filename();
        // if (image_resize("../../".$image_name_big, "../../".$image_name_small, $resolution_width, $resolution_height)) {
        //   echo "<span style=\"color:red\">Picture error</span><br>";
        //   exit();
        // }
        // echo $image_name_big."</br>";
        // echo $form->fields['picture']->get_filename();
        // exit();
        image_resize("../../".$image_name_big, "../../".$image_name_small, $resolution_width, $resolution_height);
        $image_name = $_FILES['picture']['name'];
      } else {
        $image_name_big = "imgStyle/files/oops.jpg";
        $image_name_small = "imgStyle/files/small_oops.jpg";
        $image_name = "упс!";
      }

      $dataimg = $pdo_Lib->prepare("INSERT INTO $tbl_Lib_images SET name = ?, alt = ?, small = ?, big = ?");
      $dataimg->bindValue(1, $image_name, PDO::PARAM_STR);
      $dataimg->bindValue(2, " ", PDO::PARAM_STR);
      $dataimg->bindValue(3, $image_name_small, PDO::PARAM_STR);
      $dataimg->bindValue(4, $image_name_big, PDO::PARAM_STR);
      $dataimg->execute();
      // Поиск не должен быть по имени
      $dataimage = $pdo_Lib->prepare("SELECT * FROM $tbl_Lib_images WHERE `name` = '{$image_name}' LIMIT 1");
      $dataimage->execute();
      // Упростить
      if ($dataimage->rowCount()) {
        while ($dat = $dataimage->fetch()){
          $dataimageid = $dat['idimages'];
        };
      }


      $databooks = $pdo_Lib->prepare("INSERT INTO $tbl_Lib_books SET `name` = ?, `bookscol` = ?, `date` = ?, `dateadd` = ?, `status` = ?,
        `discription` = ?, `site` = ?, `images_idimages` = ?, `publishinghouse_idpublishinghouse` = ?");
        $images_idimages = 1;
      $databooks->bindValue(1, $form->fields['name']->get_value(), PDO::PARAM_STR);
      $databooks->bindValue(2, $form->fields['quantity']->get_value(), PDO::PARAM_INT);
      $databooks->bindValue(3, $form->fields['date']->get_value(), PDO::PARAM_STR);
      $databooks->bindValue(4, $today->format("Y-m-d"), PDO::PARAM_STR);
      $databooks->bindValue(5, $form->fields['status']->get_value(), PDO::PARAM_STR);
      $databooks->bindValue(6, $form->fields['discription']->get_value(), PDO::PARAM_STR);
      $databooks->bindValue(7, $form->fields['site']->get_value(), PDO::PARAM_STR);
      $databooks->bindValue(8, $dataimageid, PDO::PARAM_INT);
      $databooks->bindValue(9, $form->fields['publishinghouse']->get_value(), PDO::PARAM_INT);
      $databooks->execute();
      // Поиск не должен быть по имени
      $databook = $pdo_Lib->prepare("SELECT * FROM $tbl_Lib_books WHERE `name` = '{$form->fields[name]->get_value()}' LIMIT 1");
      $databook->execute();
      // Упростить
      if ($databook->rowCount()) {
        while ($dat = $databook->fetch()){
          $databookid = $dat['idbooks'];
        };
      }
      $datagenries = $pdo_Lib->prepare("INSERT INTO $tbl_Lib_genries SET `books_idbooks` = ?, `genre_idgenre` = ?");
      $datagenries->bindValue(1, $databookid, PDO::PARAM_INT);
      $datagenries->bindValue(2, $form->fields['genre']->get_value(), PDO::PARAM_INT);
      $datagenries->execute();
      header("Location: indexbooks.php?page={$form->fields['page']->get_value()}");
      exit();
    }
  }
  require_once '../utils/head.php';
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
  if (!empty($error)) {
    $_REQUEST['error'] = true;
    foreach ($error as $err) {
      echo "<span style=\"color:red\">$err</span><br>";
    }
  } else {
    $_REQUEST['error'] = false;
  }
  $form->print_form();

} catch (Exception $e) {
  echo $e;
}
require_once '../utils/bottom.php';
?>
