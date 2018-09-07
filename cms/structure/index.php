<?php
require_once '../utils/head.php';
echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
if ($UserPrivilege == 1) {
  require_once '../utils/print_page.php';
  require_once '../utils/navigation.php';

  try {
    if (empty($_GET['page'])) {
      $_GET['page'] = 1;
    }
    if (empty($_GET['id_parent'])) {
      $_GET['id_parent'] = 0;
    } else {
      $_GET['id_parent'] = intval($_GET['id_parent']);
    }

    echo '<table class="table-hover" cellspacing="0" border=0>
      <tr>
        <td>
          <a href=index.php?id_parent=0>Корневое меню</a>-&gt;'.menu_navigation($_GET['id_parent'], "", $tbl_catalog).
          '<a href=catadd.php?id_parent='.$_GET['id_parent'].'&page='.intval($_GET['page']).'>Добавить меню</a>
        </td>
      </tr>
    </table>';
    $ctg = $pdo->prepare("SELECT * FROM $tbl_catalog WHERE id_parent=$_GET[id_parent] ORDER BY pos");
    $ctg->execute([$_GET['id_parent']]);
    if ($ctg->rowCount() > 0) {
      echo '<table class="table-hover table-striped text-center">
        <caption>
          '.$_GET['id_parent'].' Уровень меню
        </caption>
        <tr>
          <th>Название</th>
          <th>Описание</th>
          <th>Позиция</th>
          <th>Действия</th>
        </tr>';
      $i = 0;
      while ($catalog = $ctg->fetch()) {
        $url = "id_catalog=$catalog[id_catalog]&"."id_parent=$catalog[id_parent]&"."page=$_GET[page]";
        if ($catalog['hide'] == 'hide') {
          $strhide = "<a href=scr_catshow.php?$url><span class='glyphicon glyphicon-eye-open'></span></a>";
          $class = "bad";
        }else {
          $strhide = "<a href=scr_cathide.php?$url><span class='glyphicon glyphicon-eye-close'></span></a>";
          $class = "";
        }
        ++$i;
        echo "<tr $class>
                <td class='text-left'>
                  <a href=index.php?id_parent=$catalog[id_catalog]>$catalog[name]</a>
                </td>
                <td>".nl2br(print_page($catalog['description']))."&nbsp;</td>
                <td>$i</td>
                <td class='text-center'>
                  <a href=scr_catup.php?$url><span class='glyphicon glyphicon-chevron-up'></span></a><br>
                  $strhide<br>
                  <a href=scr_catedit.php?$url><span class='glyphicon glyphicon-edit'></span></a><br>
                  <a href=# onClick=\"delete_position('scr_catdel.php?$url',"."'Вы действительно хотите удалить раздел?');\"><span class='glyphicon glyphicon-trash'></span></a><br>
                  <a href=scr_catdown.php?$url><span class='glyphicon glyphicon-chevron-down'></span></a><br>
                </td>
              </tr>";
      }
      echo "</table>";
    }
    if (isset($_GET['id_parent']) && $_GET['id_parent'] != 0) {
      require_once 'scr_position.php';
    }


  } catch (ExceptionMySQL $e) {
  }
}
require_once '../utils/bottom.php';
?>
