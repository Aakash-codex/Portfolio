// Ensure page scrolls to top on reload
window.addEventListener('load', function() {
    window.scrollTo(0, 0);
});

// Also scroll to top immediately when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.scrollTo(0, 0);
    // Remove any hash from URL without scrolling
    if (window.location.hash) {
        history.replaceState(null, null, ' ');
    }
});

// Prevent scroll restoration on page reload
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}
