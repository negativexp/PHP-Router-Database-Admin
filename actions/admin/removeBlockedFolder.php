<?php
if(isset($_POST["id"])) {
    $db = new Database();
    $sql = "delete from router_blockedfolders where id = ?";
    $params = [$_POST["id"]];
    $db->executeQuery($sql, $params, false);
    header("location: /admin");
}