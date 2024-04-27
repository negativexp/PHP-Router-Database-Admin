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
                        <textarea cols="70" name="sql"></textarea>
                    </label>
                    <label>
                        <span>Dostat result?</span>
                        <input type="checkbox" name="getResult">
                    </label>
                    <input type="submit">
                </form>
                <?php
                    if(isset($_GET["success"])) {
                        $result = unserialize(urldecode($_GET["success"]));
                        var_dump($result);
                    }
                ?>
            </article>
        </section>
    </div>
</main>
</body>
</html>