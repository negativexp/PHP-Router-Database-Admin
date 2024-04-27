<?php
$db = new Database();
$tableName = str_replace(" ", "", $_POST["tableName"]);
$columns = $_POST['name'];
$types = $_POST['type'];
$isNull = isset($_POST['isNull']) ? $_POST['isNull'] : array();
$autoIncrement = isset($_POST['autoIncrement']) ? $_POST['autoIncrement'] : array();

$sql = "CREATE TABLE `$tableName` (";
for ($i = 0; $i < count($columns); $i++) {
    $sql .= $columns[$i] . " " . $types[$i];

    if (isset($autoIncrement[$i])) { // Adjusted index
        $sql .= " AUTO_INCREMENT";
        if($columns[$i] == "id") {
            $sql .= " PRIMARY KEY";
        }
    } else {
        if (isset($isNull[$i])) { // Adjusted index
            if ($isNull[$i] == 'true') {
                $sql .= " NOT NULL";
            } else {
                $sql .= " NULL";
            }
        }
    }

    if ($i < count($columns) - 1) {
        $sql .= ", ";
    }
}
$sql .= ");";
$db->executeQuery($sql, [], false);
include_once("actions/admin/logger.php");
header("location: /admin/database/tables");
exit();