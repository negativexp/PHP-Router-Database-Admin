<?php
$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>
<div id="popupForm" class="popupform">
    <form method="post" action="/admin/fileManager">
        <h2>Přidat fotku</h2>
        <label>
            <span>Název cesty či webové adres</span>
            <input spellcheck="false" type="text" id="imgSrc" required>
        </label>
        <div class="options">
            <a class="small button" onclick="hidePopupForm()">Zavřít</a>
            <a class="small button" type="submit" onclick="addElement('img')">Přidat</a>
        </div>
    </form>
</div>
<div id="popupForm2" class="popupform">
    <form method="post" action="/admin/fileManager">
        <h2>Přidat vlastní HTML/JS/CSS</h2>
        <textarea spellcheck="false" id="customHtml"></textarea>
        <div class="options">
            <a class="small button" onclick="hidePopupForm2()">Zavřít</a>
            <a class="small button" type="submit" onclick="addElement('custom')">Přidat</a>
        </div>
    </form>
</div>
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