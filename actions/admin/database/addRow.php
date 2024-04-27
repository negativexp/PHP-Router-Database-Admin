<?php
if(isset($_POST["tableName"])) {
    $db = new Database();
    $tableName = $_POST["tableName"];
    $params = array_values($_POST);
    $columnNames = array_keys($_POST);
    unset($params[0]);
    unset($columnNames[0]);
    $placeholders = implode(",", array_fill(0, count($params), "?"));
    $columnNamesString = implode(",", $columnNames);
    $sql = "INSERT INTO $tableName ($columnNamesString) VALUES ($placeholders)";
    if($db->executeQuery($sql, $params, false)) {
        include_once("actions/admin/logger.php");
        header("location: /admin/database/table/{$tableName}");
    }
    exit();
}