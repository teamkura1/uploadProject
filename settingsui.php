<?php
include "config.php";
?>
<a href='uploadui.php' >Back</a><br>
<fieldset>
    <legend>Customization</legend>
    <form action='settings.php' method='post'>
        <select name='colour'>
            <option value='cyan'>Cyan</option>
            <option value='orange'>Orange</option>
            <option value='dark'>Dark</option>
            <option value='green'>Green</option>
            <option value='purple'>Purple</option>
            <?php
                if ($connect->execute_query("SELECT `christmasSkinOwned` FROM `accounts` WHERE `id`=?",[$_COOKIE['user']])->fetch_row()[0]==1) {
                    echo "<option value='christmas'>Christmas</option>";
            }
            ?>
            <option value='none'>None (warning, ugly)</option>
        </select><br> 
        <input type='submit' value='Set colour scheme'>
</fieldset>
<?php
include "settings.php";
?>