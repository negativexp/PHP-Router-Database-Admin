<?php
if(isset($_POST["name"])) {
    $db = new Database();
    $sql = "insert into router_blockedfolders (name, folderExists) values (?, ?)";
    $params = [$_POST["name"]];
    if(is_dir($_POST["name"])) $params[] = 1; else $params[] = 0;
    $db->executeQuery($sql, $params, false);
    header("location: /admin");
}