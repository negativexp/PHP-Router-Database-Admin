<?php
if(isset($_POST["id"])) {
    $db = new Database();
    $sql = "delete from ".DB_PREFIX."_allowed_file_types where id = ?";
    $params = [$_POST["id"]];
    if($db->executeQuery($sql, $params,  false)) {
        include_once("actions/admin/logger.php");
        header("location: /admin/router/allowedFiles");
    }
    exit();
}