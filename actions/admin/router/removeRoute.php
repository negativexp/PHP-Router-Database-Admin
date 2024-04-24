<?php
include_once("config.php");
if(isset($_POST["id"])) {
    $db = new Database();
    $sql = "delete from ".DB_PREFIX."_routes where id = ?";
    $params = [$_POST["id"]];
    $db->executeQuery($sql, $params, false);
    header("location: /admin/router/routes");
}
