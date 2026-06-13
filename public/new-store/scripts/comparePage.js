// Load compare page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('all-cars-hero', '#all-cars-hero-container');
  await loadComponent('car-comparison', '#car-comparison-container');
  await loadComponent('comparison-table', '#comparison-table-container');
  await loadComponent('compare-cta', '#compare-cta-container');
  await loadComponent('footer', '#footer-container');
});
