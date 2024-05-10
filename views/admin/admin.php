<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("components/sidepanel.php"); ?>
<main>
    <header>
        <h1 class="big">Dashboard</h1>
    </header>
    <div class="wrapper">
        <?php
        var_export(session_status());
        ?>
    </div>
</main>
</body>
</html>