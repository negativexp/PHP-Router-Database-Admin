<?php
if(isset($db)) {
    $sql = "select * from router_allowed_file_types";
    $allowedFiles = $db->fetchRows($db->executeQuery($sql));
    echo "<table border='1'>";
    echo "<tr><th>Id</th><th>file type</th><th>mime type</th><th>Options</th></tr>";
    foreach ($allowedFiles as $folder) {
        echo "<tr>";
        echo "<td>{$folder['id']}</td>";
        echo "<td>{$folder['filetype']}</td>";
        echo "<td>{$folder['mimetype']}</td>";
        echo "<td>
                <form method='post' action='/admin/router/removeAllowedFileType'>
                    <input type='hidden' name='id' value='{$folder["id"]}'>
                    <input type='submit' value='Delete'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
}