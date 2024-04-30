<?php
function returnWithBacklink() {
    if(isset($_POST["backlink"]))
        header("location: /admin/fileManager?folder={$_POST["backlink"]}");
    exit();
}

function returnWithoutBacklink() {
    header("location: /admin/fileManager?folder={$_POST["backlink"]}");
    exit();
}

if(isset($_POST["selected_files"]) && isset($_POST["delete"])) {
    if(count($_POST["selected_files"]) > 0) {
        foreach($_POST["selected_files"] as $file) {
            unlink($file);
        }
    }
    returnWithBacklink();
}
returnWithoutBacklink();