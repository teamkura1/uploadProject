<?php
header("Access-Control-Allow-Origin: *");
header("Accept-Ranges: bytes");
header("X-Content-Type-Options: nosniff");
include "lib.php";
_log("Getting mime type");
$getData = $connect->execute_query("SELECT mtype FROM `files` WHERE `name`=?",[explode(".",$_GET['filename'])[0]]);
$data = $getData->fetch_row();
_log("Adding view");
$connect->execute_query("INSERT INTO `views` (`filename`) VALUES (?)",[explode(".",$_GET['filename'])[0]]);
if (!$data) {
    header("Location: uploadui.php?error=notfound");
    die();
}

if ($_GET['download']) {
header("Content-Disposition: attachment; filename={$_GET['filename']}");
}
header("Content-Type: ".$data[0]);
echo(file_get_contents("files/".basename(urldecode(explode(".",$_GET['filename'])[0]))));
?>