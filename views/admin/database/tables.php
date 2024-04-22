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
    <form method="post" action="/admin/database/addTable">
        <label>
            <span>Table name:</span>
            <input type="text" name="tableName">
        </label>
        <div id="rowContainer">
            <div class="row">
                <label>
                    <span>Column name:</span>
                    <input type="text" name="name[]" required>
                </label>
                <label>
                    <span>Data type:</span>
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
                <button type="button" onclick="removeRow(this)">Remove</button>
            </div>
            <div id="newRow"></div>
        </div>
        <div class="options">
            <input type="submit">
            <a class="button" onclick="hideAlert()">Zavřít</a>
            <a class="button" onclick="addRow()">Add Another Row</a>
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
                <span>Column name:</span>
                <input type="text" name="name[]" required>
            </label>
            <label>
                <span>Data type:</span>
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
            <button type="button" onclick="removeRow(this)">Remove</button>
        `;
            document.getElementById('newRow').appendChild(newRow);
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