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
    <form method="post" action="/admin/router/addBlockedFolder">
        <h2>Přidat složku</h2>
        <label>
            <span>Name: </span>
            <input type="text" name="name" required>
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
        <h1 class="big">Zablokované složky</h1>
    </header>
    <div class="wrapper">
        <div class="tableOptions">
            <a class="button" onclick="displayAlert()">Přidat</a>
        </div>
        <section>
            <article class="w100">
                <?php
if(isset($db)) {
    $sql = "select * from router_blocked_folders";
    $blockedFolders = $db->fetchRows($db->executeQuery($sql));
    echo "<table>";
    echo "<thead>";
    echo "<tr>
<td>Id</td>
<td>Name</td>
<td>Folder exists?</td>
<td>Options</td></tr>";
    echo "</thead>";
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
?>
            </article>
        </section>
    </div>
</main>
</body>

</html>