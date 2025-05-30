# flatMark

*A lightweight, flat-file Markdown-based website generator*

## Features  
- Lightweight and fast
- Easy-to-edit content, just Markdown files  
- Just requires basic php, no database or build-steps needed
- Auto-parses **Markdown** to HTML using [Parsedown](https://parsedown.org/)  
- Supports **single-language** and **multi-language** setups  
- **Metadata** for title, description, and robots meta tag for each site (YAML font matter)
- Provides simple **Shortcodes** to arrange and style your content (e.g. columns, different backgrounds)
- Basic, responsive **Theme** which you can customize and enhance  
- Option to use individual **Templates** for single pages

## Demo
The [Demo Website](https://flatmark.elektrischerwalfisch.de) is an exact copy of the [GitHub Repository](https://github.com/elektrischerwalfisch/flatmark) and doubles as the documentation site. 

## How It Works  
FlatMark dynamically converts Markdown files in the folder `/pages` (or `/pages-XX` for multilingual sites) into HTML pages. Find the full markdown-syntax here: [www.markdownguide.org/basic-syntax](https://www.markdownguide.org/basic-syntax/)  

## URL Structure  

| Setup           | Example URL | Maps to File          |
|-----------------|-------------|-----------------------|
| Single Language | `/about`    | `/pages/about.md`     |
| Multi-Language  | `/en/about` | `/pages-en/about.md`  |

## Folder Structure

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
1. **Download** the [latest release](https://github.com/elektrischerwalfisch/flatmark/releases/latest) of flatMark which contains a simple example-page
2. **Upload** the files to your web server.  
3. **Configuration** choose between single or multilanguage-setup in `index.php` (default: multi-language)
4. **Edit content** inside `/pages` (or `/pages-XX` for multilingual sites).  
5. Done! Your site is ready.  

## Requirements  
- PHP 7.4+  
- Apache/Nginx with mod_rewrite enabled 

## Configuration  
The default-configuration is for the languages English(en) and German(de).  
If this is already what you want, you do not have to change anything and you can delete the file `config-basic.php` as it is only needed for the single-language setup.

**Multi language setup (default)**  
The automatic language-redirection is described with the default-setup with folders for Engish and German pages:
When the website is opened in a browser with German language-settings, the request is automatically redirected to the pages in the folder `/pages-de`. All requests with brower-settings in other languages than german, are redirected to the pages in the folder `/pages-en` (default-language).

- You can edit the languages of your website in the file `config-multilang.php` by editing the language-codes in this line: `$supportedLanguages = ['en', 'de'];` The first language-code acts as the default-language ('en' in this case).
- **Add language** (example):  
If you want to add French, change $supportedLanguages to `$supportedLanguages = ['en', 'de', 'fr'];` and add the folder `/pages-fr` for the French pages.
- **Remove language** (example):  
If you want to remove German, change $supportedLanguages to `$supportedLanguages = ['en', 'fr'];` and remove the folder `/pages-de` for the German pages.


**Single language setup**  
- If you only want a website with a single language, open `index.php` and change the line `require 'config-multilang.php';` to `require 'config-basic.php';` 
- Delete the file `config-multilang.php` as it is only needed for the multi-language setup.
- Rename the folder `/pages-en` to `/pages` and delete the folder `/pages-de`
- Open the file `config-basic.php` and define the language of your website by editing the language-code in this line: `$lang = 'de';` ('de' stands for German this case)

Find all available language-codes here: [HTML Language Code Reference](https://www.w3schools.com/tags/ref_language_codes.asp) 

## Defaults
Each pages-folder must contain at least these files for the website to function:  

- 01-header.md  
Edit this file to change logo, title, subtitle of the website and the main menu. The main-menu must be a list of links to function correctly. You also have the option to add further elements like contact-details or a language-menu by wrapping them in the shortcode `{extras}` `{/extra}`. 

- 02-footer.md  
Edit this file to change the text in the footer and the footer-menu. The footer-menu must also be a list of links to function correctly. The shortcode `{year}` will display the current year. 

- home.md  
This file is the default startpoint of your website.   

- 404.md  
Edit this file to change the error-message which is shown if a page is not found. 


## Metadata
Each Markdown page can include optional metadata at the top of the file (Format: YAML font matter).
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
- **layout** → Sets individual template for the page, find further infos below under "Customization".

## Shortcodes
flatMark supports simple shortcodes for structured content. You can see all shortcodes in action on the [Examples-Page](https://flatmark.elektrischerwalfisch.de/en/examples) of theDemo Website. Here are just two examples:

    {columns 50-50}
    Left column
    {columns-seperator}
    Right column
    {/columns}

    {background color-01}
    This content has a colored background.
    {/background}
  
These shortcodes are part of the theme and are all located in the file `/theme/functions.php`.
You can edit this file to change existing shortcodes or add even more.
An example-page with all shortcodes is provided with the installation: `/pages-en/examples.md`


## Customization
All customizable parts of flatMark are located in the `theme/` folder: 
- Styling with CSS main-stylesheet: `theme/css/style.css`
- Interactive with JavaScript: `theme/js/presets.js`
- Additional php-fuctions (like Shortcodes): `theme/functions.php`
- Default HTML template: `theme/index.php`  

Additional assets like fonts, favicons and images can also be placed in the theme-folder to keep everything neatly organized in one place.  

**Templates**  
Further HTML-templates can be added in the theme folder and addressed via metadata. Example: For a page including the metadata `layout: blog.php` the template `theme/blog.php` would be used, instead of the default-template `theme/index.php`.  


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