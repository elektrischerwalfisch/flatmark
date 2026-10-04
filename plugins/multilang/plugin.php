<?php
/**
 * Multilang plugin for flatMark.
 *
 * Enable with: $enabledPlugins = ['multilang'];
 * Configure with:
 *   $supportedLanguages = ['en', 'de']; // first entry is fallback / x-default
 *   $siteBaseUrl = 'https://example.com'; // required for hreflang absolute URLs
 *
 * Pages live under content/pages/<lang>/ when this plugin is enabled.
 *
 * Optional page front matter for different slugs per language:
 *   i18n.de: kontakt
 *
 * @package flatMark
 */

$supportedLanguages = $supportedLanguages ?? ['en', 'de'];
$siteBaseUrl = isset($siteBaseUrl) ? rtrim((string) $siteBaseUrl, '/') : '';

$defaultLocales = [
    'en' => 'en-GB',
    'de' => 'de-DE',
];

return function (array $context) use ($supportedLanguages, $siteBaseUrl, $defaultLocales): array {
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
    $context['locale'] = $defaultLocales[$lang] ?? $lang;
    $context['folder'] = $folder;
    $context['page'] = $page;
    $context['file'] = $file;
    $context['headerFile'] = $headerFile;
    $context['footerFile'] = $footerFile;
    $context['supportedLanguages'] = $supportedLanguages;
    $context['siteBaseUrl'] = $siteBaseUrl;
    $context['routed'] = true;

    // Emit hreflang via head hook (soft pairs; skip when siteBaseUrl missing or noindex)
    $xDefaultLang = $supportedLanguages[0];
    flatmark_add_hook('head', function (array $hookContext) use ($supportedLanguages, $siteBaseUrl, $xDefaultLang): void {
        if ($siteBaseUrl === '') {
            return;
        }

        if (($hookContext['httpStatus'] ?? 200) === 404) {
            return;
        }

        $pageMeta = $hookContext['pageMeta'] ?? [];
        $robotsValue = strtolower((string) ($pageMeta['robots'] ?? ''));
        if (strpos($robotsValue, 'noindex') !== false) {
            return;
        }

        $root = $hookContext['root'] ?? '';
        $currentPage = (string) ($hookContext['page'] ?? 'home');

        $isSafePagePath = static function (string $path): bool {
            return $path !== ''
                && strpos($path, '..') === false
                && $path[0] !== '/';
        };

        $buildUrl = static function (string $langCode, string $pagePath) use ($siteBaseUrl): string {
            if ($pagePath === 'home') {
                return $siteBaseUrl . '/' . $langCode;
            }
            return $siteBaseUrl . '/' . $langCode . '/' . $pagePath;
        };

        $hreflangLinks = [];
        foreach ($supportedLanguages as $langCode) {
            $overrideKey = 'i18n.' . $langCode;
            $alternatePage = isset($pageMeta[$overrideKey]) && $pageMeta[$overrideKey] !== ''
                ? (string) $pageMeta[$overrideKey]
                : $currentPage;
            if (!$isSafePagePath($alternatePage)) {
                continue;
            }

            $alternateFile = $root . '/content/pages/' . $langCode . '/' . $alternatePage . '.md';
            if (!file_exists($alternateFile)) {
                continue;
            }

            $hreflangLinks[$langCode] = $buildUrl($langCode, $alternatePage);
        }

        if ($hreflangLinks === []) {
            return;
        }

        foreach ($hreflangLinks as $hreflang => $href) {
            echo '<link rel="alternate" hreflang="' . htmlspecialchars($hreflang) . '" href="' . htmlspecialchars($href) . '">' . "\n";
        }

        if (isset($hreflangLinks[$xDefaultLang])) {
            echo '<link rel="alternate" hreflang="x-default" href="' . htmlspecialchars($hreflangLinks[$xDefaultLang]) . '">' . "\n";
        }
    });

    return $context;
};
