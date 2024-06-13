<?php

// Receive JSON data from frontend
$json = file_get_contents('php://input');
$data = json_decode($json);

// Function to convert JSON to HTML string
function jsonToHTML($data) {
    $html = '';

    if (isset($data->tag)) {
        $html .= '<' . $data->tag;

        // Add attributes
        if (isset($data->attributes)) {
            foreach ($data->attributes as $key => $value) {
                $html .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
            }
        }

        $html .= '>';

        // Add children if present
        if (isset($data->children)) {
            foreach ($data->children as $child) {
                $html .= jsonToHTML($child);
            }
        } elseif (isset($data->text)) {
            $html .= htmlspecialchars($data->text);
        }

        $html .= '</' . $data->tag . '>';
    }

    return $html;
}

// Convert JSON data to HTML string
$htmlString = jsonToHTML($data);

// Save HTML string to file
$site = fopen("index.php", "w");
fwrite($site, $htmlString);