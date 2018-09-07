<?php
if (USER_LOGGED) {
  if (!check_user($UserID)) {
    logout();
  }
}else {
  include_once('login.php');
} ?>
