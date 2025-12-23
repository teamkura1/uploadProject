<?php
header("Cache-Control: no-store");
include "lib.php";
$croissant = explode(".",$_FILES['file']['name'])[0];
if ($_POST['renameTo']) {
    $croissant = explode(".",$_POST['renameTo'])[0];
}
$croissant = str_replace(["/","\\"],"",$croissant);
_log("Checking if file already exists");
if ($connect->execute_query("SELECT `name` FROM `files` WHERE `name`=?",[$croissant])->num_rows >= 1) {
    header("Location: uploadui.php?error=duplicate");
    die();
}
move_uploaded_file($_FILES['file']['tmp_name'],"files/".basename($croissant));
if (!file_exists("files/".basename($croissant))) { // check if windows didn't create the file because of an invalid name
    header("Location: uploadui.php?error=invalidname");
    die();
}
$egg = bin2hex(random_bytes(127));
$query = $connect->prepare('INSERT INTO `files` (`id`,`name`,`mtype`,`tag`) VALUES (?, ?, ?, ?)');
$query->bind_param("ssss",$egg,$croissant,$_FILES['file']['type'],$_POST['tag']);
try {
    _log("Uploading metadata to server");
$query->execute();
header("Location: findui.php?filename=".$croissant);
} catch(Exception $e) {
    if (strpos("a".$e->getMessage(),"Duplicate entry")) {
        header("Location: uploadui.php?error=duplicate");
    } else {
        echo $e->getMessage();
    }
}
?>