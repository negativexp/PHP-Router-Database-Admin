<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $filePath = "views/" . $data["viewName"] . ".php";
    $file = fopen($filePath, "w");

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

    // Start writing the basic HTML structure
    fwrite($file, $startStructure);

    // Separate header, footer, and main content
    $headerHtml = '';
    $footerHtml = '';
    $mainContentHtml = '';

    foreach ($data['blocks'] as $block) {
        $blockHtml = jsonToHtml($block);

        if ($block['tag'] === 'header') {
            $headerHtml .= $blockHtml;
        } elseif ($block['tag'] === 'footer') {
            $footerHtml .= $blockHtml;
        } else {
            $mainContentHtml .= $blockHtml;
        }
    }

    // Write header if exists
    fwrite($file, $headerHtml);

    // Write main content inside <main> tag
    fwrite($file, '<main>' . $mainContentHtml . '</main>');

    // Write footer if exists
    fwrite($file, $footerHtml);

    // Write the end structure
    fwrite($file, $endStructure);

    // Close the file
    fclose($file);
}

function jsonToHtml($json) {
    if (!isset($json['tag'])) {
        return '';
    }

    $tag = $json['tag'];
    $attributes = isset($json['attributes']) ? $json['attributes'] : [];
    $children = isset($json['children']) ? $json['children'] : [];
    $text = isset($json['children'][0]["text"]) ? $json['children'][0]["text"] : '';

    // Construct the HTML element with attributes
    $attrString = '';
    foreach ($attributes as $key => $value) {
        $attrString .= " {$key}=\"" . htmlspecialchars($value) . "\"";
    }

    // Start the tag
    $html = "<{$tag}{$attrString}>";

    // Add text content if present
    if (!empty($text)) {
        $html .= htmlspecialchars($text);
    }

    // Recursively add children elements
    foreach ($children as $child) {
        $html .= jsonToHtml($child);
    }

    // Close the tag
    $html .= "</{$tag}>";

    return $html;
}