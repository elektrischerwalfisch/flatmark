<?php
/**
 * Project: flatMark
 * Version: 2.0.0
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

    // Stop with HTTP 500 and a short message
        $fail = function (string $message): void {
            http_response_code(500);
            echo $message;
            exit;
        };

    // Safe folder name for themes and plugins (blocks path traversal)
        $isSafeName = function ($name): bool {
            return is_string($name) && (bool) preg_match('/^[a-zA-Z0-9_-]+$/', $name);
        };

// ---------------------------------------------------------------------------
// Config
// ---------------------------------------------------------------------------

    // Require site configuration (copy config.example.php to config.php if missing)
        if (!file_exists(__DIR__ . '/config.php')) {
            $fail('Missing config.php. Copy config.example.php to config.php and adjust settings.');
        }

        require __DIR__ . '/config.php';

    // Apply defaults for missing config values
        $lang = $lang ?? 'en';
        $themeName = $themeName ?? 'default';
        $enabledPlugins = $enabledPlugins ?? [];

    // Resolve active theme folder from config ($themeName only; no fallback theme)
        if (!$isSafeName($themeName)) {
            $fail('Invalid theme name in config.php.');
        }

        $themePath = __DIR__ . '/themes/' . $themeName;
        if (!is_dir($themePath) || !file_exists($themePath . '/index.php')) {
            $fail("Theme '{$themeName}' not found in themes folder.");
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
// Hooks
// ---------------------------------------------------------------------------

    // Named hook registry (plugins register; themes call flatmark_hook)
        $flatmarkHooks = [];
        $flatmarkHookContext = [];

        if (!function_exists('flatmark_add_hook')) {
            function flatmark_add_hook(string $name, callable $callback): void
            {
                global $flatmarkHooks;
                $flatmarkHooks[$name][] = $callback;
            }
        }

        if (!function_exists('flatmark_hook')) {
            function flatmark_hook(string $name): void
            {
                global $flatmarkHooks, $flatmarkHookContext;
                foreach ($flatmarkHooks[$name] ?? [] as $callback) {
                    $callback($flatmarkHookContext ?? []);
                }
            }
        }

// ---------------------------------------------------------------------------
// Routing + plugins
// ---------------------------------------------------------------------------

    // Get requested page from URL rewriting
        $requestUri = trim($_SERVER['REQUEST_URI'] ?? '', '/');
        $uriParts = $requestUri === '' ? [] : explode('/', $requestUri);

    // Request context for plugins (return updated array; set routed=true to own page resolution)
        $context = [
            'root' => __DIR__,
            'requestUri' => $requestUri,
            'uriParts' => $uriParts,
            'lang' => $lang,
            'locale' => $lang,
            'themeName' => $themeName,
            'themePath' => $themePath,
            'folder' => __DIR__ . '/content/pages/',
            'page' => null,
            'file' => null,
            'headerFile' => null,
            'footerFile' => null,
            'httpStatus' => 200,
            'routed' => false,
        ];

    // Load enabled plugins in config order (each plugin.php returns a callable)
        foreach ((array) $enabledPlugins as $pluginName) {
            if (!$isSafeName($pluginName)) {
                $fail('Invalid plugin name in $enabledPlugins.');
            }

            $pluginFile = __DIR__ . '/plugins/' . $pluginName . '/plugin.php';
            if (!file_exists($pluginFile)) {
                $fail("Plugin '{$pluginName}' not found.");
            }

            $bootstrap = require $pluginFile;
            $context = is_callable($bootstrap) ? $bootstrap($context) : null;
            if (!is_array($context)) {
                $fail("Plugin '{$pluginName}' must return a context array from a callable.");
            }
        }

    // Default single-language routing if no plugin handled the request
        if (empty($context['routed'])) {
            // Join URL segments to support subfolders, otherwise default to 'home'
            $pagePath = implode('/', $context['uriParts']);
            $page = $pagePath !== '' ? $pagePath : 'home';
            $folder = $context['folder'];
            $file = $folder . $page . '.md';
            $headerFile = $folder . '01-header.md';
            $footerFile = $folder . '02-footer.md';

            // Check if file exists and prevent rendering of header/footer files
            if (!file_exists($file) || in_array($page, ['01-header', '02-footer'], true)) {
                $context['httpStatus'] = 404;
                $file = $folder . '404.md';
            }

            $context['page'] = $page;
            $context['file'] = $file;
            $context['headerFile'] = $headerFile;
            $context['footerFile'] = $footerFile;
            $context['routed'] = true;
        }

        $lang = $context['lang'] ?? $lang;
        $locale = $context['locale'] ?? $lang;
        $page = $context['page'];
        $file = $context['file'];
        $headerFile = $context['headerFile'];
        $footerFile = $context['footerFile'];

        if (($context['httpStatus'] ?? 200) === 404) {
            http_response_code(404);
        }

        if (!$file || !file_exists($file)) {
            $fail('Routed page file is missing.');
        }

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

            // Parse metadata manually (line by line; keys may contain dots, e.g. lang.de)
            foreach (explode("\n", $yaml) as $line) {
                if (preg_match('/^\s*([\w\.-]+):\s*(.*)$/', $line, $meta)) {
                    $key = trim($meta[1]);
                    $value = trim($meta[2]);
                    $pageMeta[$key] = $value;
                }
            }
        }

    // Context passed into hook callbacks at render time
        $flatmarkHookContext = [
            'root' => __DIR__,
            'lang' => $lang,
            'locale' => $locale,
            'page' => $page,
            'pageMeta' => $pageMeta,
            'context' => $context,
            'httpStatus' => $context['httpStatus'] ?? 200,
        ];

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
