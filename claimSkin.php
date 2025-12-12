<?php
include "config.php";
switch ($_GET['skinID']) {
    case 1:
        if (date('m')==12) {
            $connect->execute_query("UPDATE `accounts` SET `christmasSkinOwned`=1 WHERE `id`=?",[$_COOKIE['user']]);
            setcookie("colourscheme","christmas",time()+60*60*24*365);
            header("Location: uploadui.php?miscNotif=christmasSkinClaimed");
        }
        break;
}

?>