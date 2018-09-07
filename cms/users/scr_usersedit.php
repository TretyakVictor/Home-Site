<?php
require_once '../../configs/classes.config.cms.php';
require_once '../../configs/mysql.config.cms.php';

function validateLogin($value){
  return preg_match( '/^[a-z\d]{3,34}$/i', trim($value));
}

if (empty($_GET['page'])) {
  $_REQUEST['page'] = 0;
}else {
  $_REQUEST['page'] = intval($_GET['page']);
}

try {
  $stmt = $pdo->prepare("SELECT * FROM $tbl_users WHERE id_users = ? LIMIT 1");
  $stmt->execute([$_GET['id_users']]);
  $stmt = $stmt->fetchAll();
  $dataarr = $stmt[0];
  if (empty($_POST)) {
    $_REQUEST = $dataarr;
  }

  $arr_priv = array(3 => 'Пользователь', 1 => 'Администратор', 2 => 'Редактор');

  $login = new FieldText("login", "Логин", $_REQUEST['login'], true);
  $email = new FieldEmail("email", "Почта", $_REQUEST['email'], true);
  $yearborn = new FieldDate("yearborn", "Дата рождения", $_REQUEST['yearborn'], false);
  $about = new FieldTextarea("about", "О себе", $_REQUEST['about'], false);
  $privilege = new FieldSelect("privilege", "Статус", $_REQUEST['privilege'], $arr_priv);
  $id_users = new FieldHiddenInt("id_users",  intval($_GET['id_users']), true);
  $page = new FieldHiddenInt("page",  $_REQUEST['page'], false);
  $form = new Form(array("login" => $login, "email" => $email,
  "yearborn" => $yearborn, "privilege" => $privilege, "about" => $about, "page" => $page, "id_users" => $id_users), "Изменить");

  if (!empty($_POST)) {
    $errorform = $form->check();
    $error = array();
    if (!validateLogin($form->fields['login']->get_value())) {
      $error[] = "Логин может состоять только из букв английского алфавита и цифр.";
    }
    if (strlen($form->fields['login']->get_value()) < 3 or strlen($form->fields['login']->get_value()) > 34) {
      $error[] = "Логин должен быть не меньше 3-х символов и не больше 30.";
    }

    if (empty($errorform) && empty($error)) {
      $sql = "UPDATE $tbl_users SET login = ?, email = ?, yearborn = ?,  about = ?, privilege = ? WHERE id_users = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $form->fields['login']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(2, $form->fields['email']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(3, $form->fields['yearborn']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(4, $form->fields['about']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(5, $form->fields['privilege']->get_value(), PDO::PARAM_INT);
      $stmt->bindValue(6, $form->fields['id_users']->get_value(), PDO::PARAM_INT);
      if ($stmt->execute()) {
        exit("<meta http-equiv='refresh' content='0; url=indexusers.php?page={$form->fields[page]->get_value()}'>");
      }
    }
  }

  require_once '../utils/head.php';
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
  if (!empty($errorform)) {
    foreach ($errorform as $err) {
      echo "<span style=\"color:red\">$err</span><br>";
    }
  }
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
