<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.php';

try {
  if (!empty($_GET['id_catalog'])) {
    $_REQUEST['id_catalog'] = intval($_GET['id_catalog']);
  }

  $select = array(1 => 'Текущий месяяц', 2 => 'Текущий год', 3 => 'Прошлый месяц', 4 => 'Прошлый год');

  $selectrdr = new FieldSelect("selectrdr", "Фильтр", $_REQUEST['selectrdr'], $select);
  $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], false);
  $form = new Form(array("selectrdr" => $selectrdr, "id_catalog" => $id_catalog),
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
<script type="text/javascript" src="js/activity.js"></script>
<script type="text/javascript" src="../../js/jqueryUI-1.11.4.min.js"></script>
