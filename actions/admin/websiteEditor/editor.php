<?php
$blocks = json_decode(file_get_contents('php://input'));

$filename = 'output.txt';
$file = fopen($filename, 'w') or die("Unable to open file!");

// Output to file instead of echoing
ob_start(); // Start output buffering to capture output

// Example: Accessing and writing data to file
fwrite($file, "Tag of the outermost element: " . $blocks->tag . "\n");

// Accessing attributes of the outermost element
fwrite($file, "ID attribute of the outermost element: " . $blocks->attrs->id . "\n");

// Accessing children elements
fwrite($file, "Number of children elements: " . count($blocks->children) . "\n");

// Iterating through children elements
foreach ($blocks->children as $index => $child) {
    fwrite($file, "Child " . ($index + 1) . " tag: " . $child->tag . "\n");

    // Accessing attributes of child elements
    fwrite($file, "Child " . ($index + 1) . " class attribute: " . $child->attrs->class . "\n");

    // Accessing nested children elements
    foreach ($child->children as $nestedChild) {
        fwrite($file, "Nested child tag: " . $nestedChild->tag . "\n");
        // Accessing text content of nested children (if any)
        if (isset($nestedChild->children[0])) {
            fwrite($file, "Nested child text content: " . $nestedChild->children[0] . "\n");
        }
    }
}

// Close the file
fclose($file);

// End output buffering and write captured output to the file
file_put_contents($filename, ob_get_flush(), FILE_APPEND);