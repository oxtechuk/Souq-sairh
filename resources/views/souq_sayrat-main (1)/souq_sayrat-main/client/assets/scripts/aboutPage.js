// Load about page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('all-cars-hero', '#all-cars-hero-container');
  await loadComponent('about-hero', '#about-hero-container');
  await loadComponent('about-story', '#about-story-container');
  await loadComponent('why-choose-us', '#why-choose-us-container');
  await loadComponent('about-stats', '#about-stats-container');
  await loadComponent('financing-partners', '#financing-partners-container');
  await loadComponent('contact-location', '#contact-location-container');
  await loadComponent('testimonials', '#testimonials-container');
  console.log('Loading offers-grid...');
  await loadComponent('offers-grid', '#offers-grid-container');
  console.log('Offers-grid loaded');
  await loadComponent('footer', '#footer-container');
});
