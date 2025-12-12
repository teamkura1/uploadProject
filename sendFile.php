<?php
include "config.php";
if ($connect->execute_query("SELECT isModerator FROM accounts WHERE id=?",[$_COOKIE['user']])->fetch_row()[0]) {
    $connect->execute_query("INSERT INTO sends (`file`,`sentFor`) VALUES(?,?)",[$_GET['filename'],$_GET['sendFor']]);
    header("Location: findui.php?filename=".$_GET['filename']);
} else {
    header("Location: uploadui.php?error=no.");
    die();
}
?>