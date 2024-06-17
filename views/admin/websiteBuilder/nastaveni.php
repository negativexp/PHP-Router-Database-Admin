<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>
<main>
    <header>
        <h1>Web nastavení</h1>
    </header>
    <div class="wrapper">
        <section>
            <article class="w50">
                <h2>Stránky a podstránky</h2>
                <?php

                ?>
                <ol>
                    <li>First</li>
                    <li>Second
                        <ol>
                            <li style="margin-left:1em">Sub of Second</li>
                            <li style="margin-left:1em; padding-bottom: 0;">Another Sub</li>
                        </ol>
                    </li>
                    <li>Third</li>
                    <li>Fourth </li>
                </ol>
                <a class="button small">Vytvořit</a>
            </article>
            <article class="w50">
                <h2>Hlavička</h2>
                <textarea style="min-height: 100px"></textarea>
            </article>
        </section>
    </div>
</main>
</body>
</html>