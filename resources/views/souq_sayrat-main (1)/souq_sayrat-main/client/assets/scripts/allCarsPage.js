// Load all cars page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('all-cars-hero', '#all-cars-hero-container');
  await loadComponent('cars-search-section', '#cars-search-section-container');
  await loadComponent('cta-banner', '#cta-banner-container');
  await loadComponent('footer', '#footer-container');
});
