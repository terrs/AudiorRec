<?php
 ///phpinfo();
if(empty($_FILES)) {
echo "file empty";
}
foreach($_FILES as $file){
  echo "Upload: " . $file["name"] . "<br />";
  echo "Type: " . $file["type"] . "<br />";
  echo "Size: " . ($file["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $file["tmp_name"];
  move_uploaded_file($file["tmp_name"], "/var/www/upload/" . $file["name"]);
}
?>
