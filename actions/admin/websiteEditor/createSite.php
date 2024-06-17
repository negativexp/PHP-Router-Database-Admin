<?php
if(isset($_POST["siteName"])) {
    $siteName = $_POST["siteName"];
    $file = fopen("views/$siteName.php", "w");
    fclose($file);

    $db = new Database();
    $sql = "insert into ".DB_PREFIX."_routes (route, type, path) values (?, ?, ?)";
    $route = $siteName;
    if($route[0] != "/") {
        $route = "/".$route;
    }
    $params = [$route, "get", "views/$siteName.php"];
    if($db->executeQuery($sql, $params, false)) {
        include_once("actions/admin/logger.php");
    }
}
header("location: /admin/websiteBuilder");
exit();