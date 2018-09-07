<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.php';

try {
  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";


} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

require_once '../templates/bottom.php';
?>
