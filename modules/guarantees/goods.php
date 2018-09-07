<?php
require_once '../templates/head.php';
require_once '../../configs/classes.config.php';

try {
  if (USER_LOGGED) {
    if (!empty($_GET['id_catalog'])) {
      $_REQUEST['id_catalog'] = intval($_GET['id_catalog']);
    }
    if (empty($_REQUEST['sort'])) {
      $_REQUEST['sort'] = '';
    }
    if (empty($_REQUEST['order'])) {
      $_REQUEST['order'] = '';
    }
    if (empty($_POST['search_val'])) {
      $_POST['search_val'] = '';
    }
    if (empty($_REQUEST['search'])) {
      $_REQUEST['search'] = '';
    }
    if (empty($_REQUEST['id_user'])) {
      $_REQUEST['id_user'] = $UserID;
    }

    $arr_sort = array(1 => 'По дате добавления', 2 => 'В порядке добавления', 3 => 'По дате начала гарантии',
    4 => 'По имени', 5 => 'По цене', 6 => 'По гарантии', 7 => 'По номеру',
    8 => 'По магазину', 9 => 'По производителю', 10 => 'По количеству');
    $arr_order = array(1 => 'В порядке убывания', 2 => 'В порядке возрастания');

    $sort = new FieldSelect("sort", "Сортировка", $_REQUEST['sort'], $arr_sort);
    $order = new FieldSelect("order", "Порядок", $_REQUEST['order'], $arr_order);
    $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], false);
    $id_user = new FieldHiddenInt("id_user",  $_REQUEST['id_user'], false);
    $form_sort = new Form(array("sort" => $sort, "order" => $order, "id_catalog" => $id_catalog, "id_user" => $id_user),
    "Применить", "sort_btn", "form_sort");

    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";

    $form_sort->print_form();

    $arr_search = array(1 => 'Наименование', 2 => 'Номер', 3 => 'Дата(часть)',
    4 => 'Точная дата (дд.мм.гггг)');

    $search_val = new FieldText("search_val", "Поиск", $_POST['search_val'], false);
    $search = new FieldSelect("search", "Поле поиска", $_REQUEST['search'], $arr_search);
    $id_catalog = new FieldHiddenInt("id_catalog",  $_REQUEST['id_catalog'], false);
    $form_search = new Form(array("search_val" => $search_val, "search" => $search,
    "id_catalog" => $id_catalog), "Найти", "search_btn", "form_search");

    $form_search->print_form();

    ?>
    <div id="content">

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
<script type="text/javascript" src="js/goods.js"></script>
