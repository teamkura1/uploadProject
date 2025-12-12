<?php
include "config.php";
if ($_COOKIE['devTool']!=$devToolString) {
   header("Location: uploadui.php?error=no.");
   die();
}
echo "<a href='uploadui.php' >Back</a><br>";
include "settings.php";
$name = urldecode($_GET['file']);
echo "<b>{$name}</b> what would you like to do with this file?<br><a href='devTool.php?filename={$name}&action=delete'>Delete</a><br><a href='devTool.php?filename={$name}&action=setGolden'>Set ";
   switch ($connect->execute_query("SELECT `goldenFile` FROM `files` WHERE `name`=?",[$name])->fetch_row()[0]) {
      case 2:
         echo "Normal";
         break;
      case 1:
        echo "Diamond";
        break;
    case 0:
        echo "Golden";
        break;
   };
echo" File</a><form action='devTool.php' method='get'><input type='text' name='tag' placeholder='tag name'><input type='submit' value='Change tag'><input name='filename' hidden value='{$name}'></form><form action='devTool.php' method='get'><input name='filename' hidden value='{$name}'><input type='text' name='filenewname' placeholder='new name'><input type='submit' value='Change name'></form>";
?>