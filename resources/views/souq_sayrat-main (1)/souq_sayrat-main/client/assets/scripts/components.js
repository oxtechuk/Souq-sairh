// Component Loader
async function loadComponent(componentName, targetSelector) {
    try {
        // Determine the correct path based on current location
        const isInPages = window.location.pathname.includes('/pages/');
        const basePath = isInPages ? '../assets/components/' : 'assets/components/';
        
        const response = await fetch(`${basePath}${componentName}/${componentName}.html`);
        const html = await response.text();
        const target = document.querySelector(targetSelector);
        if (target) {
            // Fix image paths based on location
            let processedHtml = html;
            if (isInPages) {
                // Replace asset paths for pages folder
                processedHtml = processedHtml.replace(/src="assets\//g, 'src="../assets/');
                processedHtml = processedHtml.replace(/href="assets\//g, 'href="../assets/');
            }
            
            target.innerHTML = processedHtml;
            
            // Execute scripts in the loaded component
            const scripts = target.querySelectorAll('script');
            scripts.forEach(script => {
                const newScript = document.createElement('script');
                if (script.src) {
                    newScript.src = script.src;
                } else {
                    newScript.textContent = script.textContent;
                }
                script.parentNode.replaceChild(newScript, script);
            });
        }
    } catch (error) {
        console.error(`Error loading component ${componentName}:`, error);
    }
}

// Load all components on page load
document.addEventListener('DOMContentLoaded', async () => {
    // Load components
    await loadComponent('top-bar', '#top-bar-container');
    await loadComponent('header', '#header-container');
    await loadComponent('hero', '#hero-container');
    await loadComponent('search-filter', '#search-filter-container');
    await loadComponent('featured-cars', '#featured-cars-container');
    await loadComponent('car-list', '#car-list-container');
    await loadComponent('offers-grid', '#offers-grid-container');
    await loadComponent('brands-carousel', '#brands-carousel-container');
    await loadComponent('testimonials', '#testimonials-container');
    await loadComponent('footer', '#footer-container');
});
