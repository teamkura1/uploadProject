<a href='uploadui.php' >Back</a><br>
<?php
include "settings.php";
include "lib.php";
$data = $connect->execute_query("SELECT `name`,`goldenFile` FROM `files` WHERE `tag` = ? ORDER BY `goldenFile` DESC",[urldecode($_GET['tag'])]);
if ($data->num_rows == 0) {
    header("Location: uploadui.php?error=noitems");
}
$itemCount = $connect->execute_query("SELECT COUNT(*) FROM FILES WHERE `tag`= ?",[$_GET['tag']]);
echo "<fieldset><legend>Files With Tag <b>".htmlspecialchars($_GET['tag'])."</b>, <b>".$itemCount->fetch_row()[0]."</b> Items Found <a href='gallery.php?tag=".htmlspecialchars($_GET['tag'])."'>Gallery</a></legend>";
displayList($data);
?>