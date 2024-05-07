<?php
$db = new Database();
$route = str_replace("/admin/", "", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
$params = [$route, urlencode(serialize($_GET)), urlencode(serialize($_POST))];
$sql = "insert into ".DB_PREFIX."_logs (route, getArr, postArr, time) values (?,?,?, NOW())";
$db->executeQuery($sql, $params, false);