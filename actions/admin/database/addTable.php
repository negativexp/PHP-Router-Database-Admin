<?php
$tableName = $_POST['tableName'];
$columns = $_POST['name'];
$types = $_POST['type'];
$isNull = $_POST['isNull'];
$autoIncrement = isset($_POST['autoIncrement']) ? $_POST['autoIncrement'] : array();

// Construct SQL query
$sql = "CREATE TABLE $tableName (";

for ($i = 0; $i < count($columns); $i++) {
    $sql .= $columns[$i] . " " . $types[$i];

    if (in_array($i, array_keys($autoIncrement))) {
        $sql .= " AUTO_INCREMENT";
        if($columns[$i] == "id") {
            $sql .= " PRIMARY KEY";
        }
    } else {
        if ($isNull[$i] == 'true') {
            $sql .= " NOT NULL";
        } else {
            $sql .= " NULL";
        }
    }

    if ($i < count($columns) - 1) {
        $sql .= ", ";
    }
}

$sql .= ");";

include_once("db.php");
$db = new Database();
$params = [];
$db->executeQuery($sql, $params, false);
header("location: /admin/database");