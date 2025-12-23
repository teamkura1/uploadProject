<a href='settingsui.php' ><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="CurrentColor"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg></a><br>
<?php
try {
    $noRedirect = true;
include "lib.php";
include "settings.php";
if ($_COOKIE['devTool']==$devToolString) {
    echo "<a href='devTool.php?action=toggleClosure'>Toggle Server Availablity</a><br>";
}
_log("Checking if server is ready");
if ($connect->query("SELECT `value` FROM `specialconfig` WHERE `name`='isClosed'")->fetch_row()[0]==1) {
    header("HTTP/1.1 503 Service Unavailable");
    echo "<fieldset><legend>Server Unavailable</legend>This server has been disabled temporarily</fieldset>";
    die();
}
_log("Checking if user doesn't already have christmas skin");
if (date('m')==12&&$connect->execute_query("SELECT `christmasSkinOwned` FROM `accounts` WHERE `id`=?",[$_COOKIE['user']])->fetch_row()[0]==0) {
    echo "<a href='claimSkin.php?skinID=1'>Claim Christmas Skin</a><br>";
}
echo "We currently have a total of <b id='fileCount'>-</b> files uploaded, which is <b id='fileSpaceUsed'>-</b> total, <b id='diskFree'>-</b> more is available";
} catch(Exception $e) {
    echo "<fieldset><legend>Critical Error</legend>A critical server error has occured,<br>hopefully the servers will be functioning again soon.</fieldset>";
    die();
}

?>
        <script>
            function update() {
                    var req1 = new XMLHttpRequest()
                    req1.onload = function() {
                    document.getElementById("diskFree").innerText = Math.round(this.responseXML.getElementsByTagName("freeSpace")[0].innerHTML/1000/1000/100)/10+"GB"
                    document.getElementById("fileSpaceUsed").innerText = Math.round(this.responseXML.getElementsByTagName("spaceUsed")[0].innerHTML/1000/1000/100)/10+"GB"
                    document.getElementById("fileCount").innerText =this.responseXML.getElementsByTagName("files")[0].innerHTML
            }
            req1.open("GET","/api.php?stats");
            req1.send();
            }
            update();
            setInterval(update,15000)
        </script>
    <fieldset style='width:16%;' >
        <legend>File Upload</legend><form method="post" action="upload.php" enctype="multipart/form-data">
    <input type="file" name="file" required><br>
    <input type="text" name="renameTo" placeholder="custom file name ( optional )"><br>
    <input type="text" name="tag" placeholder="tag ( optional )"><br>
    <input type="submit" value="Upload"></form>
    </fieldset>
    <fieldset style='width:10%' >
        <legend>Search Files</legend><form method="get" action="search.php">
            <input name="searchQuery" type="text" placeholder="name"><br>
    <input type="submit" value="Search"></form><form method="get" action="searchTag.php">
            <input name="tag" type="text" placeholder="tag name"><br>
    <input type="submit" value="Search by tag"></form>
</fieldset>
    <script>
    if (location.search.startsWith("?error=duplicate")) {
                var notif = document.createElement("fieldset")
        notif.setAttribute("class","fieldScale")
        notif.innerHTML = "<legend>Upload Failed</legend>A file with the same name has already been uploaded, try renaming it"
        document.body.append(notif)
    }
        if (location.search.startsWith("?error=no.")) {
                var notif = document.createElement("fieldset")
        notif.setAttribute("class","fieldScale")
        notif.innerHTML = "<legend>...</legend>This is not for you."
        document.body.append(notif)
    }
            if (location.search.startsWith("?error=invalidname")) {
                var notif = document.createElement("fieldset")
        notif.setAttribute("class","fieldScale")
        notif.innerHTML = "<legend>Invalid Name</legend>The file could not be uploaded because the name was invalid."
        document.body.append(notif)
    }
       if (location.search.startsWith("?error=notfound")) {
                var notif = document.createElement("fieldset")
        notif.setAttribute("class","fieldScale")
        notif.innerHTML = "<legend>No File</legend>The file you tried to find was not found"
        document.body.append(notif)
    }
           if (location.search.startsWith("?error=noitems")) {
                var notif = document.createElement("fieldset")
        notif.setAttribute("class","fieldScale")
        notif.innerHTML = "<legend>Tag Error</legend>No items were found inside the requested tag"
        document.body.append(notif)
    }
    if (location.search.startsWith("?miscNotif=christmasSkinClaimed")) {
        var notif = document.createElement("fieldset")
        notif.setAttribute("class","fieldScale")
        notif.innerHTML = "<legend>Skin Claimed</legend>Successfully claimed christmas skin"
        document.body.append(notif)
    }
    </script>
    
<?php
try {
    _log("Getting all file names and goldenFile");
$getData = "SELECT `name`, `goldenFile` FROM `files` ORDER BY `goldenFile` DESC";
$data = $connect->query($getData);
echo "<fieldset><legend>All Files <a  href='gallery.php'>Visit The Gallery</a></legend>";
displayList($data);
echo "Version <b>1.4.0</b>";
} catch(Exception) {
    displayError($e);
}
?>