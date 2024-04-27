<?php
if(isset($_POST["filetype"]) && isset($_POST["mimetype"])) {
    $db = new Database();
    $sql = "insert into ".DB_PREFIX."_allowed_file_types (filetype, mimetype) values (?, ?)";
    $params = [$_POST["filetype"],$_POST["mimetype"]];
    if($db->executeQuery($sql, $params, false)) {
        include_once("actions/admin/logger.php");
        header("location: /admin/router/allowedFiles");
    }
    exit();
}