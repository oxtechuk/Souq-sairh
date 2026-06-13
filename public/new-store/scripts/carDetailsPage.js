// Load car details page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('car-details-hero', '#car-details-hero-container');
  await loadComponent('technical-specs', '#technical-specs-container');
  await loadComponent('car-gallery', '#car-gallery-container');
  await loadComponent('features-list', '#features-list-container');
  await loadComponent('footer', '#footer-container');
});
