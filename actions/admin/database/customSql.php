<?php
include_once("actions/admin/logger.php");
if(isset($_POST["sql"])) {
    $getResult = isset($_POST["getResult"]);
    $sql = $_POST["sql"];
    include_once("db.php");
    $db = new Database();
    if($getResult) {
        $result = urlencode(serialize($db->fetchRows($db->executeQuery($sql))));
        header("location: /admin/database/customSql?success=".$result);
        die();
    } else {
        $db->executeQuery($sql, [], true);
        header("location: /admin/database/customSql");
        die();
    }
}