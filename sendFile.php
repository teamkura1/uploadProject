<?php
include "lib.php";
_log("Checking if user is moderator");
if ($connect->execute_query("SELECT isModerator FROM accounts WHERE id=?",[$_COOKIE['user']])->fetch_row()[0]) {
    _log("Adding send to database");
    $connect->execute_query("INSERT INTO sends (`file`,`sentFor`) VALUES(?,?)",[$_GET['filename'],$_GET['sendFor']]);
    header("Location: findui.php?filename=".$_GET['filename']);
} else {
    header("Location: uploadui.php?error=no.");
    die();
}
?>