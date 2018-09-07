<?php
$dir = opendir("..");
$idCount = 1;
while (($file = readdir($dir)) !== false) {
  if (is_dir("../$file")) {
    if ($UserPrivilege == 1) {
      if ($file != "." && $file != ".." && $file != "utils" && $file != "system_main") {
        if (file_exists("../$file/.descr")) {
          $file_handle = fopen("../$file/.descr", "r");
          $i = 0;
          while (!feof($file_handle)) {
            $nameAray[$i] = fgets($file_handle);
            ++$i;
          }
          $block_name = $nameAray[0];
          fclose($file_handle);
          unset($nameAray[count($nameAray)-1]);
        }else {
          $block_name = "$file";
        }

        echo "<li class='navSecondBar'><a role='button' id='menuBlockBtn{$idCount}' data-toggle='collapse' href='#menuBlock{$idCount}' aria-controls='menuBlock{$idCount}'>$block_name</a></li>";

        if ($handle = opendir("../$file")) {
          $i = 0;
          while (($files = readdir($handle)) !== false) {
            if (is_file("../$file/$files") && $files != ".descr" && stristr($files, 'scr_') === false && stristr($files, 'TMP') === false) {
              $filesAray[$i] = $files;
              ++$i;
            }
          }
          closedir($handle);
        }

        if (isset($nameAray) && isset($filesAray)) {
          $namebtn = "menuBlockBtn{$idCount}";
          if (isset($_COOKIE[$namebtn])) {
            echo "<div class='collapse in' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          } else {
            echo "<div class='collapse' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          }
          $kfArr = count($filesAray);
          if (count($nameAray) > $kfArr) {
            $i = 1;
            foreach ($filesAray as $value) {
              echo "<li><a href='../$file/$value'>$nameAray[$i]</a></li>";
              ++$i;
            }
          }else {
            $i = 0;
            for ($j=1; $j < count($nameAray); $j++) {
              echo "<li><a href='../$file/$filesAray[$i]'>$nameAray[$j]</a></li>";
              ++$i;
            }
            if ($kfArr > $i) {
              for ($j=$i; $j < $kfArr; $j++) {
                echo "<li><a href='../$file/$filesAray[$j]'>$filesAray[$j]</a></li>";
              }
            }
          }
          echo "</ul></div>";
          unset($filesAray);
          unset($nameAray);
        }else {
          if ($_COOKIE[$namebtn]) {
            echo "<div class='collapse in' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          } else {
            echo "<div class='collapse' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          }
          if (isset($filesAray)) {
            foreach ($filesAray as $value) {
              echo "<li><a href='../$file/$value'>$block_name</a></li>";
            }
          }else {
            echo "<li><a href=''>Файлы не найдены</a></li>";
          }
          echo "</ul></div>";
          unset($filesAray);
        }
      }
    } elseif ($UserPrivilege == 2) {
      if ($file != "." && $file != ".." && $file != "utils" && $file != "system_main") {
        if (file_exists("../$file/.descr")) {
          $file_handle = fopen("../$file/.descr", "r");
          $i = 0;
          while (!feof($file_handle)) {
            $nameAray[$i] = fgets($file_handle);
            ++$i;
          }
          $block_name = $nameAray[0];
          fclose($file_handle);
          unset($nameAray[count($nameAray)-1]);
        }else {
          $block_name = "$file";
        }

        echo "<li class='navSecondBar'><a role='button' id='menuBlockBtn{$idCount}' data-toggle='collapse' href='#menuBlock{$idCount}' aria-controls='menuBlock{$idCount}'>$block_name</a></li>";

        if ($handle = opendir("../$file")) {
          $i = 0;
          while (($files = readdir($handle)) !== false) {
            if (is_file("../$file/$files") && $files != ".descr" && stristr($files, 'scr_') === false && stristr($files, 'TMP') === false) {
              $filesAray[$i] = $files;
              ++$i;
            }
          }
          closedir($handle);
        }

        if (isset($nameAray) && isset($filesAray)) {
          $namebtn = "menuBlockBtn{$idCount}";
          if ($_COOKIE[$namebtn]) {
            echo "<div class='collapse in' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          } else {
            echo "<div class='collapse' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          }
          $kfArr = count($filesAray);
          if (count($nameAray) > $kfArr) {
            $i = 1;
            foreach ($filesAray as $value) {
              echo "<li><a href='../$file/$value'>$nameAray[$i]</a></li>";
              ++$i;
            }
          }else {
            $i = 0;
            for ($j=1; $j < count($nameAray); $j++) {
              echo "<li><a href='../$file/$filesAray[$i]'>$nameAray[$j]</a></li>";
              ++$i;
            }
            if ($kfArr > $i) {
              for ($j=$i; $j < $kfArr; $j++) {
                echo "<li><a href='../$file/$filesAray[$j]'>$filesAray[$j]</a></li>";
              }
            }
          }
          echo "</ul></div>";
          unset($filesAray);
          unset($nameAray);
        }else {
          if ($_COOKIE[$namebtn]) {
            echo "<div class='collapse in' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          } else {
            echo "<div class='collapse' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          }
          if (isset($filesAray)) {
            foreach ($filesAray as $value) {
              echo "<li><a href='../$file/$value'>$block_name</a></li>";
            }
          }else {
            echo "<li><a href=''>Файлы не найдены</a></li>";
          }
          echo "</ul></div>";
          unset($filesAray);
        }
      }
    }
  }
  ++$idCount;
}
closedir($dir);
?>
