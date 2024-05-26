<?php
// Function to recursively generate HTML from array
function arrayToHtml($elements) {
    $html = '';

    foreach ($elements as $element) {
        // Only process children if the current element is not a div with class "webBuilder-block"
        if ($element->tag !== 'div' || !isset($element->attrs->class) || $element->attrs->class !== 'webBuilder-block') {
            $html .= '<' . $element->tag;

            // Add attributes if present and exclude specific attributes if conditions are met
            if (isset($element->attrs)) {
                foreach ($element->attrs as $attr => $value) {
                    if ($attr === 'class') {
                        // Check if classList contains 'editingStyleText'
                        $classList = explode(' ', $value);
                        $filteredClasses = array_filter($classList, function($class) {
                            return $class !== 'editingStyleText';
                        });
                        if (!empty($filteredClasses)) {
                            $html .= ' ' . $attr . '="' . implode(' ', $filteredClasses) . '"';
                        }
                    } elseif (!in_array($attr, ['tabindex', 'onfocus', 'contenteditable', 'spellcheck'])) {
                        $html .= ' ' . $attr . '="' . $value . '"';
                    }
                }
            }

            $html .= '>';

            // Check if there are children elements or text content
            if (isset($element->children) && is_array($element->children)) {
                $hasChildElements = false;
                foreach ($element->children as $child) {
                    if (isset($child->tag)) {
                        $hasChildElements = true;
                        break;
                    }
                }

                if (!$hasChildElements) {
                    // No child elements, so this element may contain text directly
                    foreach ($element->children as $child) {
                        if (!isset($child->tag)) {
                            // Text node, include it directly
                            $html .= $child;
                        }
                    }
                } else {
                    // Recursively process child elements
                    $html .= arrayToHtml($element->children);
                }
            }

            // Close tag
            $html .= '</' . $element->tag . '>';
        } else {
            // Process children of the div with class "webBuilder-block"
            if (isset($element->children) && is_array($element->children)) {
                foreach ($element->children as $child) {
                    // Only add child elements directly without wrapping div
                    $html .= arrayToHtml([$child]);
                }
            }
        }
    }

    return $html;
}
$blocks = json_decode(file_get_contents('php://input'));
$html = arrayToHtml($blocks);
$file = fopen("views/index.php", "w");
fwrite($file, print_r($html, true));