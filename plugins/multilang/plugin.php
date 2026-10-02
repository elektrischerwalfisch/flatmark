<?php
/**
 * Multilang plugin for flatMark.
 *
 * Enable with: $enabledPlugins = ['multilang'];
 * Configure with: $supportedLanguages = ['en', 'de']; // first entry is fallback default
 *
 * Pages live under content/pages/<lang>/ when this plugin is enabled.
 *
 * @package flatMark
 */

$supportedLanguages = $supportedLanguages ?? ['en', 'de'];

return function (array $context) use ($supportedLanguages): array {
    $uriParts = $context['uriParts'];
    $firstSegment = $uriParts[0] ?? '';

    $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
    $defaultLang = in_array($browserLang, $supportedLanguages, true)
        ? $browserLang
        : $supportedLanguages[0];

    // Redirect to /<lang> when the first segment is not a supported language
    if (!in_array($firstSegment, $supportedLanguages, true)) {
        header('Location: /' . $defaultLang);
        exit;
    }

    $lang = $firstSegment;
    $pagePath = implode('/', array_slice($uriParts, 1));
    $page = $pagePath !== '' ? $pagePath : 'home';
    $folder = $context['root'] . '/content/pages/' . $lang . '/';
    $file = $folder . $page . '.md';
    $headerFile = $folder . '01-header.md';
    $footerFile = $folder . '02-footer.md';

    if (!file_exists($file) || in_array($page, ['01-header', '02-footer'], true)) {
        $context['httpStatus'] = 404;
        $file = $folder . '404.md';
    }

    $context['lang'] = $lang;
    $context['folder'] = $folder;
    $context['page'] = $page;
    $context['file'] = $file;
    $context['headerFile'] = $headerFile;
    $context['footerFile'] = $footerFile;
    $context['routed'] = true;

    return $context;
};
