<?php
require_once '../../classes/class.db.php';
require_once '../../configs/mysql.config.php';

session_start();
define("SID", session_id());

function generateCode($length=6) {
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
  $code = "";
  $clen = strlen($chars) - 1;
  while (strlen($code) < $length) {
    $code .= $chars[mt_rand(0,$clen)];
  }
  return $code;
}

function logout() {
  unset($_SESSION['id_users']);
  die(header('Location: '.$_SERVER['HTTP_REFERER']));
}

function check_user($iduser) {
  global $pdo, $tbl_users;

  $stmt = $pdo->prepare("SELECT *, INET_NTOA(ip) FROM $tbl_users WHERE `id_users` = ? LIMIT 1");
  $stmt->execute([$iduser]);

  $stmt = $stmt->fetchAll();
  $userdata = $stmt[0];
  if (($userdata['hash'] == $_SESSION['hash']) AND ($userdata['id_users'] == $_SESSION['id_users'])) {
    return $userdata['sid'] == SID ? true : false;
  }else {
    return false;
  }
}

function login($username, $password)
{
  global $pdo, $tbl_users;
  $stmt = $pdo->prepare("SELECT * FROM $tbl_users WHERE login = ? LIMIT 1");
  $stmt->execute([$username]);
  $stmt = $stmt->fetchAll();
  $data = $stmt[0];

  if ($data['pass'] === md5(md5(trim($password))) && $data['block'] != 'block' && $data['activation'] != 'deactivate') {
    $hash = md5(generateCode(10));
    $ip = false;
    $data['hash'] = $hash;
    $_SESSION = array_merge($_SESSION, $data);

    if ($ip) {
      $nowdateandtime = date('Y-m-d G:i:s');
      $sql = "UPDATE $tbl_users SET `lastvisit` = ?, `sid` = ?, hash = ? ? WHERE id_users = ? LIMIT 1";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $nowdateandtime, PDO::PARAM_STR);
      $stmt->bindValue(2, SID, PDO::PARAM_STR);
      $stmt->bindValue(3, $hash, PDO::PARAM_STR);
      $stmt->bindValue(4, $insip, PDO::PARAM_STR);
      $stmt->bindValue(5, $data['id_users'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        return true;
      } else {
        return false;
      }

    }else {
      $nowdateandtime = date('Y-m-d G:i:s');
      $sql = "UPDATE $tbl_users SET `lastvisit` = ?, `sid` = ?, hash = ? WHERE id_users = ? LIMIT 1";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $nowdateandtime, PDO::PARAM_STR);
      $stmt->bindValue(2, SID, PDO::PARAM_STR);
      $stmt->bindValue(3, $hash, PDO::PARAM_STR);
      $stmt->bindValue(4, $data['id_users'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        return true;
      } else {
        return false;
      }

    }
    return true;
  }else {
    return false;
  }
}


if (isset($_POST['submit_auth'])) {
  if (get_magic_quotes_gpc()) {
    $_POST['login'] = stripslashes($_POST['login']);
    $_POST['password'] = stripslashes($_POST['password']);
  }
  $refer = $_SERVER['HTTP_REFERER'];
  if (login($_POST['login'], $_POST['password'])) {
    header("refresh:2; url=$refer");
    require_once 'head_auth.php';
    echo "Вы успешно авторизировались!";
    require_once 'bottom_auth.php';
    die();
  }else {
    header("refresh:2; url=$refer");
    require_once 'head_auth.php';
    echo "Авторизация не удалась!";
    require_once 'bottom_auth.php';
    die();
  }
}


if(isset($_SESSION['id_users'])) {
  define('USER_LOGGED', true);
  $UserName = $_SESSION['login'];
  $UserPass = $_SESSION['pass'];
  $UserID = $_SESSION['id_users'];
  $UserHash = $_SESSION['hash'];
  $UserPrivilege = $_SESSION['privilege'];
} else {
  define('USER_LOGGED', false);
}

if(isset($_GET['logout'])) {
  logout();
}

?>
