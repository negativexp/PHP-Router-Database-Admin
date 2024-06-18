<?php

// Receive JSON data from frontend
$json = file_get_contents('php://input');
$data = json_decode($json, true);

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

        $html .= '<' . $data['tag'];

        // Add attributes except for those that need to be removed
        foreach ($data as $key => $value) {
            if (!in_array($key, ['tag', 'children', 'innerHTML', 'contenteditable', 'onkeydown', 'draggable', 'viewName'])) {
                $html .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
            }
        }

        $html .= '>';

        // Add children or innerHTML
        if (isset($data['children'])) {
            foreach ($data['children'] as $child) {
                $html .= jsonToHTML($child);
            }
        } elseif (isset($data['innerHTML'])) {
            $html .= $data['innerHTML'];
        }

        $html .= '</' . $data['tag'] . '>';
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
?>