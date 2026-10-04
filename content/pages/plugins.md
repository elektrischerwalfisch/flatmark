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

Detailed setup for each plugin lives in `plugins/<name>/README.md`.

## Multilang

Adds language prefixes in the URL, per-language pages under `content/pages/<lang>/`, browser-language redirect, and optional hreflang tags.

Disabled by default. Full setup: [plugins/multilang/README.md](https://github.com/elektrischerwalfisch/flatmark/blob/development/plugins/multilang/README.md).
