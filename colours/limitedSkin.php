<?php
header("Content-Type: text/css");
include "../lib.php";
_log("Checking if user has christmas skin, and has chosen to use it");
if ($connect->execute_query("SELECT `christmasSkinOwned` FROM `accounts` WHERE `id`=?",[$_COOKIE['user']])->fetch_row()[0]==1 && $_COOKIE['colourscheme']=='christmas') {
    echo "@keyframes christmasText {
    0% {color: red;}
    100% {color:#54fa54;}
}
    @keyframes christmasText2 {
    0% {color: red;}
    100% {color:#15bf15;}
}
body, a {
    background:rgb(32,32,32);
    animation-direction: alternate;

    animation-duration: 7.5s;
    animation-iteration-count: infinite;
}
a {
    animation-name: christmasText2;
}
body {
    animation-name: christmasText;
}
b {
    color:lime;
    box-shadow: 0 0 5px green;
}
input,select {
    background:limegreen;
    color: darkred;
}
::placeholder {
    color:red;
}
::file-selector-button {
    background:rgba(0,0,0,0);
    border-color: rgba(0,0,0,0.5);
}

@keyframes hoveranim {
    0% {box-shadow: 0 0 0px lightblue;}
    100% {box-shadow: 0 0 10px lightblue;}
}
@keyframes hoveranim2 {
    0% {box-shadow: 0 0 0px gold;}
    100% {box-shadow: 0 0 10px magenta;}
}
@keyframes hoveranim3 {
    0% {box-shadow: 0 0 0px maroon;}
    100% {box-shadow: 0 0 10px red;}
}
:not(.diamond, .golden)::selection {
    color: green;
}
@keyframes boldText {
    0% {box-shadow: 0 0 5px red;};
100% {box-shadow: 0 0 5px green;};
}";
}
?>