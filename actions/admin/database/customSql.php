<?php
if(isset($_POST["sql"])) {
    $db = new Database();
    $getResult = isset($_POST["getResult"]);
    $sql = $_POST["sql"];
    if($getResult) {
        $result = urlencode(serialize($db->fetchRows($db->executeQuery($sql))));
        header("location: /admin/database/customSql?success=".$result);
    } else {
        $db->executeQuery($sql, [], false);
        header("location: /admin/database/customSql");
    }
    include_once("actions/admin/logger.php");
    exit();
}