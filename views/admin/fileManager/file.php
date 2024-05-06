<?php
if(isset($file)) {
    $file = urldecode($file);
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>

<main>
    <header>
        <h1 class="big">Soubor: <?= isset($file) ? $file : "" ?></h1>
    </header>
    <div class="wrapper">
        <form method="post" action="/admin/fileManager">
            <div class="tableOptions">
                <input type="hidden" name="backlink" value="<?= isset($_GET["backlink"]) ? "?folder=".$_GET["backlink"] : ""?>">
                <input type="hidden" name="path" value="<?= isset($_GET["backlink"]) ? "?folder=".$_GET["backlink"] : ""?>">
                <a class="button" href="/admin/fileManager<?= isset($_GET["backlink"]) ? "?folder=".$_GET["backlink"] : ""?>">Zpátky</a>
                <input type="submit" name="saveFile" value="Uložit">
            </div>
            <section>
                <article class="w100">
                    <?php
                    $content = file_get_contents($file)
                    ?>
                    <textarea name="contents" style="height: 500px"><?= $content ?></textarea>
                </article>
            </section>
        </form>
    </div>
</main>
</body>
</html>