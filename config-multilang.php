<?php 
    /**
     * Project: FlatMark
     * File: config-multilang.php
     * Description: Configuration for multilingual setup (supports multiple languages).
     * Author: elektrischerwalfisch
     * License: MIT (or another open-source license)
     * Version: 1.0
     */

    // Define supported languages (first one is default)
        $supportedLanguages = ['en', 'de'];     

    // Dynamic definition of content folders  (folders have to be named like /pages-en, /pages-de etc.)
        $languageFolders = [];
        foreach ($supportedLanguages as $lang) {
            $languageFolders[$lang] = __DIR__ . "/pages-$lang/";
        }

    // Detect browser language
        $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);// Get the user's browser language (first two letters), defaulting to 'en' if not set
        $defaultLang = in_array($browserLang, $supportedLanguages) ? $browserLang : $supportedLanguages[0];

    // Handle default language and redirect logic
        if (!in_array($uriParts[0], $supportedLanguages)) {
            header("Location: /$defaultLang");
            exit;
        }

    // Determine the requested language and page from the URL and build the file path
        $lang = $uriParts[0]; // Set language based on the first URL segment
        $page = $uriParts[1] ?? 'home'; // Set page based on the second URL segment, default to 'home'
        $file = $languageFolders[$lang] . $page . '.md'; // Construct the full file path for the Markdown file

    // Define header and footer file paths
        $headerFile = $languageFolders[$lang] . '01-header.md';
        $footerFile = $languageFolders[$lang] . '02-footer.md';

    // Check if file exists and prevent rendering of header/footer files
        if (!file_exists($file) || in_array($page, ['01-header', '02-footer'])) {
            http_response_code(404);
            $file = $languageFolders[$lang] . '404.md'; // Define 404-page
        }

?>