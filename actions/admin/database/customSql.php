<?php
if(isset($_POST["sql"])) {
    $db = new Database();
    $getResult = isset($_POST["getResult"]);
    $sql = $_POST["sql"];
    if($getResult) {
        $result = $db->fetchRows($db->executeQuery($sql));
        $_SESSION["customsql_result"] = $result;
        $sql = urlencode($sql);
        header("location: /admin/database/customSql?sql={$sql}");
    } else {
        $db->executeQuery($sql, [], false);
        $sql = urlencode($sql);
        header("location: /admin/database/customSql?sql={$sql}");
    }
    include_once("actions/admin/logger.php");
    exit();
}