<?php
include "lib.php";
include "settings.php";
?>
<a href='uploadui.php' >Back</a><br>
<fieldset>
    <legend>Customization</legend>
    <form action='settings.php' method='post'>
        <select name='colour'>
            <option value='cyan' style='background:cyan'>Cyan</option>
            <option value='orange' style='background:orange'>Orange</option>
            <option value='dark' style='background:rgb(32,32,32)'>Dark</option>
            <option value='green' style='background:green'>Green</option>
            <option value='purple' style='background:purple'>Purple</option>
            <?php
            _log("Checking if user has christmas skin to display in settings");
                if ($connect->execute_query("SELECT `christmasSkinOwned` FROM `accounts` WHERE `id`=?",[$_COOKIE['user']])->fetch_row()[0]==1) {
                    echo "<option value='christmas' style='background:red'>Christmas</option>";
            }
            ?>
            <option value='none'>None</option>
        </select><br> 
        <input type='submit' value='Set colour scheme'>
</fieldset>
