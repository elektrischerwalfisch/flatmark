<?php
    /**
     * Project: FlatMark
     * File: config-basic.php
     * Description: Configuration for a single-language setup (basic configuration).
     * Author: elektrischerwalfisch
     * License: MIT (or another open-source license)
     * Version: 1.0
     */

    // Define page language
        $lang = 'de';

    // Use the first URI segment if available; otherwise default to 'home'
        $page = !empty($uriParts[0]) ? $uriParts[0] : 'home';

    // Set content-folder
        $folder = __DIR__ . '/pages/';
        $file = $folder . $page . '.md';

    // Define header and footer file paths
        $headerFile = $folder . '01-header.md';
        $footerFile = $folder . '02-footer.md';

    // Check if file exists and prevent rendering of header/footer files
        if (!file_exists($file) || in_array($page, ['01-header', '02-footer'])) {
            http_response_code(404);
            $file = $folder . '404.md';
        }

?>