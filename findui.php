<style>
    img,video,iframe,audio {
        max-width:100%;
        max-height:80%;
        border-radius: 10px;
    }
</style>
<?php
echo '<title>'.htmlspecialchars(urldecode($_GET['filename']))."</title>";
echo '<a href="uploadui.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="CurrentColor"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg></a> <a href="find.php?filename='.htmlspecialchars($_GET["filename"]).'&download=true"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="CurrentColor"><path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/></svg></a> ';
?>
<?php
try {
include "lib.php";
include "settings.php";
_log("Getting file info");
$getData = $connect->execute_query("SELECT `mtype`,`time`,`tag`,`goldenFile` FROM `files` WHERE `name`= ?",[explode(".",$_GET['filename'])[0]]);
if ($getData->num_rows == 0) {
    header("Location: uploadui.php?error=notfound");
}
_log("Querying cache");
$getSize = $connect->execute_query("SELECT `size` FROM `filesizecache` WHERE `name`= ?",[explode(".",$_GET['filename'])[0]]);
$size = $getSize->fetch_row()[0];
if ($getSize->num_rows==0) {
    _log("File hasn't been cached, calculating file size");
    $sizeCalculated = filesize("files/".basename(urldecode(explode(".",$_GET['filename'])[0])));
    _log("Adding file size to cache");
    $connect->execute_query("INSERT INTO `filesizecache` (`name`,`size`) VALUES (?,?)",[explode(".",$_GET['filename'])[0],$sizeCalculated]);
    $size = $sizeCalculated;
}
_log("Getting view count");
$views = $connect->execute_query("SELECT COUNT(*) FROM `views` WHERE `filename`=?",[explode(".",$_GET['filename'])[0]])->fetch_row()[0];
$data = $getData->fetch_assoc();
$sizeUnit="B";
if ($size>=1000) {
    $size/=1000;
    $sizeUnit = "KB";
}
if ($size>=1000) {
    $size/=1000;
    $sizeUnit = "MB";
}
if ($size>=1000) {
    $size/=1000;
    $sizeUnit = "GB";
}
$formated1 = explode(":",str_replace("-","</b>/<b>",$data["time"]));
$formated = str_replace(" ","</b> <b>","<b>".$formated1[0]."</b>:<b>".$formated1[1]."</b>");
if ($data["tag"]) {
    $tag = htmlspecialchars($data["tag"]);
} else {
    $tag = "None";
}
$fname = htmlspecialchars($_GET['filename']);
switch ($data["mtype"]) {
    case "image/jpeg":
        $extension = "<b>JPEG</b>/<b>JPG</b>";
        break;
    case "video/quicktime":
        $extension = "<b>MOV</b>";
        break;
    case "video/mp4":
        $extension = "<b>MP4</b>";
        break;
    case "image/png":
        $extension = "<b>PNG</b>";
        break;
    case "image/gif":
        $extension = "<b>GIF</b>";
        break;
    case "audio/mpeg":
        $extension = "<b>MP3</b>";
        break;
    case "application/pdf":
        $extension="<b>PDF</b>";
        break;
    default:
        $extension = "Unknown";
}
if ($_COOKIE['devTool']==$devToolString) {
    echo '<a href="devToolsMenu.php?file='.$fname.'"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="CurrentColor"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg></a>';
}
_log("Checking if user is moderator");
if ($connect->execute_query("SELECT isModerator FROM accounts WHERE id=?",[$_COOKIE['user']])->fetch_row()[0] and $data["goldenFile"]==0) {
    echo "<form action='sendFile.php' method='GET'><select name='sendFor' required><option value=1>Golden</option><option value=2>Diamond</option></select> <input type='text' name='filename' hidden value='".htmlspecialchars($_GET['filename'])."'><input type='submit' value='Send'></select></form>";
}
echo "<fieldset class='fieldScale'><legend>File Info</legend>Date Uploaded: ".$formated;
if ($extension!="Unknown") {
echo "<br>File Type: ".$extension;
}
echo "<br>Size: <b>".floor($size).$sizeUnit."</b><br>";
if ($tag!="None") {
    echo "Tag: <a href='searchtag.php?tag=".htmlspecialchars($tag)."'><b>".htmlspecialchars($tag)."</b></a><br>";
}
echo "Views: <b>".$views."</b>";
echo "</fieldset><br>";
switch (explode("/",$data["mtype"])[0]) {
    case "image":
        echo "<img src='find.php?filename=".htmlspecialchars($_GET['filename'])."'>";
        break;
    case "video":
        echo "<video controls autoplay muted><source src='find.php?filename=".htmlspecialchars($_GET['filename'])."'></video>";
        break;
    case "audio":
        echo "<audio controls><source src='find.php?filename=".htmlspecialchars($_GET['filename'])."'></audio>";
        break;
    default:
    echo "<iframe sandbox src='find.php?filename=".htmlspecialchars($_GET['filename'])."'></iframe>";
}
} catch(Exception $e) {
    echo $e->getMessage();
}
?>