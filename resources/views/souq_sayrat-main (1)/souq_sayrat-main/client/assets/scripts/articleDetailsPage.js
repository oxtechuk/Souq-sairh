// Load article details page components
document.addEventListener('DOMContentLoaded', async () => {
  await loadComponent('top-bar', '#top-bar-container');
  await loadComponent('header', '#header-container');
  await loadComponent('article-content', '#article-content-container');
  await loadComponent('articles-related', '#articles-related-container');
  await loadComponent('footer', '#footer-container');
});
