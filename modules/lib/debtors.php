<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.modules.php';

try {
  if (!empty($_GET['id_catalog'])) {
    $_REQUEST['id_catalog'] = intval($_GET['id_catalog']);
  }

  $select = array(2 => 'Только просроченные', 1 => 'Все', 3 => 'Только текущие');

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
<script type="text/javascript" src="js/debtors.js"></script>
<script type="text/javascript" src="../../js/jqueryUI-1.11.4.min.js"></script>
