# Examples

## Shortcodes
These shortcodes are available by default in the flatMark-theme:

{code}
{columns}
    Content of first column
{columns-seperator}
    Content of second column
{/columns}

{background}
    Content with backgrond-color
{/background}

{img-rounded}
    Picture with rounded border
{/img-rounded}

{extras}
    Extra content in 01-header.md (e. g. language-switch, phone, e-mail etc.)
{/extras}

{year}
    Output of the current year (Can be used for copyright in 02-footer.md)

{code}
    Used only in this documentation to display unrendered shortcodes in code-block

{readme}
    Outputs the readme.md from the root-folder. 
    Used on the default-page /pages-en/home.md


{/code}

### Additional values
The shortcodes for columns and background accept additional values which are rendered as classes so that the output can be styled directly by css. In the default flatMark-theme these additional values are already styled:
{code}
{columns 50-50}
{columns 33-66}
{columns 66-33}
{columns 33-33-33}

{background color-01}
{background color-02}
{/code}  
<br>  

### Shortcode-Examples: Columns

{columns 50-50}
**columns 50-50** Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
{columns-seperator}
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
{/columns}  
 <br>

{columns 33-66}
**columns 33-66** Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
{columns-seperator}
![Pic](/files/example-pic-03.jpg)
{/columns}
 <br>

{columns 66-33}
**columns 66-33** Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
{columns-seperator}
![Pic](/files/example-pic-02.jpg)
{/columns}
 <br>

{columns 33-33-33}
**columns 33-33-33** Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr. 
{columns-seperator}
![Pic](/files/example-pic-01.jpg)
{columns-seperator}
![Pic](/files/example-pic-01.jpg)
{/columns}
 <br>

### Shortcode-Examples: Backgrounds
{background color-01}
**background color-01** Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren.
{/background}
 <br>

{background color-02}
**background color-02** Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren.
{/background}

### Shortcode-Examples: Miscellaneous  
{columns 33-66}
{img-rounded}
![Pic](/files/example-pic-01.jpg)
{/img-rounded}
{columns-seperator}
**img-rounded** inside **columns 33-66** 
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.  
{/columns}
<br>





