<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["route"]) && isset($_POST["type"]) && isset($_POST["path"]) && isset($_POST["id"])) {
    $db = new Database();
    $sql = "insert into ".DB_PREFIX."_routes (route, type, path) values (?, ?, ?)";
    $sql = "UPDATE ".DB_PREFIX."_routes SET route = ?, type = ?, path = ? WHERE id = ?";
    $route = $_POST["route"];
    if($route[0] != "/") {
        $route = "/".$route;
    }
    $params = [$route, $_POST["type"], $_POST["path"], $_POST["id"]];
    if($db->executeQuery($sql, $params, false)) {
        include_once("actions/admin/logger.php");
        header("location: /admin/router/routes");
    }
    exit();
}