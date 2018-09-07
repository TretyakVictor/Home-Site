<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.php';

try {
  if (!empty($_GET['id_catalog'])) {
    $_REQUEST['id_catalog'] = intval($_GET['id_catalog']);
  }

  $now = date('Y');

  $datein = new FieldINT("datein", "Начальная дата", ($now-2), false, ($now-30), $now);
  $dateout = new FieldINT("dateout", "Конечная дата", $now, false, ($now-30), $now);
  $id_catalog = new FieldHiddenInt("id_catalog",  date('Y'-2), false);
  $form = new Form(array("datein" => $datein, "dateout" => $dateout, "id_catalog" => $id_catalog),
  "Применить");

  $form->print_form();

  ?>
  <div id="content">

  </div>
  <?php


} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

require_once '../templates/bottom.php';
?>
<script type="text/javascript" src="js/books_sph.js"></script>
<script type="text/javascript" src="../../js/jqueryUI-1.11.4.min.js"></script>
