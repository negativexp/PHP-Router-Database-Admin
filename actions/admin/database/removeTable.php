<?php
if(isset($_POST["name"])) {
    include_once("db.php");
    $db = new Database();
    $sql = "drop table {$_POST["name"]}";
    $db->executeQuery($sql, [], false);
    header("location: /admin/database/tables");
}