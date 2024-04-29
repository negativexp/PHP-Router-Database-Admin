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
        <form method="post" action="/admin/fileManager">
            <div class="tableOptions">
                <?php
                if(isset($_GET["folder"])) {
                    $folders = explode("\\", $_GET["folder"]);
                    array_pop($folders);
                    if(count($folders) > 0) {
                        $backLink = implode("\\", $folders);
                        echo "<a class='button' href='?folder={$backLink}'>Zpátky</a>";
                    }
                }
                ?>
                <a class="button">Přidat soubor</a>
                <input type="submit" name="delete" value="smazat">
            </div>
            <section>
                <article class="w100">
                    <?php
                        if(isset($_GET["folder"])) {
                            echo "<input type='hidden' name='path' value='".$_SERVER["DOCUMENT_ROOT"].$_GET["folder"]."'>";
                        } else echo "<input type='hidden' name='path' value='{$_SERVER["DOCUMENT_ROOT"]}'>";
                    ?>
                    <table>
                        <thead>
                        <tr>
                            <td class="fit"></td>
                            <td>Název</td>
                            <td>Velikost</td>
                            <td>Poslední modifikace</td>
                            <td class="fit">Options</td>
                        </tr>
                        </thead>
                        <?php
                            $path = isset($_GET["folder"]) ? $_SERVER["DOCUMENT_ROOT"]."\\".$_GET["folder"] : $_SERVER["DOCUMENT_ROOT"];
                            $files = scandir($path);
                            foreach($files as $file) {
                                $fullpath = $path."\\".$file;
                                if($file != "." && $file != "..") {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selected_files[]' value='$fullpath'></td>";
                                    if(is_dir($fullpath))
                                        if(isset($_GET["folder"]))
                                            echo "<td><a href='?folder={$_GET["folder"]}\\{$file}'>{$file}</a></td>";
                                        else echo "<td><a href='?folder=\\{$file}'>{$file}</a></td>";
                                    else echo "<td>{$file}</td>";
                                    if(is_dir($fullpath)) echo "<td>Složka</td>"; else echo "<td>".filesize($fullpath)." (bytes)</td>";
                                    echo "<td>".date("H:i:s d/m/y",filemtime($fullpath))."</td>";
                                    echo "<td><a class='button'>Upravit</a></td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </table>
                </article>
            </section>
        </form>
    </div>
</main>
</body>
</html>