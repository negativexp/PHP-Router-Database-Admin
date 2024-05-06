<?php
function returnBack() {
    if(isset($_POST["backlink"]))
        header("location: /admin/fileManager?folder={$_POST["backlink"]}");
    else {
        header("location: /admin/fileManager");
    }
    exit();
}

if(isset($_POST["selected_files"]) && isset($_POST["delete"])) {
    if(count($_POST["selected_files"]) > 0) {
        foreach($_POST["selected_files"] as $file) {
            unlink($file);
        }
    }
    include_once("actions/admin/logger.php");
    returnBack();
}

if(isset($_POST["addFile"]) && isset($_POST["fileName"])) {
    if(isset($_POST["backlink"])) {
        $file = fopen($_POST["backlink"]."/".$_POST["fileName"], "w");
    } else $file = fopen($_POST["fileName"], "w");
    fclose($file);
    include_once("actions/admin/logger.php");
    returnBack();
}

if(isset($_POST["saveFile"]) && isset($_POST["contents"])) {
    //dodelat
    returnBack();
}