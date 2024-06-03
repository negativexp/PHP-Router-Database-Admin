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
    fwrite($file, handleFormatting($data));
    fwrite($file, $endStructure);
}

function handleFormatting($data) {
    return handleHeader($data);
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

function handleHeader($json) {
    if (!isset($json['children'])) {
        return jsonToHtml($json);
    }

    $headerHtml = '';
    $otherHtml = '';

    foreach ($json['children'] as $child) {
        if ($child['tag'] === 'header') {
            $headerHtml .= jsonToHtml($child);
        } else {
            $otherHtml .= jsonToHtml($child);
        }
    }

    $mainHtml = sprintf('<main>%s</main>', $otherHtml);

    return $headerHtml . $mainHtml;
}