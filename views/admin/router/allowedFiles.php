<?php
$db = new Database();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<div id="popupForm">
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
            <a class="small button" onclick="hidePopupForm()">Zavřít</a>
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
            <a class="button" onclick="displayPopupForm()">Přidat</a>
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
<td class='fit'>Id</td>
<td>file type</td>
<td>mime type</td>
<td class='fit'>Options</td></tr>";
    echo "</thead>";

    foreach ($allowedFiles as $folder) {
        echo "<tr>";
        echo "<td>{$folder['id']}</td>";
        echo "<td>{$folder['filetype']}</td>";
        echo "<td>{$folder['mimetype']}</td>";
        echo "<td>
                <form method='post' action='/admin/router/removeAllowedFileType'>
                    <input type='hidden' name='id' value='{$folder["id"]}'>
                    <input class='small' type='submit' value='Delete'>
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