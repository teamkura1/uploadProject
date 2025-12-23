<?php
header("Cache-Control: no-store");
include "config.php";
if ($_COOKIE['devTool']!=$devToolString) {
    header("Location: uploadui.php?error=no.");
    die();
}
if ($_GET['action']!="toggleClosure") {
    include "lib.php";
}
$fname = urldecode($_GET['filename']);
if ($_GET['action']=="delete") {
    _log("Deleting file");
    unlink("files/".basename(urldecode(explode(".",$_GET['filename'])[0])));
    _log("Deleting file metadata");
    $connect->execute_query("DELETE FROM `files` WHERE `name`=?",[$fname]); //yes i dont even trust myself lol
    _log("Removing file from cache");
    $connect->execute_query("DELETE FROM `filesizecache` WHERE `name`=?",[$fname]);
    _log("Removing views");
    $connect->execute_query("DELETE FROM `views` WHERE `filename`=?",[$fname]);
    header("Location: uploadui.php");
}
if ($_GET['tag']) {
    _log("Changing tag of file");
    $connect->execute_query("UPDATE `files` SET `tag`=? WHERE `name`=?",[urldecode($_GET['tag']),$fname]);
    header("Location: uploadui.php");
}
if ($_GET['filenewname']) {
    _log("Changing file name");
    $connect->execute_query("UPDATE `files` SET `name`=? WHERE `name`=?",[urldecode($_GET['filenewname']),$fname]);
    _log("Updating cache");
    $connect->execute_query("UPDATE `filesizecache` SET `name`=? WHERE `name`=?",[urldecode($_GET['filenewname']),$fname]);
    _log("Updating views");
    $connect->execute_query("UPDATE `views` SET `filename`=? WHERE `filename`=?",[urldecode($_GET['filenewname']),$fname]);
    header("Location: uploadui.php");
}
if ($_GET['action']=="setGolden") {
    _log("Changing file rating");
   switch ($connect->execute_query("SELECT `goldenFile` FROM `files` WHERE `name`=?",[$fname])->fetch_row()[0]) {
    case 2:
        _log("Changing to none");
        $connect->execute_query("UPDATE `files` SET `goldenFile`=0 WHERE `name`=?",[$fname]);
        break;
    case 1:
        _log("Changing to diamond");
        $connect->execute_query("UPDATE `files` SET `goldenFile`=2 WHERE `name`=?",[$fname]);
        break;
    case 0:
        _log("Changing to golden");
        $connect->execute_query("UPDATE `files` SET `goldenFile`=1 WHERE `name`=?",[$fname]);
        break;
   };
    header("Location: devToolsMenu.php?file={$fname}");
}
if ($_GET['action']=="toggleClosure") {
    if ($connect->query("SELECT `value` FROM `specialconfig` WHERE `name`='isClosed'")->fetch_row()[0]==1) {
        $connect->query("UPDATE `specialconfig` SET `value`=0 WHERE `name`='isClosed'");
    } else {
        $connect->query("UPDATE `specialconfig` SET `value`=1 WHERE `name`='isClosed'");
    }
    header("Location: uploadui.php");
}
?>