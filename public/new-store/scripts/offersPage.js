// Load offers page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('offers-hero', '#offers-hero-container');
  await loadComponent('offers-cards', '#offers-cards-container');
  await loadComponent('compare-cta', '#compare-cta-container');
  await loadComponent('footer', '#footer-container');
});
