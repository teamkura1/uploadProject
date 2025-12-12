<!-- File viewer UI -->
<style>
    img,video,iframe,audio {
        max-width:100%;
        max-height:80%;
        border-radius: 10px;
    }

</style>
<a href='uploadui.php'>Back</a><br>
<?php
try {
include "settings.php";
include "lib.php";
$getData = $connect->execute_query("SELECT `mtype`,`time`,`tag`,`goldenFile` FROM `files` WHERE `name`= ?",[explode(".",$_GET['filename'])[0]]);
if ($getData->num_rows == 0) {
    header("Location: uploadui.php?error=notfound");
}
$getSize = $connect->execute_query("SELECT `size` FROM `filesizecache` WHERE `name`= ?",[explode(".",$_GET['filename'])[0]]); // this is done to reduce server load, instead of calculating the size of files every time the file is viewed it calculates once and uses that forever

$size = $getSize->fetch_row()[0];
if ($getSize->num_rows==0) {
    $sizeCalculated = $connect->execute_query("SELECT sum(LENGTH(fileBlob)) FROM FILES WHERE `name`= ?",[explode(".",$_GET['filename'])[0]])->fetch_row()[0];
    $connect->execute_query("INSERT INTO `filesizecache` (`name`,`size`) VALUES (?,?)",[explode(".",$_GET['filename'])[0],$sizeCalculated]);
    $size = $sizeCalculated;
}

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
$formated1 = explode(":",str_replace("-","</b>/<b>",$data["time"])); //waiter, waiter! more stupid time formatting
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
        $extension = "<b>M2A</b>/<b>M3A</b>/<b>MP2</b>/<b>MP2A</b>/<b>MP3</b>/<b>MPGA</b>";
        break;
    case "application/pdf":
        $extension="<b>PDF</b>";
        break;
    default:
        $extension = "Unknown";
}
echo "<a href='find.php?filename=".htmlspecialchars($_GET['filename'])."&download=true'>Download</a>";
if ($_COOKIE['devTool']==$devToolString) {
    echo "<br><a href='devToolsMenu.php?file={$fname}'>Dev Tools</a>";
}
if ($connect->execute_query("SELECT isModerator FROM accounts WHERE id=?",[$_COOKIE['user']])->fetch_row()[0] and $data["goldenFile"]==0) {
    echo "<form action='sendFile.php' method='GET'><select name='sendFor' required><option value=1>Golden</option><option value=2>Diamond</option></select> <input type='text' name='filename' hidden value='".htmlspecialchars($_GET['filename'])."'><input type='submit' value='Send'></select></form>";
}

echo "<fieldset class='fieldScale'><legend>File Info</legend>Date Uploaded: ".$formated;
if ($extension!="Unknown") {
echo "<br>File Type: ".$extension;
}
echo "<br>Size: <b>".floor($size).$sizeUnit."</b><br>";
if ($tag!="None") {
    echo "Tag: <a href='searchtag.php?tag=".htmlspecialchars($tag)."'><b>".htmlspecialchars($tag)."</b></a>";
}
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
    displayError($e);
}
?>