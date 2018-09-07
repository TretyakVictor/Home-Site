<table>
  <tr>
    <td>
      <?php
      echo "<a href=scr_urladd.php?id_parent=$_GET[id_parent]&page=$_GET[page]
        title=\"Добавить ссылку на страницу текущего или любого друго сайта\">
         Добавить ссылку</a>&nbsp;&nbsp;&nbsp;
        <a href=scr_artadd.php?id_parent=$_GET[id_parent]&page=$_GET[page]
          title=\"Добавить статью в данный раздел\">
          Добавить статью</a>";
       ?>
    </td>
  </tr>
</table><br>
<?php
try {
  $page_link = 3;
  $page_number = 5;
  $obj = new PagerMySQL($pdo, $tbl_position, "WHERE id_catalog=$_GET[id_parent]", "ORDER BY pos", $page_number, $page_link, "&id_parent=$_GET[id_parent]");
  $position = $obj->get_page();
  if (!empty($position)) {
    ?>
    <table class="table-hover table-striped text-center">
      <tr>
        <th>
          Название
        </th>
        <th>
          URL
        </th>
        <th>
          Позиция
        </th>
        <th>
          Действия
        </th>
      </tr>
    <?php
    $j = 0;
    for ($i=0; $i < count($position); $i++) {
      $url = "id_position={$position[$i][id_position]}&id_catalog=$_GET[id_parent]&page=$_GET[page]";
      if ($position[$i]['hide'] == 'hide') {
        $strhide = "<a href=scr_urlshow.php?$url><span class='glyphicon glyphicon-eye-open'></span></a>";
      }else {
        $strhide = "<a href=scr_urlhide.php?$url><span class='glyphicon glyphicon-eye-close'></span></a>";
      }
      if ($position[$i]['url'] == 'article') {
        $edit = "scr_artedit.php";
        $name  = "<td>
          <a href=scr_paragraph.php?id_position={$position[$i][id_position]}&id_catalog=$_GET[id_parent]>".
          print_page($position[$i]['name'])."</a>
        </td>";
      }else {
        $edit = "scr_urledit.php";
        $name  = "<td>".print_page($position[$i]['name'])."</td>";
      }
      ++$j;
      echo "<tr>$name
        <td>".print_page($position[$i]['url'])."</td>
        <td align=center>$j</td>
        <td class='text-right'>
          <a href=scr_urlup.php?$url><span class='glyphicon glyphicon-chevron-up'></span></a><br>$strhide<br>
          <a href=$edit?$url><span class='glyphicon glyphicon-edit'></span></a><br>
          <a href=# onClick=\"delete_position('scr_urldel.php?$url',".
          "'Вы действительно хотите удалить позицию?');\"><span class='glyphicon glyphicon-trash'></span></a><br>
          <a href=scr_urldown.php?$url><span class='glyphicon glyphicon-chevron-down'></span></a>
        </td>
      </tr>";
    }
    echo "</table><br>";
  }
  echo $obj->print_page();

} catch (ExceptionMySQL $e) {
  // require("../utils/exception_mysql.php");
}
?>
