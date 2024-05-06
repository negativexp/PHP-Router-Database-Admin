<?php
$db = new Database();
$db->updateRouter();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<div id="popupForm">
    <form method="post" action="/admin/router/addBlockedFolder">
        <h2>Přidat složku</h2>
        <label>
            <span>Name: </span>
            <input type="text" name="name" required>
        </label>
        <div class="options">
            <a class="button" onclick="hidePopupForm()">Zavřít</a>
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
            <a class="button" onclick="displayPopupForm()">Přidat</a>
        </div>
        <section>
            <article class="w100">
                <?php
if(isset($db)) {
    $sql = "select * from ".DB_PREFIX."_blocked_folders";
    $blockedFolders = $db->fetchRows($db->executeQuery($sql));
    echo "<table>";
    echo "<thead>";
    echo "<tr>
<td class='fit'>Id</td>
<td>Name</td>
<td>Folder exists?</td>
<td class='fit'>Options</td></tr>";
    echo "</thead>";
    foreach ($blockedFolders as $folder) {
        echo "<tr>";
        echo "<td>{$folder['id']}</td>";
        echo "<td>{$folder['name']}</td>";
        echo "<td>{$folder['folderExists']}</td>";
        echo "<td>
                <form method='post' action='/admin/router/removeBlockedFolder'>
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