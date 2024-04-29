<?php
$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("components/sidepanel.php"); ?>
<main>
    <header>
        <h1 class="big">File Manager</h1>
    </header>
    <div class="wrapper">
        <form>
            <div class="tableOptions">
                <a class="button">Přidat soubor</a>
                <a class="button">Smazat</a>
            </div>
            <section>
                <article class="w100">
                    <table>
                        <thead>
                        <tr>
                            <td class="fit"></td>
                            <td>Název</td>
                            <td>Velikost</td>
                            <td>Poslední modifikace</td>
                            <td class="fit">Options</td>
                        </tr>
                        <?php
                            $files = scandir($_SERVER["DOCUMENT_ROOT"]);
                            foreach($files as $file) {
                                if($file != "." && $file != "..") {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox'></td>";
                                    if(is_dir($file)) echo "<td><a href='?folder={$file}'>{$file}</a></td>"; else echo "<td>{$file}</td>";
                                    if(is_dir($file)) echo "<td>Složka</td>"; else echo "<td>".filesize($file)." (bytes)</td>";
                                    echo "<td>".date("H:i:s d/m/y",filemtime($file))."</td>";
                                    echo "<td></td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                        </thead>
                    </table>
                </article>
            </section>
        </form>
    </div>
</main>
</body>
</html>