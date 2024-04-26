<?php
include_once("actions/admin/logger.php");
if(isset($_POST["tableName"])) {
    $tableName = $_POST["tableName"];
    $params = array_values($_POST);
    $columnNames = array_keys($_POST);
    unset($params[0]);
    unset($columnNames[0]);
    $placeholders = implode(",", array_fill(0, count($params), "?"));
    $columnNamesString = implode(",", $columnNames);
    $sql = "INSERT INTO $tableName ($columnNamesString) VALUES ($placeholders)";
    include_once("db.php");
    $db = new Database();
    if($db->executeQuery($sql, $params, false)) {
        header("location: /admin/database/table/{$tableName}");
        exit();
    }
}
