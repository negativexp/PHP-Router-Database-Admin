<?php
if(isset($db)) {
    $sql = "select * from router_blocked_folders";
    $blockedFolders = $db->fetchRows($db->executeQuery($sql));
    echo "<table border='1'>";
    echo "<tr><th>Id</th><th>Name</th><th>Folder exists?</th><th>Options</th></tr>";
    foreach ($blockedFolders as $folder) {
        echo "<tr>";
        echo "<td>{$folder['id']}</td>";
        echo "<td>{$folder['name']}</td>";
        echo "<td>{$folder['folderExists']}</td>";
        echo "<td>
                <form method='post' action='/admin/router/removeBlockedFolder'>
                    <input type='hidden' name='id' value='{$folder["id"]}'>
                    <input type='submit' value='Delete'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
}