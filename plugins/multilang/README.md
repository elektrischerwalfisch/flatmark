# Multilang plugin

Optional language routing and SEO alternates for flatMark. Shipped with the product, disabled by default.

## Enable

In `config.php`:

```php
$enabledPlugins = ['multilang'];
$supportedLanguages = ['en', 'de']; // first entry is fallback / x-default
$siteBaseUrl = 'https://example.com'; // required for hreflang tags
```

Leave `$enabledPlugins = [];` for single-language sites.

## Content layout

```text
content/pages/en/home.md
content/pages/de/home.md
```

Do not mix flat files in `content/pages/` with language folders. Prefer the same relative slug in every language.

## URLs

- `/en/about` → `content/pages/en/about.md`
- `/` or an unknown first segment → redirect to `/<browser-or-default-lang>`

## SEO (hreflang)

When `$siteBaseUrl` is set, multilang emits `hreflang` and `x-default` link tags for indexable pages via the `head` hook. Missing translations are skipped (no error). Pages with `noindex` in `robots` get no hreflang tags.

## Different slugs (optional)

Same slug across languages is the default. To point to another filename, add a flat front-matter key on the current page:

```yaml
---
title: Contact
i18n.de: kontakt
---
```

Values are relative paths under that language folder, without `.md`. Nested paths such as `docs/guide` are allowed. Put matching entries on both sides if you want a consistent two-way map (`i18n.en` / `i18n.de`).

Language codes: [HTML Language Code Reference](https://www.w3schools.com/tags/ref_language_codes.asp)
