<?php
header("Cache-Control: no-store");
include "config.php";
$croissant = explode(".",$_FILES['file']['name'])[0];
if ($_POST['renameTo']) {
    $croissant = explode(".",$_POST['renameTo'])[0];
}
if ($connect->execute_query("SELECT `name` FROM `files` WHERE `name`=?",[$croissant])->num_rows >= 1) {
    header("Location: uploadui.php?error=duplicate");
    die();
}
move_uploaded_file($_FILES['file']['tmp_name'],"temp/".$_FILES['file']['name']);
$file = file_get_contents("temp/{$_FILES['file']['name']}");
$egg = bin2hex(random_bytes(127));
$query = $connect->prepare('INSERT INTO `files` (`id`,`fileBlob`,`name`,`mtype`,`tag`) VALUES (?, ?, ?, ?, ?)'); // god damn why the fuck did you make this so fucking complicated
$query->bind_param("sssss",$egg,$file,$croissant,$_FILES['file']['type'],$_POST['tag']);
try {
$query->execute();
$connect->execute_query("INSERT INTO `logs` (`action`,`account`) VALUES(CONCAT('Uploaded file ',?),?)",[$croissant,$_COOKIE['user']]);
header("Location: findui.php?filename=".$croissant);
} catch(Exception $e) {
    $connect->execute_query("INSERT INTO `logs` (`action`) VALUES(CONCAT('Failed to upload file ',?))",[$croissant,$_COOKIE['user']]);
    if (strpos("a".$e->getMessage(),"Duplicate entry")) {
        header("Location: uploadui.php?error=duplicate");
    } else {
        echo $e->getMessage();
    }
}
unlink("temp/".$_FILES['file']['name']);
?>