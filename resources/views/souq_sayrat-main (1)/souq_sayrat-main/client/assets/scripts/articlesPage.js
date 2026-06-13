// Load articles page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('articles-featured', '#articles-featured-container');
  await loadComponent('articles-list', '#articles-list-container');
  await loadComponent('footer', '#footer-container');
});
