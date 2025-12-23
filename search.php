<a href='uploadui.php' >Back</a><br>
<?php
include "lib.php";
include "settings.php";
_log("Searching files");
$data = $connect->execute_query("SELECT `name`,`goldenFile` FROM `files`  WHERE `name` LIKE CONCAT('%',?,'%') ORDER BY `goldenFile` DESC",[$_GET['searchQuery']]);
if ($data->num_rows == 0) {
    header("Location: uploadui.php?error=noitems");
}
echo "<fieldset><legend>All files with names containing <b>".htmlspecialchars($_GET['searchQuery'])."</b>, <b>".$data->num_rows."</b> items found</legend>";
displayList($data);
?>