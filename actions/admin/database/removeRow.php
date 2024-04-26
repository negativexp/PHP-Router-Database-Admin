<?php
include_once("actions/admin/logger.php");
if(isset($_POST["tableName"]) && isset($_POST["id"])) {
    $tableName = $_POST["tableName"];
    $id = $_POST["id"];
    $sql = "delete from {$tableName} where id = ?";
    include_once("db.php");
    $db = new Database();
    $db->executeQuery($sql, [$id], false);
    header("location: /admin/database/table/{$tableName}");
}