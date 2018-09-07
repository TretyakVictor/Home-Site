<?php
require_once "../utils/head.php";

try {
  echo "<div class='well'>
  <h1>Административная панель</h1>
  Здесь осуществляется управление содержимым сайта.
  </div>";
    ?>
    <?php
} catch (ExceptionMySQL $e) {
  require("../utils/exception_mysql.php");
}
require_once "../utils/bottom.php";
?>
