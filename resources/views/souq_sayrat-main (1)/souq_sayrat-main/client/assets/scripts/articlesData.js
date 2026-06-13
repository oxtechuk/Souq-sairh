// Articles Data
const articlesData = [
  {
    id: 1,
    image: 'assets/images/offer-card-1.jpg',
    title: 'كيف تختار السيارة الكهربائية المناسبة؟',
    excerpt: 'نصائح مهمة لاختيار السيارة الكهربائية التي تناسب احتياجاتك وميزانيتك',
  },
  {
    id: 2,
    image: 'assets/images/offer-card-2.jpg',
    title: 'كيف تختار السيارة الكهربائية المناسبة؟',
    excerpt: 'نصائح مهمة لاختيار السيارة الكهربائية التي تناسب احتياجاتك وميزانيتك',
  },
  {
    id: 3,
    image: 'assets/images/offer-card-3.jpg',
    title: 'كيف تختار السيارة الكهربائية المناسبة؟',
    excerpt: 'نصائح مهمة لاختيار السيارة الكهربائية التي تناسب احتياجاتك وميزانيتك',
  },
  {
    id: 4,
    image: 'assets/images/offer-card-1.jpg',
    title: 'كيف تختار السيارة الكهربائية المناسبة؟',
    excerpt: 'نصائح مهمة لاختيار السيارة الكهربائية التي تناسب احتياجاتك وميزانيتك',
  },
  {
    id: 5,
    image: 'assets/images/offer-card-2.jpg',
    title: 'كيف تختار السيارة الكهربائية المناسبة؟',
    excerpt: 'نصائح مهمة لاختيار السيارة الكهربائية التي تناسب احتياجاتك وميزانيتك',
  },
  {
    id: 6,
    image: 'assets/images/offer-card-3.jpg',
    title: 'كيف تختار السيارة الكهربائية المناسبة؟',
    excerpt: 'نصائح مهمة لاختيار السيارة الكهربائية التي تناسب احتياجاتك وميزانيتك',
  },
];

// Generate a single article card HTML
function generateArticleCard(article) {
  const isInPages = window.location.pathname.includes('/pages/');
  const imgPath = isInPages ? article.image.replace('assets/', '../assets/') : article.image;
  return `
    <div class="article-card">
      <div class="article-card-image">
        <img src="${imgPath}" alt="${article.title}" />
      </div>
      <div class="article-card-content">
        <h3 class="article-card-title">${article.title}</h3>
        <p class="article-card-excerpt">${article.excerpt}</p>
        <button class="article-card-btn" onclick="navigateToArticle(${article.id})">اقرأ المقالة</button>
      </div>
    </div>
  `;
}

window.articlesData = articlesData;
window.generateArticleCard = generateArticleCard;

function navigateToArticle(id) {
  const isInPages = window.location.pathname.includes('/pages/');
  const path = isInPages ? `article-details.html?id=${id}` : `pages/article-details.html?id=${id}`;
  window.location.href = path;
}

window.navigateToArticle = navigateToArticle;
