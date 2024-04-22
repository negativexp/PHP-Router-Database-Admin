<?php
if(isset($db)) {
    $sql = "select * from router_routes";
    $routes = $db->fetchRows($db->executeQuery($sql));
    echo "<table class='products'>";
    echo "<thead>";
    echo "<tr>
<td class='medium'>Id</td>
<td class='medium'>Route</td>
<td class='medium'>Type</td>
<td class='medium'>Path</td>
<td class='medium'>File exists?</td>
<td class='medium'>Options</td></tr>";
    echo "</thead>";
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
?>