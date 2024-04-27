<?php
$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>
<div id="alert">
    <form method="post" action="/admin/database/addRow">
        <h2>Přidat řádek</h2>
        <input type="hidden" name="tableName" value="<?= isset($name) ? $name : "..."?>">
        <?php
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$name}'";
        $columns = $db->fetchRows($db->executeQuery($sql));
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        foreach($columns as $column) {
            echo "<td>{$column["COLUMN_NAME"]}</td>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo "<tr>";
        foreach($columns as $column) {
            if($column["COLUMN_NAME"] == "id") {
                echo "<td></td>";
            } else {
                echo "<td><input type='text' name='{$column["COLUMN_NAME"]}'></td>";
            }
        }
        echo "</tr>";
        echo "</tbody>";

        echo "</table>";
        ?>
        <div class="options">
            <a class="button small" onclick="hideAlert()">Zavřít</a>
            <input class="small" type="submit">
        </div>
    </form>
</div>
<main>
    <header>
        <h1 class="big">Tabulka: <?= isset($name) ? $name : "..."?></h1>
    </header>
    <div class="wrapper">
        <div class="tableOptions">
            <a class="button" href="/admin/database/tables">Zpátky</a>
            <a class="button" onclick="displayAlert()">Přidat řádek</a>
        </div>
        <section>
            <article class="w100">
                <?php
                if(isset($name)) {
                    $sql = "select * from {$name}";
                    $rows = $db->fetchRows($db->executeQuery($sql));
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    foreach($columns as $column) {
                        echo "<td>{$column["COLUMN_NAME"]}</td>";
                    }
                    echo "<td>Options</td>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($rows as $row) {
                        echo "<tr>";
                        foreach ($row as $columnValue) {
                            echo "<td>{$columnValue}</td>";
                        }
                        echo "<td>
                <form class='formOptions' method='post' action='/admin/database/removeRow'>
                    <input type='hidden' name='tableName' value='{$name}' '>
                    <input type='hidden' name='id' value='{$row["id"]}' '>
                    <input class='small' type='submit' value='Smazat'>
                </form>
              </td>";
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
</body>
</html>
