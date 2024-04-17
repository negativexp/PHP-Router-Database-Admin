<?php
if(isset($_POST["user"]) && isset($_POST["password"])) {
    $db = new Database();
    $sql = "select * from router_users where username = ? and password = ?";
    $params = [$_POST["user"], $_POST["password"]];
    echo "{$_POST["user"]} ; {$_POST["password"]}";
    if($db->fetchSingleRow($db->executeQuery($sql, $params))) {
        session_start();
        $_SESSION["admin"] = true;
        header("location: /admin");
    } else {
        header("location: /admin/login");
    }
    exit();
}