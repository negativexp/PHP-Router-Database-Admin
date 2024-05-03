<?php
$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>
<main>
    <header>
        <h1 class="big">Vlastní SQL</h1>
    </header>
    <div class="wrapper">
        <section>
            <article class="w100">
                <form method="post" action="/admin/database/customSql">
                    <label>
                        <textarea cols="70" name="sql"><?= isset($_GET["sql"]) ? urldecode($_GET["sql"]) : ""?></textarea>
                    </label>
                    <label>
                        <span>Dostat result?</span>
                        <input type="checkbox" name="getResult">
                    </label>
                    <input type="submit">
                </form>
                <?php
                if(isset($_SESSION["customsql_result"])) {
                    $result = $_SESSION["customsql_result"];
                    unset($_SESSION["customsql_result"]);

                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    foreach($result[0] as $key => $value) {
                        echo "<td>$key</td>";
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach($result as $row) {
                        echo "<tr>";
                        foreach($row as $value) {
                            echo "<td>$value</td>";
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