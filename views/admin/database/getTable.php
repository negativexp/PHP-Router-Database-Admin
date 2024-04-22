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
    <link rel="stylesheet" href="../../../adminStyle.css">
    <script defer src="../../../adminScript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<?php include_once("views/admin/components/sidepanel.php"); ?>

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
                    include_once("db.php");
                    $db = new Database();
                    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$name}'";
                    $columns = $db->fetchRows($db->executeQuery($sql));
                    $sql = "select * from {$name}";
                    $rows = $db->fetchRows($db->executeQuery($sql));
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    foreach($columns as $column) {
                        echo "<td>{$column["COLUMN_NAME"]}</td>";
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($rows as $row) {
                        echo "<tr>";
                        foreach ($row as $columnValue) {
                            echo "<td>{$columnValue}</td>";
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
</body>

</html>
