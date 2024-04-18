<?php
if(isset($db)) {
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
                <form method='post' action='/admin/router/removeRoute'>
                    <input type='hidden' name='id' value='{$route["id"]}'>
                    <input type='submit' value='Delete'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
}