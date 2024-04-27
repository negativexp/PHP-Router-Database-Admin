<?php
if(isset($_POST["route"]) && isset($_POST["type"]) && isset($_POST["path"])) {
    $db = new Database();
    $sql = "insert into ".DB_PREFIX."_routes (route, type, path, fileExists) values (?, ?, ?, ?)";
    $route = $_POST["route"];
    if($route[0] != "/") {
        $route = "/".$route;
    }
    $params = [$route, $_POST["type"], $_POST["path"]];
    if(file_exists($_POST["path"])) $params[] = 1; else $params[] = 0;
    if($db->executeQuery($sql, $params, false)) {
        include_once("actions/admin/logger.php");
        header("location: /admin/router/routes");
    }
    exit();
}