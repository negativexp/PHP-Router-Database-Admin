<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>
<main>
    <header>
        <h1 class="big">Logs</h1>
    </header>
    <div class="wrapper">
        <section>
            <article class="w100 reverse">
                <?php
                include_once("db.php");
                $db = new Database();
                $sql = "select * from ".DB_PREFIX."_logs ORDER BY id DESC";
                $logs = $db->fetchRows($db->executeQuery($sql));
                echo "<table>";
                echo "<thead>";
                echo "<tr>
<td class='fit'>Id</td>
<td>Action route</td>
<td class='fit'>Post array</td>
<td>Time</td>
</tr>";
                echo "</thead>";
                foreach ($logs as $log) {
                    echo "<tr>";
                    echo "<td>{$log['id']}</td>";
                    echo "<td>{$log['route']}</td>";
                    echo "</td>";
                    echo "<td>";
                    $postArr = urldecode($log["postArr"]);
                    $postArr = unserialize($postArr);
                    $columnNames = array_keys($postArr);
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    foreach($columnNames as $name) {
                        echo "<td>{$name}</td>";
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach($columnNames as $name) {
                        if(is_array($postArr[$name])) {
                            echo "<td>";
                            foreach($postArr[$name] as $item) {
                                echo "$item, ";
                            }
                            echo "</td>";
                        } else echo "<td>{$postArr[$name]}</td>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</td>";
                    echo "<td class='fit'>{$log["time"]}</td>";
                    echo "</tr>";
                }
                echo "</table>";
                ?>
            </article>
        </section>
    </div>
</main>
</body>
</html>
