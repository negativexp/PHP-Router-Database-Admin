<?php
if(isset($db)) {
    $tables = $db->getTables();
    echo "<table border='1'>";
    echo "<tr><th>Database</th><th>Options</th></tr>";
    foreach ($tables as $table) {
        echo "<tr>";
        echo "<td>{$table}</td>";
        echo "<td>
                <a href='/admin/database/table/{$table}'>upravit</a>
                <form method='post' action='/admin/database/removeTable'>
                    <input type='hidden' name='name' value='{$table}' '>
                    <input type='submit' value='Smazat'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
}
