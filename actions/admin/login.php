<?php
include_once("config.php");
if(isset($_POST["username"]) && isset($_POST["password"])) {
    $db = new Database();
    $sql = "select * from ".DB_PREFIX."_users where username = ? and password = ?";
    $username = $_POST["username"];
    $password = hash("sha256", $_POST["password"]);
    $params = [$username, $password];
    if($db->fetchSingleRow($db->executeQuery($sql, $params))) {
        session_start();
        $_SESSION["admin"] = ["username" => "admin"];
        header("location: /admin");
    } else {
        header("location: /admin/login");
    }
    exit();
}
