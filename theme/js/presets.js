document.addEventListener("DOMContentLoaded", function() {
    
// HEADER: generate toggle-button, nav-wrapper and set variables
    const modHeader = document.querySelector("header");

    modHeader.insertAdjacentHTML('beforeend', '<button id="toggle-nav"><span></span><span></span><span></span></button>');
    const toggleNav = document.querySelector("#toggle-nav");

    modHeader.insertAdjacentHTML('beforeend', '<nav id="main-nav"></nav>');
    const mainNav = document.querySelector("#main-nav");

// HEADER: move main-menu into nav-wrapper
    mainNav.appendChild(document.querySelector('header > ul'));

// HEADER: toggle button
    toggleNav.addEventListener("click", function() {
        if (modHeader.classList.contains("menu-active")) {
            modHeader.classList.remove("menu-active");
        } else {
            modHeader.classList.add("menu-active");
        }
    });

// HEADER (only multilanguage setup): highlight active language in header extras menu
    const langSwitch = document.querySelectorAll('header .extras ul li a');
    const currentLang = document.documentElement.lang;  // Get the lang attribute from the <html> element
    langSwitch.forEach(link => {
        // Check if the href matches the current lang
        if (link.getAttribute('href').includes(currentLang)) {
            link.classList.add('active');  // Add the active class to the correct link
        }
    });
    
// HEADER: highlight active page in main menu
    const currentUrl = window.location.href;
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('header > ul a');
    menuLinks.forEach((link) => {
        // Check if the href matches the current URL or pathname
        if (link.href === currentUrl || link.pathname === currentPath) {
            link.classList.add('active');
        }
        // Additional check for the Home link
        if (currentPath === '/' && link.getAttribute('href') === '/') {
            link.classList.add('active');
        }
    });

// OPTIONAL: open all links inside main in a new window
    // document.querySelectorAll('main a').forEach((link) => {
    //     link.setAttribute('target', '_blank');
    // });

});