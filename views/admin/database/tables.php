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
    <link rel="stylesheet" href="../../resources/admin/adminStyle.css">
    <script defer src="../../resources/admin/adminScript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<div id="alert">
    <form class="w800" method="post" action="/admin/database/addTable">
        <label>
            <span>Jméno tabulky:</span>
            <input type="text" name="tableName" required>
        </label>
        <div id="rowContainer">
            <div class="row">
                <label>
                    <span>Sloupec:</span>
                    <input type="text" name="name[]" required>
                </label>
                <label>
                    <span>Datovej typ:</span>
                    <select name="type[]">
                        <option value="TEXT">text</option>
                        <option value="INT">int</option>
                        <option value="BOOL">bool</option>
                    </select>
                </label>
                <label>
                    <span>Not null:</span>
                    <input type="radio" name="isNull[0]" value="true">
                </label>
                <label>
                    <span>Null:</span>
                    <input type="radio" name="isNull[0]" value="false" checked>
                </label>
                <label>
                    <span>Auto Increment:</span>
                    <input type="checkbox" name="autoIncrement[0]" value="true">
                </label>
                <a onclick="removeRow(this)">Smazat</a>
            </div>
        </div>
        <div class="options">
            <a class="button small" onclick="hideAlert()">Zavřít</a>
            <a class="button small" onclick="addRow()">Přidat sloupec</a>
            <input class="small" type="submit">
        </div>
    </form>

    <script>
        var rowCount = 0; // Track number of rows

        function addRow() {
            rowCount++;
            var newRow = document.createElement('div');
            newRow.className = 'row';
            newRow.innerHTML = `
        <label>
            <span>Sloupec:</span>
            <input type="text" name="name[]" required>
        </label>
        <label>
            <span>Datovej typ:</span>
            <select name="type[]">
                <option value="TEXT">text</option>
                <option value="INT">int</option>
                <option value="BOOL">bool</option>
            </select>
        </label>
        <label>
            <span>Not null:</span>
            <input type="radio" name="isNull[${rowCount}]" value="true">
        </label>
        <label>
            <span>Null:</span>
            <input type="radio" name="isNull[${rowCount}]" value="false" checked>
        </label>
        <label>
            <span>Auto Increment:</span>
            <input type="checkbox" name="autoIncrement[${rowCount}]" value="true">
        </label>
        <a onclick="removeRow(this)">Smazat</a>
    `;
            document.getElementById('rowContainer').appendChild(newRow);
        }

        function removeRow(button) {
            button.parentNode.remove();
        }
    </script>
</div>
<?php include_once("views/admin/components/sidepanel.php"); ?>

<main>
    <header>
        <h1 class="big">Tabulky</h1>
    </header>
    <div class="wrapper">
        <div class="tableOptions">
            <a class="button" onclick="displayAlert()">Přidat</a>
        </div>
        <section>
            <article class="w100">
<?php
if(isset($db)) {
    $tables = $db->getTables();
    echo "<table>";
    echo "<thead>";
    echo "<tr><td>Database</td><td>Options</td></tr>";
    echo "</thead>";
    foreach ($tables as $table) {
        echo "<tr>";
        echo "<td>{$table}</td>";
        echo "<td>
                <form class='formOptions' method='post' action='/admin/database/removeTable'>
                    <input type='hidden' name='name' value='{$table}' '>
                    <input class='small' type='submit' value='Smazat'>
                    <a class='button small' href='/admin/database/table/{$table}'>Upravit</a>
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