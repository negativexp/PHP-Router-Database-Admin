<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $file = fopen("views/".$data["viewName"].".php", "w");
    $startStructure = '
<!doctype html>
<html lang="en">
<link rel="stylesheet" href="resources/style.css">
<body>
';
    $endStructure = '
</body>
</html>
';
    fwrite($file, print_r($startStructure, true));
    fwrite($file, print_r(jsonToHtml($data), true));
    fwrite($file, print_r($endStructure, true));
}

function jsonToHtml($json) {
    if (!isset($json['tag'])) {
        return '';
    }

    $tag = $json['tag'];
    $attributes = isset($json['attributes']) ? $json['attributes'] : [];
    $children = isset($json['children']) ? $json['children'] : [];

    $attrString = '';
    foreach ($attributes as $key => $value) {
        $attrString .= sprintf(' %s="%s"', htmlspecialchars($key), htmlspecialchars($value));
    }

    $html = sprintf('<%s%s>', htmlspecialchars($tag), $attrString);

    foreach ($children as $child) {
        if (isset($child['text'])) {
            $html .= htmlspecialchars($child['text']);
        } else {
            $html .= jsonToHtml($child);
        }
    }

    $html .= sprintf('</%s>', htmlspecialchars($tag));

    return $html;
}