<?php
    $db = new Database();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<div id="popupForm" class="popupform">
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
            <select name="path" required>
                <?php
                $path = "views";
                $files = scandir($path);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && $file != "admin") {
                        if (is_dir($path . '/' . $file)) {
                            $subfiles = scandir($path . '/' . $file);
                            foreach ($subfiles as $subfile) {
                                if ($subfile != '.' && $subfile != '..') {
                                    echo '<option value="'.$path."/" . $file . '/' . $subfile . '">'.$path."/" . $file . '/' . $subfile . '</option>';
                                }
                            }
                        } else {
                            echo '<option value="'.$path."/".$file . '">' .$path."/".$file . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </label>
        <div class="options">
            <a class="button" onclick="MessageBox('popupForm')">Zavřít</a>
            <input class="small" type="submit">
        </div>
    </form>
</div>

<div id="editRouteForm" class="popupform">
    <form method="post" action="/admin/router/editRoute">
        <h2>Upravit route - <span id="editRouteNameSpan"></span></h2>
        <label>
            <span>Route</span>
            <input type="text" name="route" id="editRouteName" required>
        </label>
        <label>
            <span>Typ</span>
            <select name="type" id="editRouteType" required>
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
            <span>Cesta</span>
            <input name="path" type="text" id="editRoutePath" required>
        </label>
        <input type="hidden" name="id" id="editRouteId" required>

        <div class="options">
            <a class="button" onclick="MessageBox('editRouteForm')">Zavřít</a>
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
            <a class="button" onclick="MessageBox('popupForm')">Přidat</a>
        </div>
        <section>
            <article class="w100">
                <?php
                if(isset($db)) {
                    $sql = "select * from ".DB_PREFIX."_routes";
                    $routes = $db->fetchRows($db->executeQuery($sql));
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr><td class='fit'>Id</td><td>Route</td><td>Type</td><td>Path</td><td class='fit'>Options</td></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($routes as $route) {
                        echo "<tr>";
                        echo "<td>{$route['id']}</td>";
                        echo "<td>{$route['route']}</td>";
                        echo "<td>{$route['type']}</td>";
                        echo "<td>{$route['path']}</td>";
                        if($route['route'] !== "/") {
                            echo "<td class='fit'><a class='button' onclick='setEditForm(this.parentNode.parentNode)'>Upravit</a><form method='post' action='/admin/router/removeRoute'><input type='hidden' name='id' value='{$route["id"]}'><input class='small' type='submit' value='Delete'></form></td>";
                        } else {
                            echo "<td></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
                ?>
            </article>
        </section>
    </div>
</main>
<script>
    function setEditForm(parent) {
        document.getElementById("editRouteNameSpan").innerText = parent.children[1].innerText
        document.getElementById("editRouteName").value = parent.children[1].innerText
        document.getElementById("editRouteType").value = parent.children[2].innerText
        document.getElementById("editRoutePath").value = parent.children[3].innerText
        document.getElementById("editRouteId").value = parent.children[0].innerText

        MessageBox("editRouteForm")
    }
</script>
</body>

</html>