<?php
if (!$libExists) {
    include "lib.php";
}
if (!$_COOKIE["colourscheme"]) {
    setcookie("colourscheme","cyan",time()+60*60*24*365);
}
_log("Checking if user is valid");
if ($connect->execute_query("SELECT `id` FROM `accounts` WHERE `id`=?",[$_COOKIE["user"]])->num_rows==0) {
    $random = bin2hex(random_bytes(500));
    _log("Creating user");
    $connect->execute_query("INSERT INTO `accounts` (`id`) VALUES (?)",[$random]);
    setcookie("user",$random,time()+60*60*24*365);
}
header("Cache-Control: no-store");
switch ($_COOKIE["colourscheme"]) {
    case "cyan":
        echo "<link rel='stylesheet' href='colours/cyan.css'>";
        break;
    case "orange":
        echo "<link rel='stylesheet' href='colours/orange.css'>";
        break;
    case "dark":
        echo "<link rel='stylesheet' href='colours/dark.css'>";
        break;
    case "green":
        echo "<link rel='stylesheet' href='colours/green.css'>";
        break;
    case "purple":
        echo "<link rel='stylesheet' href='colours/purple.css'>";
        break;
    case "christmas":
            echo "<link rel='stylesheet' href='colours/limitedSkin.php'>";      
        break;
    case "none":
        break;
    default:
        echo "<link rel='stylesheet' href='colours/cyan.css'>";
}
echo "<link rel='stylesheet' href='upload.css'>";
if ($_POST['colour']) {
    if (in_array($_POST['colour'],['cyan','orange','dark','green','purple','none'])) {
    setcookie("colourscheme",$_POST['colour'],time()+60*60*24*365);
    } elseif (in_array($_POST['colour'],['christmas'])) {
        switch ($_POST['colour']) {
            case "christmas":
                _log("Checking if user has christmas skin");
                if ($connect->execute_query("SELECT `christmasSkinOwned` FROM `accounts` WHERE `id`=?",[$_COOKIE['user']])->fetch_row()[0]==1) {
                    setcookie("colourscheme","christmas",time()+60*60*24*365);
                }
        }
    }
    header("Location: settingsui.php");
}
?>