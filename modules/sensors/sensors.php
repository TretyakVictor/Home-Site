<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
try {
  if (USER_LOGGED) {

    ?>
    <div id="content1" style="height: 300px">

    </div>
    <div id="content2" style="height: 300px">

    </div>
    <div id="content3" style="height: 300px">

    </div>
    <div id="content4" style="height: 300px">

    </div>
    <?php
  } else {
    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
    echo "Необходимо войти, что бы просматривать эту страницу.";
  }

} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

require_once '../templates/bottom.php';
?>
<script type="text/javascript" src="js/sensors.js"></script>
<script src="/js/jquery.canvasjs.min.js"></script>
