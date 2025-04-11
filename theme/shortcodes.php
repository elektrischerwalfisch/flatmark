<?php
/**
 * Shortcode definitions and handlers.
 * 
 * @package flatMark
 * @subpackage Theme
 */

    function processShortcodes($markdown) {

    // Initialize Parsedown
        $Parsedown = new Parsedown();

    // Shortcode: code
        $codeBlocks = [];
        $markdown = preg_replace_callback('/\{code\}(.*?)\{\/code\}/s', function ($matches) use (&$codeBlocks) {
            // Generate a unique placeholder
            $placeholder = '[[[CODEBLOCK_' . count($codeBlocks) . ']]]';
            // Store the raw code block content
            $codeBlocks[$placeholder] = '<pre><code>' . htmlspecialchars($matches[1]) . '</code></pre>';
            return $placeholder;
        }, $markdown);

    // Shortcode: extras for header
        $pattern = '/\{extras\}(.*?)\{\/extras\}/s';
        $markdown = preg_replace_callback($pattern, function ($matches) use ($Parsedown) {
            // Process Markdown inside
            $extrasContent = $Parsedown->text($matches[1]);
            return '<div class="extras">' . $extrasContent . '</div>';
        }, $markdown);
    
    // Shortcode: year (current year)
        $pattern = '/\{year\}/s';
        $markdown = preg_replace_callback($pattern, function () {
            return date("Y"); // Return current year
        }, $markdown);

    // Shortcode: columns with optional classes
        $pattern = '/\{columns(.*?)\}(.*?)\{\/columns\}/s';
        $markdown = preg_replace_callback($pattern, function ($matches) use ($Parsedown) {
            // Capture the additional class (if any exists) directly after {columns}
            $colsAttributes = trim($matches[1]);    // e.g., "50-50" from {background 50-50}
            $colsContent = $matches[2]; // Capture the content inside {columns} ... {/columns}
            
            // Add prefix to the class
            $colsPrefix = 'cols-'; // Define the prefix here
            $colsClass = !empty($colsAttributes) ? $colsPrefix . $colsAttributes : ''; // Prefix added to class (e.g., cols-50-50)

            // Split the columns by {columns-seperator}
            $cols = preg_split('/\{columns-seperator\}/', $colsContent);

            // Wrap each column in <div class="column">
            $colHtml = '';
            foreach ($cols as $col) {
                $colHtml .= '<div class="column">' . $Parsedown->text(trim($col)) . '</div>';
            }

            // Wrap everything in a div and add class if available
            return '<div class="columns ' . $colsClass . '">' . $colHtml . '</div>';
        }, $markdown);

    // Shortcode: background with optional class
        $pattern = '/\{background(.*?)\}(.*?)\{\/background\}/s';
        $markdown = preg_replace_callback($pattern, function ($matches) use ($Parsedown) {
            // Capture the additional class (if any exists) directly after {background}
            $bgAttributes = trim($matches[1]);  // e.g., "color-01" from {background color-01}
            $bgContent = $matches[2];   // Capture the content inside {background} ... {/background}

            // Add prefix to the class
            $bgPrefix = 'bg-'; // Define the prefix here
            $bgClass = !empty($bgAttributes) ? $bgPrefix . $bgAttributes : '';  // Prefix added to class (e.g., bg-color-01)

            // Convert the inner Markdown content
            $bgHtml = $Parsedown->text($bgContent);

            // Wrap everything in a div and add class if available
            return '<div class="background ' . $bgClass . '">' . $bgHtml . '</div>';
        }, $markdown);



    // Shortcode: img rounded
        $pattern = '/\{img-rounded\}(.*?)\{\/img-rounded\}/s';
        $markdown = preg_replace_callback($pattern, function ($matches) use ($Parsedown) {
            // Process Markdown 
            $imgRounded = $Parsedown->text($matches[1]);
            return '<div class="img-rounded">' . $imgRounded . '</div>';
        }, $markdown);


    // Shortcode: code - Restore code blocks (so no shortcode inside was processed)
        foreach ($codeBlocks as $placeholder => $codeBlock) {
            $markdown = str_replace($placeholder, $codeBlock, $markdown);
        }

        return $markdown;
    }

?>


