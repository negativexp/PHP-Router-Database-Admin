<?php

// Receive JSON data from frontend
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Function to clean innerHTML by removing specific attributes and handling class 'active'
function cleanInnerHTML($html) {
    $doc = new DOMDocument('1.0', 'UTF-8');
    @$doc->loadHTML('<div>' . mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $xpath = new DOMXPath($doc);
    foreach (['onmousedown', 'onkeydown', 'draggable'] as $attr) {
        foreach ($xpath->query("//*[@$attr]") as $node) {
            $node->removeAttribute($attr);
        }
    }

    foreach ($xpath->query("//*[@class]") as $node) {
        $classes = explode(' ', $node->getAttribute('class'));
        $filteredClasses = array_filter($classes, function($class) {
            return trim($class) !== 'active';
        });
        if (empty($filteredClasses)) {
            $node->removeAttribute('class');
        } else {
            $node->setAttribute('class', implode(' ', $filteredClasses));
        }
    }

    foreach ($xpath->query("//*[@style]") as $node) {
        if (empty(trim($node->getAttribute('style')))) {
            $node->removeAttribute('style');
        }
    }

    // Extract inner content of the temporary div
    $innerHTML = '';
    foreach ($doc->documentElement->childNodes as $child) {
        $innerHTML .= $doc->saveHTML($child);
    }

    return $innerHTML;
}

// Function to convert JSON to HTML string with specific transformations
function jsonToHTML($data) {
    $html = '';

    if (isset($data['tag'])) {
        // Transform specific tags
        if ($data['tag'] === 'div' && isset($data['id'])) {
            if ($data['id'] === 'webBuilder-Body') {
                $data['tag'] = 'body';
                unset($data['id']);
            } elseif ($data['id'] === 'webBuilder-Main') {
                $data['tag'] = 'main';
                unset($data['id']);
            }
        }

        // Extract elements from div with class 'webBuilder-block'
        if ($data['tag'] === 'div' && isset($data['class']) && strpos($data['class'], 'webBuilder-block') !== false) {
            if (isset($data['children'])) {
                foreach ($data['children'] as $child) {
                    $html .= jsonToHTML($child);
                }
            }
            return $html;
        }

        $html .= '<' . htmlspecialchars($data['tag'], ENT_QUOTES, 'UTF-8');

        // Add attributes except for those that need to be removed or are empty
        foreach ($data as $key => $value) {
            if (!in_array($key, ['tag', 'children', 'innerHTML', 'contenteditable', 'onkeydown', 'onmousedown', 'draggable', 'viewName']) && $value !== '') {
                // Handle class attribute
                if ($key === 'class') {
                    $classes = explode(' ', $value);
                    $filteredClasses = array_filter($classes, function($class) {
                        return trim($class) !== 'active';
                    });
                    if (empty($filteredClasses)) {
                        continue;
                    }
                    $value = implode(' ', $filteredClasses);
                }
                // Exclude empty class and style attributes
                if (($key === 'class' || $key === 'style') && empty(trim($value))) {
                    continue;
                }
                $html .= ' ' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
            }
        }

        $html .= '>';

        // Add children or innerHTML
        if (isset($data['children'])) {
            foreach ($data['children'] as $child) {
                $html .= jsonToHTML($child);
            }
        } elseif (isset($data['innerHTML'])) {
            $html .= cleanInnerHTML($data['innerHTML']);
        }

        $html .= '</' . htmlspecialchars($data['tag'], ENT_QUOTES, 'UTF-8') . '>';
    }

    return $html;
}

// Ensure the viewName is set and sanitize it
$viewName = isset($data["viewName"]) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $data["viewName"]) : 'default_view';
unset($data["viewName"]); // Remove viewName from the data before converting to HTML

// Convert JSON data to HTML string
$headString = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Hello, world!</title>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="resources/style.css">
</head>
<body id="webBuilder-Body">
';
$htmlString = jsonToHTML($data);
$htmlEndString = '
</body>
</html>
';

// Save HTML string to file
$site = fopen("views/{$viewName}.php", "w");
fwrite($site, $headString);
fwrite($site, $htmlString);
fwrite($site, $htmlEndString);
fclose($site);