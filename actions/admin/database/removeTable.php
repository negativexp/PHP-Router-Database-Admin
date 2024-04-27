<?php
if(isset($_POST["name"])) {
    $db = new Database();
    $sql = "drop table {$_POST["name"]}";
    $db->executeQuery($sql, [], false);
    include_once("actions/admin/logger.php");
    header("location: /admin/database/tables");
    exit();
}