<?php
/**
 * Project: flatMark
 * Version: 2.0.0-dev
 *
 * Project URI: https://github.com/elektrischerwalfisch/flatmark
 * Author: elektrischerwalfisch
 * Author URI: https://www.elektrischerwalfisch.de
 * License: MIT
 */

// ---------------------------------------------------------------------------
// Bootstrap
// ---------------------------------------------------------------------------

    // Start output buffering (prevents page to "jump" before everything is loaded)
        ob_start();

// ---------------------------------------------------------------------------
// Config
// ---------------------------------------------------------------------------

    // Require site configuration (copy config.example.php to config.php if missing)
        if (!file_exists(__DIR__ . '/config.php')) {
            http_response_code(500);
            echo 'Missing config.php. Copy config.example.php to config.php and adjust settings.';
            exit;
        }

        require __DIR__ . '/config.php';

    // Apply defaults for missing config values
        $lang = $lang ?? 'en';
        $themeName = $themeName ?? 'default';
        $enabledPlugins = $enabledPlugins ?? [];

    // Resolve active theme folder from config
        $themePath = __DIR__ . '/themes/' . $themeName;
        if (!is_dir($themePath)) {
            http_response_code(500);
            echo "Theme '{$themeName}' not found in themes folder.";
            exit;
        }

// ---------------------------------------------------------------------------
// Theme + libraries
// ---------------------------------------------------------------------------

    // Include shortcode functions (if file exists)
        if (file_exists($themePath . '/functions.php')) {
            require $themePath . '/functions.php';
        }

    // Include Parsedown
        require __DIR__ . '/vendor/Parsedown.php';
        $Parsedown = new Parsedown();

// ---------------------------------------------------------------------------
// Routing (single-language default)
// ---------------------------------------------------------------------------

    // Get requested page from URL rewriting
        $requestUri = trim($_SERVER['REQUEST_URI'] ?? '', '/');
        $uriParts = $requestUri === '' ? [] : explode('/', $requestUri);

    // Join all available URL segments to also support subfolders, otherwise default to 'home'
        $pagePath = implode('/', $uriParts);
        $page = $pagePath !== '' ? $pagePath : 'home';

    // Set content folder and build Markdown file paths
        $folder = __DIR__ . '/content/pages/';
        $file = $folder . $page . '.md';
        $headerFile = $folder . '01-header.md';
        $footerFile = $folder . '02-footer.md';

    // Check if file exists and prevent rendering of header/footer files
        if (!file_exists($file) || in_array($page, ['01-header', '02-footer'], true)) {
            http_response_code(404);
            $file = $folder . '404.md';
        }

// ---------------------------------------------------------------------------
// Plugins
// ---------------------------------------------------------------------------

    // Plugin loading comes in a later step. $enabledPlugins is reserved for that.

// ---------------------------------------------------------------------------
// Render
// ---------------------------------------------------------------------------

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
            // Apply theme functions before converting to HTML (if function exists)
            if (function_exists('processShortcodes')) {
                $headerContent = processShortcodes($headerContent);
            }
            // Convert Markdown to HTML
            $headerContent = $Parsedown->text($headerContent);
        }
        $footerContent = '';
        if (file_exists($footerFile)) {
            $footerContent = file_get_contents($footerFile);
            // Apply theme functions before converting to HTML (if function exists)
            if (function_exists('processShortcodes')) {
                $footerContent = processShortcodes($footerContent);
            }
            // Convert Markdown to HTML
            $footerContent = $Parsedown->text($footerContent);
        }

    // Apply theme functions before converting to HTML (if function exists)
        if (function_exists('processShortcodes')) {
            $markdown = processShortcodes($markdown);
        }

    // Convert Markdown to HTML
        $htmlContent = $Parsedown->text($markdown);

    // Load HTML template
        $layout = $pageMeta['layout'] ?? 'index'; // Set template from pageMeta or use theme index.php as fallback
        $templateFile = $themePath . '/' . $layout . '.php'; // Build full path to template
        // Show warning if template does not exist
        if (!file_exists($templateFile)) {
            http_response_code(500);
            echo "Template '{$layout}.php' not found in theme folder.";
            exit;
        }
        require $templateFile; // Load template

    // Flush output buffer
        ob_end_flush();
