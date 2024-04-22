<?php
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
    <form method="post" action="/admin/router/addAllowedFileType">
        <label>
            <span>File type: </span>
            <input type="text" name="filetype" required>
        </label>
        <label>
            <span>File mime type: </span>
            <input type="text" name="mimetype" required>
        </label>
        <div class="options">
            <input type="submit">
            <a class="button" onclick="hideAlert()">Zavřít</a>
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
    $sql = "select * from router_allowed_file_types";
    $allowedFiles = $db->fetchRows($db->executeQuery($sql));
    echo "<table>";
    echo "<thead>";
    echo "<tr>
<td class='medium'>Id</td>
<td class='medium'>file type</td>
<td class='medium'>mime type</td>
<td class='medium'>Options</td></tr>";
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