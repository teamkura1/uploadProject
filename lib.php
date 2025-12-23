<?php
include "config.php";
$libExists = true;
function _log($str) {
    file_put_contents("logs\\".date("Y")."-".date("M")."-".date("j").".txt",date("g").":".date("i").":".date("s").date("A")." ".$_SERVER['PHP_SELF'].": ".$str."\n",FILE_APPEND|LOCK_EX);
}
_log("Checking if server is closed");
if ($connect->query("SELECT `value` FROM `specialconfig` WHERE `name`='isClosed'")->fetch_row()[0]==1&&$noRedirect!=true) {
    header("Location: uploadui.php");
    die();
}
function displayList($data) {
    $n = $data->num_rows;
    $localN = $n;
    while($item = $data->fetch_assoc()) {
            $localN--;
            echo "<a href='findui.php?filename=".htmlspecialchars($item["name"])."'";
    switch ($item["goldenFile"]) {
        case 1:
        echo "class='golden'";
        break;
        case 2:
        echo "class='diamond'";
        break;
    }
    echo ">".htmlspecialchars($item["name"])."</a>";
    if (!($localN==0)) {
    echo " | ";
    }

}
echo "</fieldset>";
}
function displayError($e)  {
if ($_COOKIE['devTool']==$devToolString) {
    echo "e";
} else {
    echo "<fieldset><legend>Critical Error</legend>A critical server error has occured,<br>hopefully the servers will be functioning again soon.</fieldset>";
    die();
}
}

?>