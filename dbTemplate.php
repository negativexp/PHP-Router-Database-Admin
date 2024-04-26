<?php
include_once("db.php");
include_once("config.php");
$db = new Database();
$sql = "
CREATE TABLE " . DB_PREFIX . "_allowed_file_types (id INT AUTO_INCREMENT, filetype TEXT, mimetype TEXT, PRIMARY KEY (id));
CREATE TABLE " . DB_PREFIX . "_blocked_folders (id INT AUTO_INCREMENT, name TEXT, folderExists TINYINT(1), PRIMARY KEY (id));
CREATE TABLE " . DB_PREFIX . "_routes (id INT AUTO_INCREMENT, route TEXT, type TEXT, path TEXT, fileExists TINYINT(1), PRIMARY KEY (id));
CREATE TABLE " . DB_PREFIX . "_users (id INT AUTO_INCREMENT, username TEXT, password TEXT, PRIMARY KEY (id));
CREATE TABLE " . DB_PREFIX . "_logs (id INT AUTO_INCREMENT, route TEXT, getArr TEXT, postArr TEXT, PRIMARY KEY (id));
INSERT INTO " . DB_PREFIX . "_allowed_file_types (filetype, mimetype) VALUES ('css', 'text/css');
INSERT INTO " . DB_PREFIX . "_allowed_file_types (filetype, mimetype) VALUES ('js', 'text/javascript');
INSERT INTO " . DB_PREFIX . "_routes (route, type, path, fileExists) VALUES ('/', 'get', 'views/index.php', 0);
INSERT INTO " . DB_PREFIX . "_users (username, password) VALUES ('root', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8');
";
$db->executeMultipleQueries($sql);
echo "<p>Tables created!</p>";
echo "<p>Please refresh to get to index file</p>";