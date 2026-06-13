// Load contact page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('all-cars-hero', '#all-cars-hero-container');
  await loadComponent('contact-form', '#contact-form-container');
  await loadComponent('financing-cta', '#financing-cta-container');
  await loadComponent('footer', '#footer-container');
});
