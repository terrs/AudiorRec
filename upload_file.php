<?php
 ///phpinfo();
  echo "Upload: " . $_FILES["testfile"]["name"] . "<br />";
  echo "Type: " . $_FILES["testfile"]["type"] . "<br />";
  echo "Size: " . ($_FILES["testfile"]["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $_FILES["testfile"]["tmp_name"];
?>
