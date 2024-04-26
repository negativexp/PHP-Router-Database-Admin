<?php
include_once("actions/admin/logger.php");
include_once("config.php");
if(isset($_POST["name"])) {
    $db = new Database();
    $sql = "insert into ".DB_PREFIX."_blocked_folders (name, folderExists) values (?, ?)";
    $params = [$_POST["name"]];
    if(is_dir($_POST["name"])) $params[] = 1; else $params[] = 0;
    $db->executeQuery($sql, $params, false);
    header("location: /admin/router/blockedFolders");
}