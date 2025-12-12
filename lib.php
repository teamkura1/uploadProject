<?php
include "config.php";
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