<?php
$tableName = str_replace(" ", "", $_POST["tableName"]); // Assuming this is sanitized and validated
$columns = $_POST['name'];
$types = $_POST['type'];
$isNull = isset($_POST['isNull']) ? $_POST['isNull'] : array(); // Adjusting for isset() check
$autoIncrement = isset($_POST['autoIncrement']) ? $_POST['autoIncrement'] : array();

// Construct SQL query
$sql = "CREATE TABLE `$tableName` ("; // Escape table name with backticks

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

include_once("db.php");
$db = new Database();
$db->executeQuery($sql, [], false);

// Ensure no output is sent before this point
header("location: /admin/database/tables");