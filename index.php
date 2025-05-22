<?php
/**
 * Project: flatMark
 * Version: 1.1.1
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

    // Set default metadata values in global scope
        $pageMeta = [
            'title' => ucfirst($page),
            'description' => '',
            'robots' => 'index, follow',
        ];

    // Detect and extract metadata (YAML Front Matter)
        if (preg_match('/^---\s*(.*?)\s*---\s*(.*)$/s', $markdown, $matches)) {
            $yaml = $matches[1];
            $markdown = $matches[2]; // Markdown content without metadata

            // Parse metadata manually (line by line)
            foreach (explode("\n", $yaml) as $line) {
                if (preg_match('/^\s*([\w\-]+):\s*(.*)$/', $line, $meta)) {
                    $key = trim($meta[1]);
                    $value = trim($meta[2]);
                    $pageMeta[$key] = $value;
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

    // Load HTML template
        $layout = $pageMeta['layout'] ?? 'index'; // Set template from pageMeta or use /theme/index.php as fallback
        $templateFile = __DIR__ . "/theme/{$layout}.php"; // Build full path to template
        // Show warning if template does not exist
        if (!file_exists($templateFile)) {
            http_response_code(500);
            echo "Template '{$layout}.php' not found in theme folder.";
            exit;
        }
        require $templateFile; // Load template

    // Flush output buffer
        ob_end_flush(); 
?>