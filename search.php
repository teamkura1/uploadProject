<a href='uploadui.php' >Back</a><br>
<?php
include "settings.php";
include "lib.php";
$data = $connect->execute_query("SELECT `name`,`goldenFile` FROM `files`  WHERE `name` LIKE CONCAT('%',?,'%') ORDER BY `goldenFile` DESC",[$_GET['searchQuery']]);
if ($data->num_rows == 0) {
    header("Location: uploadui.php?error=noitems");
}
$itemCount = $connect->execute_query("SELECT COUNT(*) FROM FILES WHERE `name` LIKE CONCAT('%',?,'%')",[$_GET['searchQuery']]);
echo "<fieldset><legend>All files with names containing <b>".htmlspecialchars($_GET['searchQuery'])."</b>, <b>".$itemCount->fetch_row()[0]."</b> items found</legend>";
displayList($data);
?>