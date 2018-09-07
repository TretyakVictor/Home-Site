<?php
// require_once '../utils/head.php';
try {
  $filename = $_GET['img'];
  $size = getimagesize($filename);
  echo "<table>
    <tr>
      <div>
        <img src='".$filename."' border='0' ".$size[3]." >
      </div>
    </tr>
  </table>";

} catch (Exception $e) {
  echo "Error!";
}

// require_once '../utils/bottom.php';
?>
