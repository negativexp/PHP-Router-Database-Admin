<?php
    include_once("config.php");
    include_once("db.php");
    $db = new Database();
    $db->update();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../adminStyle.css">
    <script defer src="../../adminScript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<div id="alert">
    <form method="post" action="/admin/router/addRoute">
        <h2>Přidat route</h2>
        <label>
            <span>Route</span>
            <input type="text" name="route" required>
        </label>
        <label>
            <span>Type</span>
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
            <span>Path</span>
            <input type="text" name="path" required>
        </label>
        <div class="options">
            <a class="button small" onclick="hideAlert()">Zavřít</a>
            <input class="small" type="submit">
        </div>
    </form>
</div>
<?php include_once("views/admin/components/sidepanel.php"); ?>

<main>
    <header>
        <h1 class="big">Routes</h1>
    </header>
    <div class="wrapper">
        <div class="tableOptions">
            <a class="button" onclick="displayAlert()">Přidat</a>
        </div>
        <section>
            <article class="w100">
                <?php
                if(isset($db)) {
                    $sql = "select * from ".DB_PREFIX."_routes";
                    $routes = $db->fetchRows($db->executeQuery($sql));
                    echo "<table>";
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
            </article>
        </section>
    </div>
</main>
</body>

</html>