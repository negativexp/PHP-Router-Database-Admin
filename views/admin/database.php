<?php
include_once("db.php");
$db = new Database();
$db->update();
?>
<a href="/admin">Zpátky</a>
<h1>Databáze</h1>
<h2>tabulky</h2>
<?php require_once("components/database/tables.php"); ?>

