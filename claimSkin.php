<?php
// Special skin claiming
include "config.php";
switch ($_GET['skinID']) {
    case 1:
        if (date('m')==12) { // if thats true then merry christmas
            $connect->execute_query("UPDATE `accounts` SET `christmasSkinOwned`=1 WHERE `id`=?",[$_COOKIE['user']]); //give the user the skin
            setcookie("colourscheme","christmas",time()+60*60*24*365);
            header("Location: uploadui.php?miscNotif=christmasSkinClaimed");
        }
        break;
}

?>