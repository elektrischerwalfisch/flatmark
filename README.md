# flatMark

*A lightweight, flat-file Markdown-based site generator*

## Features  
- Lightweight and fast
- Easy-to-edit content, just Markdown files  
- Just requires basic php, no database needed
- Supports **single-language** and **multi-language** setups  
- Auto-parses **Markdown** to HTML using [Parsedown](https://parsedown.org/)  
- **Custom meta fields** for title, description, and robots meta tag for each site 
- Provides simple **Shortcodes** to arrange and style your content (e.g. columns, different backgrounds)
- Basic, responsive **Theme** which you can customize and enhance  

## How It Works  
FlatMark dynamically converts Markdown files in the folder `/pages` (or `/pages-XX` for multilingual sites) into HTML pages.  
Find the full markdown-syntax here: [www.markdownguide.org/basic-syntax](https://www.markdownguide.org/basic-syntax/)  

### URL Structure  

| Setup           | Example URL | Maps to File          |
|-----------------|-------------|-----------------------|
| Single Language | `/about`    | `/pages/about.md`     |
| Multi-Language  | `/en/about` | `/pages-en/about.md`  |

### Folder Structure

    /flatmark/
    │── /files/                 # Files (images, pdfs etc.)
    │── /pages-en/              # English pages
    │── /pages-de/              # German pages
    │── /theme/                 # Styles, assets and templates of the theme
    │── config-basic.php        # Single-language setup
    │── config-multilang.php    # Multi-language setup
    │── index.php               # Main file
    │── .htaccess               # URL rewriting
    │── README.md               # Documentation

## Installation  
1. **Download** the latest release of flatMark which contains a simple example-page
2. **Upload** the files to your web server.  
3. **Configuration** choose between single or multilanguage-setup in `index.php` (default: multi-language)
4. **Edit content** inside `/pages` (or `/pages-XX` for multilingual sites).  
5. Done! Your site is ready.  

### Requirements  
- PHP 7.4+  
- Apache/Nginx with mod_rewrite enabled 

### Configuration  
Set up **single** or **multi-language setup** in `index.php`:  

```php
// Default: multilingual setup
// If your site is a single-language setup, 
// change 'config-multilang.php' to 'config-basic.php' in the next line 
require 'config-multilang.php';
```
After that you can also delete the config-file which you are not using - only one of the two config-files is needed.

### Defaults: Header, Footer, Homepage, 404-page

Each pages-folder must contain at least these files for the website to function:  

- 01-header.md  
Edit this file to change logo, title, subtitle of the website and the main menu. The main-menu must be a list of links to function correctly. You also have the option to add further elements like contact-details or a language-menu by wrapping them in the shortcode `{extras}` `{/extra}`. 

- 02-footer.md  
Edit this file to change the text in the footer and the footer-menu. The footer-menu must also be a list of links to function correctly. The shortcode `{year}` will display the current year. 

- home.md  
This file is the default startpoint of your website.   

- 404.md  
Edit this file to change the error-message which is shown if a page is not found. 


## Meta information
Each Markdown page can include optional meta information using HTML comments at the top of the file.
These values will be automatically extracted and used in the <head> section of the generated HTML page.

Example Markdown file (about.md) with meta information:

    <!-- title: About Us -->
    <!-- description: Learn more about our mission and team. -->
    <!-- robots: index, follow -->

    # Welcome to Our Company

    We are committed to providing the best services...

- **title** → Sets the `<title>` of the page. Defaults to the filename if not provided.
- **description** → Used for the `<meta name="description">` tag (important for SEO). Defaults to an empty string if not set.
- **robots** → Controls search engine indexing (index, follow / noindex, nofollow). Defaults to index, follow.

## Shortcodes
flatMark supports simple shortcodes for structured content. Some examples:

    {columns 50-50}
    Left column
    {columns-seperator}
    Right column
    {/columns}

    {background color-01}
    This content has a colored background.
    {/background}

These shortcodes are part of the theme and are all located in the file `/theme/shortcodes.php`.
You can edit this file to change existing shortcodes or add even more.
An example-page with all shortcodes is provided with the installation: `/pages-en/shortcode-examples.md`


## Customization
All customizable parts of flatMark are located in the `theme/` folder. This includes the main stylesheet (`theme/css/style.css`), a JavaScript file for interactive behavior (`theme/js/presets.js`), and the `theme/shortcodes.php` file where custom shortcodes can be defined. You can freely modify these files to match your design and functionality needs. Additional assets like images, fonts, or scripts can also be placed in this folder to keep everything neatly organized in one place.


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
If your are using Nextcloud and the default-plugin "External Storage", you can use that to acces your flatMark-Installation via FTP. For Markdown-files Nextcloud even provides a WYSIWYG-Editor by default.  
Website: [Nextcloud](https://nextcloud.com/)
- **Tinyfilemanager**  
If you do not have none of the options above, you still can install this simple file-manager which is actually just one single php-file. Just upload it to the root-folder of your flatMark-Installation and acces it via your browser!  
(Do not forget to change the password in that file before you upload it)  
Website: [tinyfilemanager](https://tinyfilemanager.github.io/)  

These are only 3 options how you can access your flatMark-Installation, but there might be more.

## License
FlatMark is released under the MIT License.