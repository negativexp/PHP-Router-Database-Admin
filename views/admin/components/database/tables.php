<?php
if(isset($db)) {
    $tables = $db->getTables();
    echo "<table border='1'>";
    echo "<tr><th>Database</th><th>Options</th></tr>";
    foreach ($tables as $table) {
        echo "<tr>";
        echo "<td>{$table}</td>";
        echo "<td>
                <a>Upravit</a>
                <a>smazat</a>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
}
