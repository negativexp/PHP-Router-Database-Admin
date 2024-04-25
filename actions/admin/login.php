<?php
include_once("config.php");
if(isset($_POST["user"]) && isset($_POST["password"])) {
    $db = new Database();
    $sql = "select * from ".DB_PREFIX."_users where username = ? and password = ?";
    $params = [$_POST["user"], $_POST["password"]];
    $result = $db->fetchSingleRow($db->executeQuery($sql, $params));
    if($db->fetchSingleRow($db->executeQuery($sql, $params))) {
        session_start();
        $_SESSION["admin"] = true;
        header("location: /admin");
    } else {
        header("location: /admin/login");
    }
    exit();
}