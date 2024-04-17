<?php
if(isset($_POST["filetype"]) && isset($_POST["mimetype"])) {
    $db = new Database();
    $sql = "insert into router_allowed_file_types (filetype, mimetype) values (?, ?)";
    $params = [$_POST["filetype"],$_POST["mimetype"]];
    $db->executeQuery($sql, $params, false);
    header("location: /admin");
}