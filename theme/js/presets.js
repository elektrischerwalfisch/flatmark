document.addEventListener("DOMContentLoaded", function() {
    
// generate toggle-button and nav-wrapper for main-menu
    document.querySelector('header').insertAdjacentHTML('beforeend', '<button id="toggle-nav"><span></span><span></span><span></span></button>');
    document.querySelector('header').insertAdjacentHTML('beforeend', '<nav id="main-nav"></nav>');

// menu & toggle-button variables
    const modHeader = document.querySelector("header");
    const toggleNav = document.querySelector("#toggle-nav");
    const mainNav = document.querySelector("#main-nav");

// move main-menu into nav-wrapper
    mainNav.appendChild(document.querySelector('header > ul:not(#lang)'));

// detect screensize
    function updateARIA() {
        if (window.matchMedia("(max-width: 781px)").matches) {
            // small screens, close menu
            toggleNav.removeAttribute("aria-hidden");
            toggleNav.setAttribute("aria-controls", "main-nav");
            toggleNav.setAttribute("aria-expanded", "false");
            mainNav.setAttribute("aria-hidden", "true");
            mainNav.setAttribute("inert", ""); // Disable interaction
        } else {
            // large screens, open menu
            toggleNav.setAttribute("aria-hidden", "true");
            toggleNav.removeAttribute("aria-controls");
            toggleNav.removeAttribute("aria-expanded");
            mainNav.removeAttribute("aria-hidden");
            mainNav.removeAttribute("inert"); // Enable interaction
        }
    }
    // Run once on page load
    updateARIA();
    // Run again on window resize (for responsiveness)
    window.addEventListener("resize", updateARIA);

// toggle menu
    toggleNav.addEventListener("click", function() {
        if (modHeader.classList.contains("menu-active")) {
            // close menu
            modHeader.classList.remove("menu-active");
            toggleNav.setAttribute("aria-expanded", "false");
            mainNav.setAttribute("aria-hidden", "true");
            mainNav.setAttribute("inert", ""); // Disable interaction
        } else {
            // open menu
            modHeader.classList.add("menu-active");
            toggleNav.setAttribute("aria-expanded", "true");
            mainNav.removeAttribute("aria-hidden", "true");
            mainNav.removeAttribute("inert"); // Enable interaction
        }
    });

// highlight active language in language-switch menu
    const langSwitch = document.querySelectorAll('#lang a');
    const currentLang = document.documentElement.lang;  // Get the lang attribute from the <html> element

    langSwitch.forEach(link => {
        // Check if the href matches the current lang
        if (link.getAttribute('href').includes(currentLang)) {
            link.classList.add('active');  // Add the active class to the correct link
        }
    });
    
// highlight active page in menu
    const currentUrl = window.location.href;
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('header :not(#lang) ul a');
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

// open all links inside main in a new window
    document.querySelectorAll('#main a').forEach((link) => {
        link.setAttribute('target', '_blank');
    });

});