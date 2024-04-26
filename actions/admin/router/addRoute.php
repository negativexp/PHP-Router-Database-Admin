<?php
include_once("actions/admin/logger.php");
include_once("config.php");
if(isset($_POST["route"]) && isset($_POST["type"]) && isset($_POST["path"])) {
    $db = new Database();
    $sql = "insert into ".DB_PREFIX."_routes (route, type, path, fileExists) values (?, ?, ?, ?)";
    $params = [$_POST["route"], $_POST["type"], $_POST["path"]];
    if(file_exists($_POST["path"])) $params[] = 1; else $params[] = 0;
    $db->executeQuery($sql, $params, false);
    header("location: /admin/router/routes");
}