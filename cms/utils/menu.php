<?php

function getBlocksName($file, &$nameArray) {
  if (file_exists("../$file/.descr")) {
    $file_handle = fopen("../$file/.descr", "r");
    $i = 0;
    while (!feof($file_handle)) {
      $nameArray[$i] = fgets($file_handle);
      ++$i;
    }
    $block_name = $nameArray[0];
    fclose($file_handle);
    unset($nameArray[count($nameArray) - 1]);
  } else {
    $block_name = "$file";
  }
  return $block_name;
}

function getFiles($file) {
  if ($handle = opendir("../$file")) {
    $i = 0;
    while (($files = readdir($handle)) !== false) {
      if (is_file("../$file/$files") && $files != ".descr" && stristr($files, 'scr_') === false && stristr($files, 'TMP') === false) {
        $filesArray[$i] = $files;
        ++$i;
      }
    }
    closedir($handle);
    return $filesArray;
  }
}

$dir = opendir("..");
$idCount = 1;
$nameArray = [];
while (($file = readdir($dir)) !== false) {
  if (is_dir("../$file")) {
    if ($UserPrivilege == 1) {
      if ($file != "." && $file != ".." && $file != "utils" && $file != "system_main") {

        $block_name = getBlocksName($file, $nameArray);

        echo "<li class='navSecondBar'><a role='button' id='menuBlockBtn{$idCount}' data-toggle='collapse' href='#menuBlock{$idCount}' aria-controls='menuBlock{$idCount}'>$block_name</a></li>";

        $filesArray = getFiles($file);

        if (isset($nameArray) && isset($filesArray)) {
          $namebtn = "menuBlockBtn{$idCount}";
          if (isset($_COOKIE[$namebtn])) {
            echo "<div class='collapse in' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          } else {
            echo "<div class='collapse' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          }
          $kfArr = count($filesArray);
          if (count($nameArray) > $kfArr) {
            $i = 1;
            foreach ($filesArray as $value) {
              echo "<li><a href='../$file/$value'>$nameArray[$i]</a></li>";
              ++$i;
            }
          } else {
            $i = 0;
            for ($j = 1; $j < count($nameArray); $j++) {
              echo "<li><a href='../$file/$filesArray[$i]'>$nameArray[$j]</a></li>";
              ++$i;
            }
            if ($kfArr > $i) {
              for ($j = $i; $j < $kfArr; $j++) {
                echo "<li><a href='../$file/$filesArray[$j]'>$filesArray[$j]</a></li>";
              }
            }
          }
          echo "</ul></div>";
          unset($filesArray);
          unset($nameArray);
        } else {
          if ($_COOKIE[$namebtn]) {
            echo "<div class='collapse in' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          } else {
            echo "<div class='collapse' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          }
          if (isset($filesArray)) {
            foreach ($filesArray as $value) {
              echo "<li><a href='../$file/$value'>$block_name</a></li>";
            }
          } else {
            echo "<li><a href=''>Файлы не найдены</a></li>";
          }
          echo "</ul></div>";
          unset($filesArray);
        }
      }
    } elseif ($UserPrivilege == 2) {
      if ($file != "." && $file != ".." && $file != "utils" && $file != "system_main") {

        $block_name = getDirName($file, $nameArray);

        echo "<li class='navSecondBar'><a role='button' id='menuBlockBtn{$idCount}' data-toggle='collapse' href='#menuBlock{$idCount}' aria-controls='menuBlock{$idCount}'>$block_name</a></li>";

        $filesArray = getFiles($file);

        if (isset($nameArray) && isset($filesArray)) {
          $namebtn = "menuBlockBtn{$idCount}";
          if ($_COOKIE[$namebtn]) {
            echo "<div class='collapse in' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          } else {
            echo "<div class='collapse' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          }
          $kfArr = count($filesArray);
          if (count($nameArray) > $kfArr) {
            $i = 1;
            foreach ($filesArray as $value) {
              echo "<li><a href='../$file/$value'>$nameArray[$i]</a></li>";
              ++$i;
            }
          } else {
            $i = 0;
            for ($j = 1; $j < count($nameArray); $j++) {
              echo "<li><a href='../$file/$filesArray[$i]'>$nameArray[$j]</a></li>";
              ++$i;
            }
            if ($kfArr > $i) {
              for ($j = $i; $j < $kfArr; $j++) {
                echo "<li><a href='../$file/$filesArray[$j]'>$filesArray[$j]</a></li>";
              }
            }
          }
          echo "</ul></div>";
          unset($filesArray);
          unset($nameArray);
        } else {
          if ($_COOKIE[$namebtn]) {
            echo "<div class='collapse in' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          } else {
            echo "<div class='collapse' id='menuBlock{$idCount}'><ul class='nav nav-pills nav-stacked navIn'>";
          }
          if (isset($filesArray)) {
            foreach ($filesArray as $value) {
              echo "<li><a href='../$file/$value'>$block_name</a></li>";
            }
          } else {
            echo "<li><a href=''>Файлы не найдены</a></li>";
          }
          echo "</ul></div>";
          unset($filesArray);
        }
      }
    }
  }
  ++$idCount;
}
closedir($dir);
?>
