---
title: Plugins
description: Optional flatMark plugins such as multilang routing.
robots: index, follow
---

# Plugins

Optional features live under `plugins/<name>/` and are enabled in `config.php`:

```php
$enabledPlugins = ['multilang'];
```

Each plugin is `plugins/<name>/plugin.php`. A plugin may take over page routing; otherwise flatMark stays single-language under `content/pages/`.

Themes call `flatmark_hook('head')` and `flatmark_hook('footer')`. Plugins may register callbacks with `flatmark_add_hook(...)` when they load.

## Multilang

Shipped with flatMark, disabled by default.

### Enable

```php
$enabledPlugins = ['multilang'];
$supportedLanguages = ['en', 'de']; // first entry is fallback / x-default
$siteBaseUrl = 'https://example.com'; // required for hreflang tags
```

Leave `$enabledPlugins = [];` for single-language sites.

### Content layout

```text
content/pages/en/home.md
content/pages/de/home.md
```

Do not mix flat files in `content/pages/` with language folders. Prefer the same relative slug in every language.

### URLs

- `/en/about` → `content/pages/en/about.md`
- `/` or an unknown first segment → redirect to `/<browser-or-default-lang>`

### SEO (hreflang)

When `$siteBaseUrl` is set, multilang emits `hreflang` and `x-default` link tags for indexable pages. Missing translations are skipped (no error). Pages with `noindex` in `robots` get no hreflang tags.

### Different slugs (optional)

Same slug across languages is the default. To point to another filename, add a flat front-matter key on the current page:

```yaml
---
title: Contact
i18n.de: kontakt
---
```

Values are relative paths under that language folder, without `.md`. Nested paths such as `docs/guide` are allowed. Put matching entries on both sides if you want a consistent two-way map (`i18n.en` / `i18n.de`).

Language codes: [HTML Language Code Reference](https://www.w3schools.com/tags/ref_language_codes.asp)
