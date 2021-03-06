<?php require_once '../templates/head.php';
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';

function validateLogin($value){
    return preg_match( '/^[a-z\d]{3,34}$/i', trim($value));
}

try {
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";

  $nowdate = date('Y-m-d');

  $login = new FieldText("login", "Логин", $_REQUEST['login'], true);
  $mail = new FieldEmail("mail", "Почта", $_REQUEST['mail'], true);
  $pass = new FieldPassword("pass", "Пароль", $_REQUEST['pass'], true);
  $passconform = new FieldPassword("passconform", "Пароль повтор", $_REQUEST['passconform'], true);
  $yearborn = new FieldDate("yearborn", "Дата рождения", $_REQUEST['yearborn'], false);
  $about = new FieldTextarea("about", "О себе", $_REQUEST['about'], false);
  $dateofregister = new FieldHiddenInt("dateofregister",  $nowdate, true);
  $form = new Form(array("login" => $login, "mail" => $mail, "pass" => $pass, "passconform" => $passconform,
  "yearborn" => $yearborn, "about" => $about, "dateofregister" => $dateofregister), "Зарегистрироваться");

  if (!empty($_POST)) {
    $errorform = $form->check();
    $error = array();
    if (!validateLogin($form->fields['login']->get_value())) {
      $error[] = "Логин может состоять только из букв английского алфавита и цифр.";
    }
    if (strlen($form->fields['login']->get_value()) < 3 or strlen($form->fields['login']->get_value()) > 34) {
      $error[] = "Логин должен быть не меньше 3-х символов и не больше 30.";
    }
    if ($form->fields['pass']->get_value() == $form->fields['passconform']->get_value()) {
      if (strlen($form->fields['pass']->get_value()) < 5 or strlen($form->fields['pass']->get_value()) > 30) {
        $error[] = "Пароль должен быть не меньше 5-х символов и не больше 30.";
      }
    } else {
      $error[] = "Пароли не совпадают.";
    }
    $stmt = $pdo->prepare("SELECT COUNT(id_users) FROM $tbl_users WHERE login = ?");
    $stmt->execute([$form->fields['login']->get_value()]);
    if ($stmt->fetchColumn() > 0) {
      $error[] = "Пользователь с таким логином уже существует в базе данных.";
    }
    $stmt = $pdo->prepare("SELECT COUNT(email) FROM $tbl_users WHERE email = ?");
    $stmt->execute([$form->fields['mail']->get_value()]);
    if ($stmt->fetchColumn() > 0) {
      $error[] = "Пользователь с такой почтой уже существует в базе данных.";
    }

    if (empty($errorform) && empty($error)) {
      $password = md5(md5(trim($form->fields['pass']->get_value())));
      $defprivil = 3;
      $blockdef = "block";
      $sql = "INSERT INTO $tbl_users SET login = ?, pass = ?, email = ?, yearborn = ?,  about = ?, dateofregister = ?, privilege = ?, block = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $form->fields['login']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(2, $password, PDO::PARAM_STR);
      $stmt->bindValue(3, $form->fields['mail']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(4, $form->fields['yearborn']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(5, $form->fields['about']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(6, $form->fields['dateofregister']->get_value(), PDO::PARAM_STR);
      $stmt->bindValue(7, $defprivil, PDO::PARAM_INT);
      $stmt->bindValue(8, $blockdef, PDO::PARAM_STR);
      if ($stmt->execute()) {
        exit("<meta http-equiv='refresh' content='0; url=index.php'>");
      }
    }
  }
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

} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

?>
<?php require_once '../templates/bottom.php'; ?>
