<?php
    include_once("db.php");
    $db = new Database();
    $db->update();
?>
<a href="/admin">Zpátky</a>
<h1>Router</h1>
<h2>Routes</h2>
<?php require_once("components/router/routes.php"); ?>
<h2>Přidat route</h2>
<?php require_once("components/router/addRoute.php"); ?>
<h2>Zablokované složky</h2>
<?php require_once("components/router/blockedFolders.php"); ?>
<h2>Přidat zablokovanou složku</h2>
<?php require_once("components/router/addBlockedFolder.php"); ?>
<h2>Povolené souborové typy</h2>
<?php require_once("components/router/allowedFileTypes.php"); ?>
<h2>Přidat povolenej souborovej typ</h2>
<?php require_once("components/router/addAllowedFileType.php"); ?>
