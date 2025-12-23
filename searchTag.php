<a href='uploadui.php' >Back</a><br>
<?php
include "lib.php";
include "settings.php";
_log("Getting files with tag");
$data = $connect->execute_query("SELECT `name`,`goldenFile` FROM `files` WHERE `tag` = ? ORDER BY `goldenFile` DESC",[urldecode($_GET['tag'])]);
if ($data->num_rows == 0) {
    header("Location: uploadui.php?error=noitems");
}
echo "<fieldset><legend>Files With Tag <b>".htmlspecialchars($_GET['tag'])."</b>, <b>".$data->num_rows."</b> Items Found <a href='gallery.php?tag=".htmlspecialchars($_GET['tag'])."'>Gallery</a></legend>";
displayList($data);
?>