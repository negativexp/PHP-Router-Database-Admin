<?php
//echo "Table Name: " . $_POST['tableName'] . "<br>";
//if(isset($_POST['name'])) {
//    $columns = $_POST['name'];
//    $types = $_POST['type'];
//    $isNull = $_POST['isNull'];
//    $autoIncrement = isset($_POST['autoIncrement']) ? $_POST['autoIncrement'] : array();
//
//    echo "<table border='1'>";
//    echo "<tr><th>Column Name</th><th>Data Type</th><th>Not Null</th><th>Auto Increment</th></tr>";
//
//    for ($i = 0; $i < count($columns); $i++) {
//        echo "<tr>";
//        echo "<td>" . $columns[$i] . "</td>";
//        echo "<td>" . $types[$i] . "</td>";
//        echo "<td>" . ($isNull[$i] == 'true' ? "Yes" : "No") . "</td>";
//        echo "<td>" . (in_array($i, array_keys($autoIncrement)) ? "Yes" : "No") . "</td>"; // Check if current index is in array keys
//        echo "</tr>";
//    }
//    echo "</table>";
//}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve table name
    $tableName = $_POST['tableName'];

    // Retrieve column details
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

    // Execute the query (you'll need your MySQL connection here)
    // mysqli_query($conn, $sql);

    echo "Table created successfully with the following query: <br>";
    echo $sql;
}