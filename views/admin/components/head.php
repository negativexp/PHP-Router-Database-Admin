<?php
$parsedURL = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parsedURL = str_replace("/admin", "", $parsedURL);
$count = substr_count($parsedURL, "/");
$string = str_repeat("../", $count);
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= $string ?>resources/admin/adminStyle.css">
    <script defer src="<?= $string ?>resources/admin/adminScript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
