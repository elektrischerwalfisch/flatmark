<?php
    /**
     * Project: FlatMark
     * File: index.php
     * Description: Main entry point for the website. 
     *              Dynamically loads Markdown content, basic or multilingual configuration and shortcode-settings.
     * Author: elektrischerwalfisch
     * License: MIT (or another open-source license)
     * Version: 1.0
     */

     // Start output buffering (prevents page to "jump" before everything is loaded)
        ob_start();

    // Include shortcode functions (if file exists)
        if (file_exists('theme/shortcodes.php')) {
            require 'theme/shortcodes.php';
        }
    
    // Include Parsedown
        require 'theme/libs/Parsedown.php';
        $Parsedown = new Parsedown();

    // Get requested language and page from URL rewriting
        $requestUri = trim($_SERVER['REQUEST_URI'], '/');
        $uriParts = explode('/', $requestUri);

    // Multilingual setup. If your site is a single-language setup, change 'config-multilang.php' to 'config-basic.php'
        require 'config-multilang.php';

    // Read the content of the requested Markdown file into a string
        $markdown = file_get_contents($file);
            
    // Set defaults for Titel and meta-values
        $defaultPageTitle = ucfirst($page);
        $defaultMetaDescription = "";
        $defaultMetaRobots = "index, follow";

    // Overwrite defaults by extracting individual title, description and robots from the first lines of the markdown file
        $pageTitle = $defaultPageTitle;
        $metaDescription = $defaultMetaDescription;
        $metaRobots = $defaultMetaRobots;
        if (preg_match('/^<!--\s*title:(.*?)\s*-->$/m', $markdown, $matches)) {
            $pageTitle = trim($matches[1]);
        }
        if (preg_match('/^<!--\s*description:(.*?)\s*-->$/m', $markdown, $matches)) {
            $metaDescription = trim($matches[1]);
        }
        if (preg_match('/^<!--\s*robots:(.*?)\s*-->$/m', $markdown, $matches)) {
            $metaRobots = trim($matches[1]);
        }

    // Load header and footer markdown files and convert to HTML
        $headerContent = '';
        if (file_exists($headerFile)) {
            $headerContent = file_get_contents($headerFile);
            // Apply theme/functions.php before converting to HTML (if function exists)
            if (function_exists('processShortcodes')) {
                $headerContent = processShortcodes($headerContent);
            }
            // Convert Markdown to HTML
            $headerContent = $Parsedown->text($headerContent);
        }
        $footerContent = '';
        if (file_exists($footerFile)) {
            $footerContent = file_get_contents($footerFile);
            // Apply theme/functions.php before converting to HTML (if function exists)
            if (function_exists('processShortcodes')) {
                $footerContent = processShortcodes($footerContent);
            }
            // Convert Markdown to HTML
            $footerContent = $Parsedown->text($footerContent);
        }

    // Apply theme/shortcodes.php before converting to HTML (if function exists)
        if (function_exists('processShortcodes')) {
            $markdown = processShortcodes($markdown);
        }

    // Convert Markdown to HTML
        $htmlContent = $Parsedown->text($markdown);

?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($pageTitle) ?></title>
        <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
        <meta name="robots" content="<?= htmlspecialchars($metaRobots) ?>">
        <link rel="stylesheet" href="/theme/css/style.css">
    </head>
    <body>
        <div id="wrapper">
            <header>	
                <?= $headerContent ?>
            </header>
            <main id="main">
                <?= $htmlContent ?>
            </main>
            <footer>
                <?= $footerContent ?>
            </footer>
            <script src="/theme/js/presets.js"></script>
        </div>
    </body>
</html>

<?php
    ob_end_flush(); // Flush output buffer
?>