// Load calculator page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('calculator', '#calculator-container');
  await loadComponent('compare-cta', '#compare-cta-container');
  await loadComponent('footer', '#footer-container');
});
