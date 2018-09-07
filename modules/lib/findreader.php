<?php
require_once '../../configs/classes.config.modules.php';
require_once '../templates/head.php';
try {
  if (!empty($_GET['id_catalog'])) {
    $_REQUEST['id_catalog'] = intval($_GET['id_catalog']);
  }

  $readertarget = new FieldText("readertarget", "Читатель", "", true);
  $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], false);
  $form = new Form(array("readertarget" => $readertarget, "id_catalog" => $id_catalog), "Перейти", "GET", "readercard.php");

  echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
  echo "<div class='ui-widget'>";
  $form->print_form();
  echo "</div>";
} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

require_once '../templates/bottom.php';
?>
<script type="text/javascript" src="js/findreader.js"></script>
<script type="text/javascript" src="../../js/jqueryUI-1.11.4.min.js"></script>
