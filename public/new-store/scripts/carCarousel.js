// Reusable Carousel Class
class CarCarousel {
  constructor(containerSelector) {
    this.container = document.querySelector(containerSelector);
    if (!this.container) return;

    this.viewport = this.container.querySelector('.car-carousel-viewport');
    this.track = this.container.querySelector('.car-carousel-track');
    this.prevButton = this.container.querySelector('.car-carousel-prev');
    this.nextButton = this.container.querySelector('.car-carousel-next');
    this.progress = this.container.querySelector('.car-carousel-progress');
    
    this.currentSlide = 0;
    
    this.init();
  }

  init() {
    if (this.prevButton) {
      this.prevButton.addEventListener('click', () => this.prev());
    }

    if (this.nextButton) {
      this.nextButton.addEventListener('click', () => this.next());
    }

    window.addEventListener('resize', () => this.update());
    this.update();
  }

  getCards() {
    return this.track ? Array.from(this.track.children) : [];
  }

  getCardStep() {
    const cards = this.getCards();
    if (!cards.length) return 0;

    const cardWidth = cards[0].offsetWidth;
    const gap = parseFloat(getComputedStyle(this.track).gap) || 0;

    return cardWidth + gap;
  }

  getMaxSlide() {
    if (!this.viewport || !this.track) return 0;

    const cards = this.getCards();
    if (!cards.length) return 0;

    const cardStep = this.getCardStep();
    const totalWidth =
      cards.length * cards[0].offsetWidth +
      (cards.length - 1) * (parseFloat(getComputedStyle(this.track).gap) || 0);

    const visibleWidth = this.viewport.offsetWidth;
    const maxMove = Math.max(0, totalWidth - visibleWidth);

    return Math.ceil(maxMove / cardStep);
  }

  update() {
    if (!this.track) return;

    const maxSlide = this.getMaxSlide();
    this.currentSlide = Math.max(0, Math.min(this.currentSlide, maxSlide));

    const move = this.currentSlide * this.getCardStep();
    this.track.style.transform = `translateX(${move}px)`;

    if (this.progress) {
      const progressPercent = maxSlide > 0 ? this.currentSlide / maxSlide : 0;
      const progressWidth = 42;
      const trackWidth = 112;
      const maxProgressMove = trackWidth - progressWidth;

      const progressMove = progressPercent * maxProgressMove;
      this.progress.style.right = `${progressMove}px`;
      this.progress.style.left = 'auto';
    }
  }

  prev() {
    this.currentSlide = Math.max(0, this.currentSlide - 1);
    this.update();
  }

  next() {
    this.currentSlide = Math.min(this.getMaxSlide(), this.currentSlide + 1);
    this.update();
  }

  addCards(cardsHTML) {
    if (this.track) {
      this.track.innerHTML = cardsHTML;
      this.currentSlide = 0;
      this.update();
    }
  }
}

// Export for use in other components
window.CarCarousel = CarCarousel;
