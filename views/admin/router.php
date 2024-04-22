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
    <link rel="stylesheet" href="../adminStyle.css">
    <script defer src="../adminScript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<div id="alert">
    <form>
        <label>
            <span>Route</span>
            <input type="text" name="route" required>
        </label>
        <label>
            <span>Type</span>
            <select name="type" required>
                <option>get</option>
                <option>post</option>
                <option>getpost</option>
                <option>put</option>
                <option>patch</option>
                <option>delete</option>
                <option>any</option>
            </select>
        </label>
        <label>
            <span>Path</span>
            <input type="text" name="path" required>
        </label>
        <div class="options">
            <input type="submit">
            <a class="button" onclick="hideAlert()">Zavřít</a>
        </div>
    </form>
</div>
<?php include_once("components/sidepanel.php"); ?>

<main>
    <header>
        <h1 class="big">Router</h1>
    </header>
    <div class="wrapper">
        <div class="tableOptions">
            <a class="button" onclick="displayAlert()">Přidat</a>
        </div>
        <section>
            <article class="w100">
                <h2>Routy</h2>
                <?php require_once("components/router/routes.php") ?>
            </article>
        </section>
    </div>
</main>
</body>

</html>