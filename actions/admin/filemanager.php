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
            if(is_file($file)) unlink($file); else rmdir($file);
        }
        include_once("actions/admin/logger.php");
        returnBack();
    }
}

if(isset($_POST["addFile"]) && isset($_POST["fileName"])) {
    if(isset($_POST["backlink"])) {
        $file = fopen($_POST["backlink"]."/".$_POST["fileName"], "w");
    } else $file = fopen($_POST["fileName"], "w");
    fclose($file);
    include_once("actions/admin/logger.php");
    returnBack();
}
if(isset($_POST["addFolder"]) && isset($_POST["folderName"])) {
    if(isset($_POST["backlink"])) {
        $fullPath = $_SERVER['DOCUMENT_ROOT']."\\".$_POST["backlink"]."\\".$_POST["folderName"];
    } else $fullPath = $_SERVER['DOCUMENT_ROOT']."\\".$_POST["folderName"];
    mkdir($fullPath);
    include_once("actions/admin/logger.php");
    returnBack();
}

if(isset($_POST["saveFile"]) && isset($_POST["contents"]) && isset($_POST["filepath"])) {
    file_put_contents($_POST["filepath"], $_POST["contents"]);
    include_once("actions/admin/logger.php");
    returnBack();
}
if(isset($_POST["saveFolder"]) && isset($_POST["folderName"]) && isset($_POST["originalFolderName"])) {
    rename($_POST["originalFolderName"], dirname($_POST["originalFolderName"])."\\".$_POST["folderName"]);
    include_once("actions/admin/logger.php");
    returnBack();
}