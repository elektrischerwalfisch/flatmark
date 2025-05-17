<?php
/**
 * Project: flatMark
 * Version: 1.0
 * 
 * Project URI: https://github.com/elektrischerwalfisch/flatmark
 * Author: elektrischerwalfisch
 * Author URI: https://www.elektrischerwalfisch.de
 * License: MIT
 */

     // Start output buffering (prevents page to "jump" before everything is loaded)
        ob_start();

    // Include shortcode functions (if file exists)
        if (file_exists('theme/functions.php')) {
            require 'theme/functions.php';
        }
    
    // Include Parsedown
        require 'theme/libs/Parsedown.php';
        $Parsedown = new Parsedown();

    // Get requested language and page from URL rewriting
        $requestUri = trim($_SERVER['REQUEST_URI'], '/');
        $uriParts = explode('/', $requestUri);

    // Default: multilingual setup. If your site is a single-language setup, change 'config-multilang.php' to 'config-basic.php' in the next line 
        require 'config-multilang.php';

    // Read the content of the requested Markdown file into a string
        $markdown = file_get_contents($file);
            
    // Set defaults for page-titel and meta-values
        $pageTitle = ucfirst($page);
        $metaDescription = "";
        $metaRobots = "index, follow";

    // Detect and extract YAML Front Matter
        if (preg_match('/^---\s*(.*?)\s*---\s*(.*)$/s', $markdown, $matches)) {
            $yaml = $matches[1];
            $markdown = $matches[2]; // Markdown content without front matter

            // Parse YAML (basic parser without dependencies)
            foreach (explode("\n", $yaml) as $line) {
                if (preg_match('/^\s*(title|description|robots):\s*(.*)$/', $line, $meta)) {
                    $key = trim($meta[1]);
                    $value = trim($meta[2]);
                    if ($key === 'title') $pageTitle = $value;
                    if ($key === 'description') $metaDescription = $value;
                    if ($key === 'robots') $metaRobots = $value;
                }
            }
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

    // Apply theme/functions.php before converting to HTML (if function exists)
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