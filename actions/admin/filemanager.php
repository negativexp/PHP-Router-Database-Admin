<?php
if(isset($_POST["selected_files"]) && isset($_POST["delete"])) {
    if(count($_POST["selected_files"]) > 0) {
        foreach($_POST["selected_files"] as $file) {
            echo $file."<br>";
        }
    }
    echo $_POST["path"];
}