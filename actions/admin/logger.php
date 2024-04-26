<?php
$route = str_replace("/admin/", "", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
include_once("db.php");
include_once("config.php");
$db = new Database();
$params = [$route, serialize($_GET), serialize($_POST)];
$sql = "insert into ".DB_PREFIX."_logs (route, getArr, postArr) values (?,?,?)";
$db->executeQuery($sql, $params, false);