<?php
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';

try {
  $flag = false; $main = false; $catflag = false;
  if (empty($_GET['id_catalog']) && empty($_GET['id_position'])) {
    $query = "SELECT * FROM $tbl_catalog WHERE hide = 'show' AND id_parent = 0 ORDER BY pos";
    $flag = true;
  }elseif (!empty($_GET['id_catalog'])) {
    $query = "SELECT * FROM $tbl_catalog WHERE hide = 'show' AND id_parent = $_GET[id_catalog] ORDER BY pos";
    $flag = true;
  }
  if ($flag) {
    $cat = $pdo->query($query);
    if ($cat->rowCount()) {
      $i = -1;
      while ($catalog = $cat->fetch()) {
        $catalogarr[++$i] = $catalog;
        if (submenu($catalog['id_catalog'], $tbl_catalog) > 0 || submenuUrls($catalog['id_catalog'], $tbl_position, true) > 0) {
          $catflag = true;
        }
      }
    } else {
      $main = true;
    }
    if ($catflag) {
      ?>
        <div class="col-xs-12 col-md-3 col-md-push-9 right-sidebar">
          <div class="navbar">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed btnPos" data-toggle="collapse" data-target="#navbar-second-collapse-1">
                <span class="glyphicon glyphicon-th-list"></span>
              </button>
            </div>
            <div class="collapse navbar-collapse navSecondBarCollapse" id="navbar-second-collapse-1">
              <ul class="nav nav-pills nav-stacked">
                <?php
                  try {
                    if ($flag) {
                      for ($i=0; $i < count($catalogarr); $i++) {
                        $catalog = $catalogarr[$i];
                        if (submenu($catalog['id_catalog'], $tbl_catalog) > 0 || submenuUrls($catalog['id_catalog'], $tbl_position, true) > 0) {
                          echo "
                          <li class='navSecondBar'><a role='button' data-toggle='collapse' href='#collapseSubMenu$i' aria-expanded='false' aria-controls='collapseSubMenu$i'>$catalog[name]</a>
                            <div class='collapse' id='collapseSubMenu$i'>
                              <ul class='nav nav-pills nav-stacked navIn'>"
                                .submenu($catalog['id_catalog'], $tbl_catalog, true)."".submenuUrls($catalog['id_catalog'], $tbl_position).
                              "</ul>
                            </div>
                          </li>";
                        }
                      }
                    }

                  } catch (Exception $e) {
                    echo "Error";
                  }
                ?>
              </ul>
            </div>
          </div>
        </div>
        <!-- Контент -->
        <div class="col-xs-12 col-md-9 col-md-pull-3 content link" id="mainContent">
        <?php
    } else {
      $main = true;
    }
  } else {
    $main = true;
  }
  if ($main) {
    ?>
    <!-- Контент -->
    <div class="col-xs-12 col-md-12 content link" id="mainContent">
    <?php
  }

} catch (Exception $e) {

}
?>
