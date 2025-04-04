# FlatMark

*A lightweight, flat-file Markdown-based site generator with optional multilingual support.*

## Features  
✅ Simple, no database required – just Markdown files  
✅ Supports **single** and **multi-language** setups  
✅ Auto-parses **Markdown** to HTML using [Parsedown](https://parsedown.org/)  
✅ Easy-to-edit content, just upload `.md` files  
✅ **Custom meta fields** for title, description, and robots meta tag  
✅ Customizable with your own **themes and styles**  
✅ Lightweight and fast  

## How It Works  
FlatMark dynamically converts Markdown files in the `/pages` folder into HTML pages.  
- In **single-language mode**, pages are stored in `/pages/`.  
- In **multi-language mode**, each language has its own folder, e.g., `/pages-en/`, `/pages-de/`.  

### URL Structure  

| Mode            | Example URL  | Maps to File          |
|----------------|-------------|-----------------------|
| Single Language | `/about`    | `/pages/about.md`    |
| Multi-Language | `/en/about` | `/pages-en/about.md` |

## Installation  
1. **Upload** the files to your web server.  
2. **Edit content** inside `/pages` (or `/pages-XX` for multilingual sites).  
3. Done! Your site is ready.  

### Requirements  
- PHP 7.4+  
- Apache/Nginx with mod_rewrite enabled  

## Configuration  
Set up **single** or **multi-language mode** in `index.php`:  

```php
$multilingual = true; // Set to false for single-language mode
require $multilingual ? 'config-multilang.php' : 'config-basic.php';


## Meta Information (Title, Description & Robots)

Each Markdown page can include optional meta information using HTML comments at the top of the file.
These values will be automatically extracted and used in the <head> section of the generated HTML page.

Example Markdown file (about.md) with meta information:

    <!-- title: About Us -->
    <!-- description: Learn more about our mission and team. -->
    <!-- robots: index, follow -->

    # Welcome to Our Company

    We are committed to providing the best services...

- title → Sets the <title> of the page. Defaults to the filename if not provided.
- description → Used for the <meta name="description"> tag. Defaults to an empty string if not set.
- robots → Controls search engine indexing (index, follow / noindex, nofollow). Defaults to index, follow.

### Folder Structure

    /flatmark/
    │── /pages-en/         # English pages
    │── /pages-de/         # German pages
    │── /theme/            # Styles and templates
    │── /config-multilang.php  # Multi-language setup
    │── /config-basic.php      # Single-language setup
    │── index.php          # Main file
    │── .htaccess          # URL rewriting
    │── README.md          # Documentation

### Customization
- Themes: Modify /theme/css/style.css for styling.
- Header/Footer: Edit 01-header.md and 02-footer.md in each language folder.

## License
FlatMark is released under the MIT License.