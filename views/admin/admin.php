<?php
    $db = new Database();
    $db->update();
?>
<h1>Router</h1>
<h2>Routes</h2>
<?php
    $sql = "select * from router_routes";
    $routes = $db->fetchRows($db->executeQuery($sql));
    echo "<table border='1'>";
    echo "<tr><th>Id</th><th>Route</th><th>Type</th><th>Path</th><th>File exists?</th><th>Options</th></tr>";
    foreach ($routes as $route) {
        echo "<tr>";
        echo "<td>{$route['id']}</td>";
        echo "<td>{$route['route']}</td>";
        echo "<td>{$route['type']}</td>";
        echo "<td>{$route['path']}</td>";
        echo "<td>" . ($route['fileExists'] == 1 ? "true" : "false") . "</td>";
        echo "<td>
                <form method='post' action='/admin/removeRoute'>
                    <input type='hidden' name='id' value='{$route["id"]}'>
                    <input type='submit' value='Delete'>
                </form>
              </td>";
        echo "</tr>";
    }
     echo "</table>";
?>
<h2>Přidat route</h2>
<form method="post" action="/admin/addRoute">
    <label>
        <span>Route</span>
        <input type="text" name="route" required>
    </label>
    <label>
        <span>Type:</span>
        <select name="type" required>
            <option>get</option>
            <option>post</option>
            <option>getpost</option>
            <option>put</option>
            <option>patch</option>
            <option>delete</option>
            <option>any</option>
        </select>
    </label>
    <label>
        <span>Path:</span>
        <input type="text" name="path" required>
    </label>
    <input type="submit">
</form>
<h2>Zablokovane slozky</h2>
<?php
    $sql = "select * from router_blockedfolders";
    $blockedFolders = $db->fetchRows($db->executeQuery($sql));
    echo "<table border='1'>";
    echo "<tr><th>Id</th><th>Name</th><th>Folder exists?</th><th>Options</th></tr>";
    foreach ($blockedFolders as $folder) {
        echo "<tr>";
        echo "<td>{$folder['id']}</td>";
        echo "<td>{$folder['name']}</td>";
        echo "<td>{$folder['folderExists']}</td>";
        echo "<td>
                <form method='post' action='/admin/removeBlockedFolder'>
                    <input type='hidden' name='id' value='{$folder["id"]}'>
                    <input type='submit' value='Delete'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
?>
<h2>pridat zablokovanou slozku</h2>
<form method="post" action="/admin/addBlockedFolder">
    <label>
        <span>Name: </span>
        <input type="text" name="name" required>
    </label>
    <input type="submit">
</form>