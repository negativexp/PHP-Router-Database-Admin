<?php
include_once("config.php");
if(isset($_POST["user"]) && isset($_POST["password"])) {
    $db = new Database();
    $sql = "select * from ".DB_PREFIX."_users where username = ? and password = ?";
    $username = htmlspecialchars($_POST["user"], ENT_QUOTES, 'UTF-8');
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