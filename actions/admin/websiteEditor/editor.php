<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $file = fopen("views/".$data["viewName"].".php", "w");
    $startStructure = '
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="resources/style.css">
</head>
<body>
';
    $endStructure = '
</body>
</html>
';
    fwrite($file, $startStructure);
    fwrite($file, print_r(jsonToHtml($data), true));
    fwrite($file, $endStructure);
}

function jsonToHtml($json) {
    $output = "";
    if(isset($json["blocks"])) {
        foreach ($json["blocks"] as $block) {
            if (!isset($block['tag'])) {
                return '';
            } else {
                $tag = $block["tag"];
                $attrs = $block["attributes"];
                $output .= "<{$tag} {$attrs}"."<br>";
            }
        }
        return $output;
    }
}