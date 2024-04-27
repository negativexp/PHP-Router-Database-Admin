<?php
if(isset($_POST["tableName"]) && isset($_POST["id"])) {
    $db = new Database();
    $tableName = $_POST["tableName"];
    $id = $_POST["id"];
    $sql = "delete from {$tableName} where id = ?";
    if($db->executeQuery($sql, [$id], false)) {
        header("location: /admin/database/table/{$tableName}");
        include_once("actions/admin/logger.php");
    }
    exit();
}