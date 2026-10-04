# flatMark

*A lightweight, flat-file Markdown-based website generator*

## Features  
- Lightweight and fast
- Easy-to-edit content, just Markdown files  
- Just requires basic php, no database or build-steps needed
- Auto-parses **Markdown** to HTML using [Parsedown](https://parsedown.org/)  
- Single-language by default (optional multilang plugin for routing and hreflang)
- **Metadata** for title, description, and robots meta tag for each site (YAML front matter)
- Provides simple **Shortcodes** to arrange and style your content (e.g. columns, different backgrounds)
- Basic, responsive **Theme** which you can customize and enhance  
- Option to use individual **Templates** for single pages

## Demo
The [Demo Website](https://flatmark.elektrischerwalfisch.de) is an exact copy of the [GitHub Repository](https://github.com/elektrischerwalfisch/flatmark) and doubles as the documentation site. 

## How It Works  
FlatMark dynamically converts Markdown files in `content/pages/` into HTML pages. Find the full markdown-syntax here: [www.markdownguide.org/basic-syntax](https://www.markdownguide.org/basic-syntax/)  

## URL Structure  

| Example URL | Maps to File             |
| ----------- | ------------------------ |
| `/about`    | `content/pages/about.md` |

For multilang URLs such as `/en/about`, see the demo page [Plugins](https://flatmark.elektrischerwalfisch.de/plugins).

## Folder Structure

    /flatmark/
    │── /content/
    │   │── /files/             # Files (images, pdfs etc.)
    │   └── /pages/             # Markdown pages
    │── /themes/
    │   └── /default/           # Shipped theme (styles, assets, templates)
    │── /plugins/
    │   └── /multilang/         # Optional language routing (disabled by default)
    │── /vendor/
    │   └── Parsedown.php       # Third-party Markdown parser
    │── config.example.php      # Sample settings (copy to config.php)
    │── config.php              # Site settings (required, gitignored)
    │── index.php               # Main file
    │── .htaccess               # URL rewriting
    │── README.md               # Documentation

## Installation  
1. **Download** the [latest release](https://github.com/elektrischerwalfisch/flatmark/releases/latest) of flatMark which contains a simple example-page
2. **Upload** the files to your web server.  
3. **Configuration** copy `config.example.php` to `config.php` and set `$lang` and `$themeName`
4. **Edit content** inside `content/pages/`  
5. Done! Your site is ready.  

## Requirements  
- PHP 7.4+  
- Apache/Nginx with mod_rewrite enabled 

## Configuration  
Site settings live in `config.php` (gitignored). Copy `config.example.php` to `config.php` for local installs and for the demo deploy.

If `config.php` is missing, flatMark stops with a clear setup error.

Default values:

```php
$lang = 'en';
$themeName = 'default';
$enabledPlugins = [];
```

- `$lang` — HTML language code of the site  
- `$themeName` — theme folder under `themes/`  
- `$enabledPlugins` — optional plugin folders under `plugins/` (empty by default); see [Plugins](#plugins)

Find available language-codes here: [HTML Language Code Reference](https://www.w3schools.com/tags/ref_language_codes.asp) 

## Defaults
`content/pages/` must contain at least these files for the website to function:  

- 01-header.md  
Edit this file to change logo, title, subtitle of the website and the main menu. The main-menu must be a list of links to function correctly. You also have the option to add further elements like contact-details by wrapping them in the shortcode `{extras}` `{/extras}`. 

- 02-footer.md  
Edit this file to change the text in the footer and the footer-menu. The footer-menu must also be a list of links to function correctly. The shortcode `{year}` will display the current year. 

- home.md  
This file is the default startpoint of your website.   

- 404.md  
Edit this file to change the error-message which is shown if a page is not found. 


## Metadata
Each Markdown page can include optional metadata at the top of the file (Format: YAML front matter).
These values will be automatically extracted and used in the <head> section of the generated HTML page.

Example Markdown file (about.md) with metadata:

    ---
    title: About Us
    description: Learn more about our mission and team.
    robots: index, follow
    ---

    # Welcome to Our Company

    We are committed to providing the best services...

- **title** → Sets the `<title>` of the page. Defaults to the filename if not provided.
- **description** → Used for the `<meta name="description">` tag (important for SEO). Defaults to an empty string if not set.
- **robots** → Controls search engine indexing (index, follow / noindex, nofollow). Defaults to index, follow.
- **layout** → Sets individual template for the page, find further infos below under "Themes".

## Shortcodes
flatMark supports simple shortcodes for structured content. You can see all shortcodes in action on the [Shortcodes page](https://flatmark.elektrischerwalfisch.de/shortcodes) of the Demo Website. Here are just two examples:

    {columns 50-50}
    Left column
    {columns-seperator}
    Right column
    {/columns}

    {background color-01}
    This content has a colored background.
    {/background}
  
These shortcodes are part of the theme and are all located in the file `themes/default/functions.php`.
You can edit this file to change existing shortcodes or add even more.
A demo page with all shortcodes is provided with the installation: `content/pages/shortcodes.md`

## Plugins

Optional features live under `plugins/<name>/` and are enabled in `config.php`:

```php
$enabledPlugins = ['multilang'];
```

Each plugin is `plugins/<name>/plugin.php`. A plugin may take over page routing; otherwise flatMark stays single-language under `content/pages/`.

Themes call `flatmark_hook('head')` and `flatmark_hook('footer')`. Plugins may register callbacks with `flatmark_add_hook(...)` when they load.

Detailed setup for each plugin lives in `plugins/<name>/README.md`.

See the demo page [Plugins](https://flatmark.elektrischerwalfisch.de/plugins) for the list of shipped plugins.

## Themes

Themes live under `themes/<name>/`. The active theme is selected only by `$themeName` in `config.php`. There is no automatic fallback to another theme.

- `themes/default/` — shipped system theme (updated with the product)
- `themes/<other>/` — site-owned custom themes (not overwritten by product updates)

Each theme should contain at least:

- `index.php` — default HTML template
- `functions.php` — optional shortcodes and helpers
- `css/style.css`
- `js/presets.js`

**Switch theme**

1. Copy `themes/default/` to e.g. `themes/custom/` (or create a new theme folder)
2. Edit the copy as needed
3. Set in `config.php`: `$themeName = 'custom';`
4. Invalid or missing theme names return a clear HTTP 500 error

CSS, JS, and templates are always loaded from the selected theme folder (`/themes/<name>/...`).

**Templates within a theme**  
Further HTML templates can be added in the active theme folder and addressed via metadata. Example: For a page including the metadata `layout: blog` the template `themes/<name>/blog.php` would be used, instead of the default template `themes/<name>/index.php`.

Shipped default theme paths:

- Styling: `themes/default/css/style.css`
- JavaScript: `themes/default/js/presets.js`
- Shortcodes: `themes/default/functions.php`
- Default HTML template: `themes/default/index.php`


## flatMark as CMS
If you are not a programmer and used to work with FTP-client and texteditor you might prefer a Content-Management-System (CMS) to edit your website. So how would you define a simple CMS? Basically it would allow you to do the following directly in your Browser:
- Login via username & password
- Edit website content in a text-editor
- Upload/Manage files
- Change settings

Even if flatMark itself does not provide that, you might already have resources at hand which you can use to do exactly that!
- **Webhoster**  
Your webhoster might already provide a seperate WebFTP-Login with a build-in Text-editor.   
Example: [all-inkl webftp](https://webftp.all-inkl.com/)
- **Nextcloud**  
If your are using Nextcloud and the default-plugin "External Storage", you can use that to access your flatMark-Installation via FTP. For Markdown-files Nextcloud even provides a WYSIWYG-Editor by default.  
Website: [Nextcloud](https://nextcloud.com/)
- **Tinyfilemanager**  
If you do not have none of the options above, you still can install this simple file-manager which is actually just one single php-file. Just upload it to the root-folder of your flatMark-Installation and access it via your browser! (Do not forget to change the password in that file before you upload it)  
Website: [tinyfilemanager](https://tinyfilemanager.github.io/)  

These are only 3 options how you can access your flatMark-Installation, but there might be more.

## License
FlatMark is released under the MIT License.

## Credits
This project uses;
- [Parsedown](https://parsedown.org) — MIT License
- [Open Sans font](https://www.fontsquirrel.com/fonts/open-sans) — Apache License 2.0
