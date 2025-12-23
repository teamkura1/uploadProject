<?php
header("Access-Control-Allow-Origin: *");
$noRedirect=true;
include "lib.php";
if (isset($_GET['stats'])) {
    header("Content-Type: application/xml");
    _log("Querying isClosed");
    if ($connect->query("SELECT `value` FROM `specialconfig` WHERE `name`='isClosed'")->fetch_row()[0]==1) {
    echo"<stats><freeSpace>0</freeSpace><spaceUsed>0</spaceUsed><files>0</files></stats>";
    die();
}
    echo "<stats>";
    echo "<freeSpace>";
    echo disk_free_space($datadrive);
    echo "</freeSpace>";
    echo "<spaceUsed>";
    _log("Querying total space used by files");
    echo $connect->query("SELECT SUM(`size`) FROM `filesizecache`")->fetch_row()[0];
    echo "</spaceUsed>";
    echo "<files>";
    _log("Querying file count");
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