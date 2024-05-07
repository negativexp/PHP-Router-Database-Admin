<?php
if(isset($_POST["name"])) {
    $db = new Database();
    $sql = "insert into ".DB_PREFIX."_blocked_folders (name) values (?)";
    $params = [$_POST["name"]];
    if($db->executeQuery($sql, $params, false)) {
        include_once("actions/admin/logger.php");
        header("location: /admin/router/blockedFolders");
    }
    exit();
}