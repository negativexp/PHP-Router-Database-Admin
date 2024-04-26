<?php
include_once("config.php");
include_once("db.php");
$db = new Database();
$db->update();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<div id="alert">
    <form method="post" action="/admin/router/addAllowedFileType">
        <h2>Přidat soubor</h2>
        <label>
            <span>File type: </span>
            <input type="text" name="filetype" required>
        </label>
        <label>
            <span>File mime type: </span>
            <input type="text" name="mimetype" required>
        </label>
        <div class="options">
            <a class="small button" onclick="hideAlert()">Zavřít</a>
            <input class="small" type="submit">
        </div>
    </form>
</div>
<?php include_once("views/admin/components/sidepanel.php"); ?>

<main>
    <header>
        <h1 class="big">Povolené soubory</h1>
    </header>
    <div class="wrapper">
        <div class="tableOptions">
            <a class="button" onclick="displayAlert()">Přidat</a>
        </div>
        <section>
            <article class="w100">
                <?php
if(isset($db)) {
    $sql = "select * from ".DB_PREFIX."_allowed_file_types";
    $allowedFiles = $db->fetchRows($db->executeQuery($sql));
    echo "<table>";
    echo "<thead>";
    echo "<tr>
<td>Id</td>
<td>file type</td>
<td>mime type</td>
<td>Options</td></tr>";
    echo "</thead>";

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
?>
            </article>
        </section>
    </div>
</main>
</body>

</html>