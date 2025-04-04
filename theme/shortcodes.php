<?php

    function processShortcodes($markdown) {

    // Initialize Parsedown
        $Parsedown = new Parsedown();

    // Shortcode: language menu
        $pattern = '/\{langmenu\}(.*?)\{\/langmenu\}/s';
        $markdown = preg_replace_callback($pattern, function ($matches) use ($Parsedown) {
            // Process Markdown inside the language menu
            $langLinks = $Parsedown->text($matches[1]);
            return '<div id="lang">' . $langLinks . '</div>';
        }, $markdown);
    
    // Shortcode: year (current year)
        $pattern = '/\{year\}/s';
        $markdown = preg_replace_callback($pattern, function () {
            return date("Y"); // Return current year
        }, $markdown);

    // Shortcode: columns with optional classes
        $pattern = '/\{columns(.*?)\}(.*?)\{\/columns\}/s';
        $markdown = preg_replace_callback($pattern, function ($matches) use ($Parsedown) {
            // Capture the class (if any) directly after {columns}
            $attributes = trim($matches[1]); // Capture the class (e.g., 50-50)
            $colsContent = $matches[2]; // Capture the content inside {columns} ... {/columns}
            
            // Add prefix to the class
            $prefix = 'c-'; // Define the prefix here
            $class = !empty($attributes) ? $prefix . $attributes : ''; // Prefix added to class

            // Split the columns by {columns-seperator}
            $cols = preg_split('/\{columns-seperator\}/', $colsContent);

            // Wrap each column in <div class="col">
            $colHtml = '';
            foreach ($cols as $col) {
                $colHtml .= '<div class="column">' . $Parsedown->text(trim($col)) . '</div>';
            }

            // Wrap everything in a <div class="cols"> and add class if available
            return '<div class="columns ' . $class . '">' . $colHtml . '</div>';
        }, $markdown);

    // Shortcode: bg color
        $pattern = '/\{bg-color\}(.*?)\{\/bg-color\}/s';
        $markdown = preg_replace_callback($pattern, function ($matches) use ($Parsedown) {
            // Process Markdown
            $bgColor = $Parsedown->text($matches[1]);
            return '<div class="bg-color">' . $bgColor . '</div>';
        }, $markdown);

    // Shortcode: img rounded
        $pattern = '/\{img-rounded\}(.*?)\{\/img-rounded\}/s';
        $markdown = preg_replace_callback($pattern, function ($matches) use ($Parsedown) {
            // Process Markdown 
            $imgRounded = $Parsedown->text($matches[1]);
            return '<div class="img-rounded">' . $imgRounded . '</div>';
        }, $markdown);

        return $markdown;
    }

?>


