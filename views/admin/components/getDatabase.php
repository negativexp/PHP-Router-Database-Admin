<?php
if(isset($name)) {
    echo "<h1>Tabulka: {$name}</h1>";
    include_once("db.php");
    $db = new Database();
    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$name}'";
    $columns = $db->fetchRows($db->executeQuery($sql));
    $sql = "select * from {$name}";
    $rows = $db->fetchRows($db->executeQuery($sql));
    echo "<table border='1'>";
    echo "<thead>";
    echo "<tr>";
    foreach($columns as $column) {
        echo "<th>{$column["COLUMN_NAME"]}</th>";
    }
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $columnValue) {
            echo "<td>{$columnValue}</td>";
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}