<?php
include_once '../../configs/classes.config.cms.php';
include_once '../../configs/mysql.config.cms.php';

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
