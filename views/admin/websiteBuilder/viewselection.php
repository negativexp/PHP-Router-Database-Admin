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
        <section>
            <?php
            $path = "views";
            $files = scandir($path);
            foreach ($files as $file) {
                if($file != '.' && $file != '..' && $file != "admin") {
                    $href = pathinfo($file,PATHINFO_FILENAME);
                    echo "<a class='small button' href='/admin/websiteBuilder/{$href}' >{$file}</a>";
                }
            }
            ?>
        </section>
    </div>
</main>
</body>
</html>