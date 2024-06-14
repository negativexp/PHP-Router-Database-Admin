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
        <h1 class="big">Vyberte si stránku</h1>
    </header>

    <div class="wrapper">
        <div class="tableOptions">
            <a class="button" onclick="MessageBox('popupForm')">Přidat</a>
        </div>
        <section>
            <article class="w100">
                <table>
                    <thead>
                    <tr>
                        <td>Stránka</td>
                        <td class="fit">Možnosti</td>
                    </tr>
                    </thead>
                    <?php
                    $path = "views";
                    $files = scandir($path);
                    foreach ($files as $file) {
                        if($file != '.' && $file != '..' && $file != "admin") {
                            $href = pathinfo($file,PATHINFO_FILENAME);
                            $fileName = pathinfo($file, PATHINFO_BASENAME);
                            echo "<tr>";
                            echo "<td>{$file}</td>";
                            echo "<td>
<form class='formOptions' method='post' action=''>
<input type='hidden' name='siteName' value='{$fileName}'>
<input type='submit' class='small button' name='delete' value='Smazat'>
<a class='small button' href='/admin/websiteBuilder/{$href}'>Upravit</a>
</form>
</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </article>
            <?php

            ?>
        </section>
    </div>
</main>
</body>
</html>