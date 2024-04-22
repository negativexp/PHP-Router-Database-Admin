<?php
if(isset($_POST["id"])) {
    $db = new Database();
    $sql = "delete from router_allowed_file_types where id = ?";
    $params = [$_POST["id"]];
    $db->executeQuery($sql, $params, false);
    header("location: /admin/router/allowedFiles");
}