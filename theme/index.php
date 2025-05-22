<?php
/**
 * Main HTML Template
 *
 * This template outputs the complete HTML structure of the site.
 * It is loaded by the main index.php as default template after parsing content and metadata.
 *
 * @package flatMark
 * @subpackage Theme
 * 
 */
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($pageMeta['title']) ?></title>
        <meta name="description" content="<?= htmlspecialchars($pageMeta['description']) ?>">
        <meta name="robots" content="<?= htmlspecialchars($pageMeta['robots']) ?>">
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