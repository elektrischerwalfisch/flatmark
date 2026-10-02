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

## Multilang

Shipped with flatMark, disabled by default.

### Enable

```php
$enabledPlugins = ['multilang'];
$supportedLanguages = ['en', 'de']; // first entry is the fallback default
```

Leave `$enabledPlugins = [];` for single-language sites.

### Content layout

```text
content/pages/en/home.md
content/pages/de/home.md
```

Do not mix flat files in `content/pages/` with language folders.

### URLs

- `/en/about` → `content/pages/en/about.md`
- `/` or an unknown first segment → redirect to `/<browser-or-default-lang>`

Language codes: [HTML Language Code Reference](https://www.w3schools.com/tags/ref_language_codes.asp)
