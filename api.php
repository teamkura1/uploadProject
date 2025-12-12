<?php
// Heavily WIP API, should be finished by 1.3.5
header("Access-Control-Allow-Origin: *");
include "config.php";
if (isset($_GET['stats'])) {
    header("Content-Type: application/xml");
    echo "<stats>";
    echo "<freeSpace>";
    echo disk_free_space($datadrive);
    echo "</freeSpace>";
    echo "<spaceUsed>";
    echo $connect->query("SELECT SUM(`size`) FROM `filesizecache`")->fetch_row()[0];
    echo "</spaceUsed>";
    echo "<files>";
    echo $connect->query("SELECT COUNT(*) FROM `files`")->fetch_row()[0];
    echo "</files>";
    echo "</stats>";
}
if (isset($_GET['file'])) {
    header("Location: find.php?filename=".$_GET['file']);
    die();
}
header("HTTP/1.1 200 OK");
?>