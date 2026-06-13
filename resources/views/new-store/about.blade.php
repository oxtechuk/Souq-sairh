@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - من نحن')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/all-cars-hero/all-cars-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/about-story/about-story.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/why-choose-us/why-choose-us.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/about-stats/about-stats.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/financing-partners/financing-partners.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/testimonials/testimonials.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/offers-grid/offers-grid.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/contact-location/contact-location.css') }}" />
@endpush

@section('content')

{{-- 1. Hero Section --}}
@php $breadcrumbBg = $settings['breadcrumb_bg'] ?? null; @endphp
<section class="all-cars-hero" dir="rtl" style="{{ $breadcrumbBg ? 'background-image: url(' . asset('storage/' . $breadcrumbBg) . ');' : '' }}">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="text-center" style="margin-top: 80px;">
      <h1 class="font-extrabold text-white" style="font-family: 'Tajawal'; font-size: 36px; line-height: 48px; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
        عن <span style="color: #FEC303;">سوق سيارة</span>
      </h1>
      <p class="text-lg max-w-3xl mx-auto text-gray-200 mt-4" style="text-shadow: 0 1px 4px rgba(0,0,0,0.3);">
        الوجهة الأولى والموثوقة لاقتناء أحدث السيارات وأفضل عروض التمويل في المملكة
      </p>
    </div>
  </div>
</section>

{{-- 2. About Story --}}
<section class="about-story-section py-16" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="about-story-content text-center max-w-4xl mx-auto">
      <h2 class="about-story-title text-3xl font-extrabold mb-6">
        قصتنا.. <span class="text-gold">شغف يقوده الطموح</span>
      </h2>
      <p class="about-story-description text-gray-600 text-lg leading-relaxed">
        في "سوق سيارة"، لم نأت لنبيع السيارات فحسب، بل جئنا لنعيد تعريف تجربة تملكها في المملكة، عبر دمج الحلول التمويلية الذكية بالخيارات التي تليق بطموحاتكم. نحن شركاء دربكم نحو مستقبل أفضل وتجربة قيادة ممتعة وآمنة.
      </p>
    </div>
  </div>
</section>

{{-- 3. Why Choose Us --}}
<section class="why-choose-us-section py-16 bg-gray-50" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="why-choose-us-container grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      
      <div class="why-choose-us-content order-2 lg:order-1">
        <h2 class="why-choose-us-main-title text-3xl font-extrabold text-primary mb-8">لماذا نحن؟</h2>
        
        <div class="why-choose-us-features space-y-8">
          <div class="feature-item flex gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center text-xl">
              <i class="fas fa-layer-group"></i>
            </div>
            <div>
              <h3 class="feature-title text-xl font-bold text-primary mb-2">خيارات لا محدودة</h3>
              <p class="feature-description text-gray-600 leading-relaxed">
                نجمع لك كبرى العلامات التجارية تحت سقف واحد، لتضمن لك حرية الاختيار وتنوع الخيارات التي تناسب كل الأذواق.
              </p>
            </div>
          </div>

          <div class="feature-item flex gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center text-xl">
              <i class="fas fa-hand-holding-dollar"></i>
            </div>
            <div>
              <h3 class="feature-title text-xl font-bold text-primary mb-2">تمويل بلا تعقيد</h3>
              <p class="feature-description text-gray-600 leading-relaxed">
                صممنا حلولنا التمويلية لتكون مرنة، سريعة، ومتوافقة مع احتياجاتك المالية دون أي شروط تعجيزية.
              </p>
            </div>
          </div>

          <div class="feature-item flex gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center text-xl">
              <i class="fas fa-shield-alt"></i>
            </div>
            <div>
              <h3 class="feature-title text-xl font-bold text-primary mb-2">شفافية مطلقة</h3>
              <p class="feature-description text-gray-600 leading-relaxed">
                من الفحص وحتى الاستلام، الوضوح هو محركنا الأساسي في كل خطوة لضمان راحتك وثقتك الكاملة.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="why-choose-us-image order-1 lg:order-2">
        <div class="image-card rounded-2xl overflow-hidden shadow-xl">
          @php $whyChooseImage = $settings['about_why_choose_image'] ?? null; @endphp
          @if($whyChooseImage)
            <img src="{{ asset('storage/' . $whyChooseImage) }}" alt="Why Choose Us" class="main-image w-full h-auto object-cover" loading="lazy" />
          @else
            <img src="{{ asset('new-store/images/about-why-choose.jpg') }}" alt="Why Choose Us" class="main-image w-full h-auto object-cover" loading="lazy" />
          @endif
        </div>
      </div>

    </div>
  </div>
</section>

{{-- 4. About Stats --}}
<section class="about-stats-section py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="about-stats-header">
      <h2 class="about-stats-title">
        أكثر من معرض.. <span class="text-gold">إحنا شركاء الدرب</span>
      </h2>
      <p class="about-stats-subtitle">
        نرافقك في كل خطوة، من اختيار موديل حتى استلام المفتاح
      </p>
    </div>

    <div class="about-stats-grid">
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-award"></i></div>
        <div class="stat-number">10+</div>
        <div class="stat-label">أعوام من الثقة</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-heart"></i></div>
        <div class="stat-number">98%</div>
        <div class="stat-label">تجارب ناجحة</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-car"></i></div>
        <div class="stat-number">{{ $stats['cars'] ?? '200' }}+</div>
        <div class="stat-label">موديل بالمعارض</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-number">50K+</div>
        <div class="stat-label">عائلة سوق سيارة</div>
      </div>
    </div>
  </div>
</section>

{{-- 5. Financing Partners --}}
@if($partners && $partners->isNotEmpty())
<section class="financing-partners-section py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="financing-partners-header">
      <h2 class="financing-partners-title">
        شركاء التيسير.. <span class="text-gold">حلول تمويلية معتمدة</span>
      </h2>
      <p class="financing-partners-subtitle">
        نتعاون مع كبرى الجهات التمويلية والبنوك لنضمن لك أفضل نسبة تمويل وأسرع إجراءات.
      </p>
    </div>

    <div class="financing-partners-container partners-desktop">
      <div class="partners-grid">
        @foreach($partners as $partner)
        <div class="partner-logo"><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" /></div>
        @endforeach
      </div>
    </div>

    <div class="partners-mobile-carousel">
      <div class="partners-mobile-viewport">
        <div class="partners-mobile-track" id="partners-mobile-track"></div>
      </div>
      <div class="flex items-center justify-center gap-8 mt-6">
        <button class="partners-prev w-[44px] h-[44px] bg-primary text-white rounded-full flex items-center justify-center">
          <i class="fas fa-arrow-right"></i>
        </button>
        <div class="partners-dots flex gap-2" id="partners-dots"></div>
        <button class="partners-next w-[44px] h-[44px] bg-primary text-white rounded-full flex items-center justify-center">
          <i class="fas fa-arrow-left"></i>
        </button>
      </div>
    </div>
  </div>
</section>
@endif

{{-- 6. Offers Grid - صور من معرضنا --}}
<section class="bg-white py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-start justify-between mb-8">
      <div class="flex-1">
        <h2 class="text-[32px] font-extrabold mb-3 text-center">
          <span class="text-primary">صور من</span>
          <span class="text-gold"> معرضنا</span>
        </h2>
      </div>
    </div>
    <div class="offers-grid">
      <div class="offers-row-1">
        <div class="offer-card offer-tall">
          <img src="{{ asset('new-store/images/offers/offer-1.svg') }}" alt="عرض 1" />
          <div class="offer-overlay"></div>
          <div class="offer-content">
            <div class="offer-arrow"><i class="fas fa-arrow-left"></i></div>
          </div>
        </div>
        <div class="offer-column-center">
          <div class="offer-card offer-short">
            <img src="{{ asset('new-store/images/offers/offer-2.svg') }}" alt="عرض 2" />
            <div class="offer-overlay"></div>
            <div class="offer-content">
              <div class="offer-arrow"><i class="fas fa-arrow-left"></i></div>
            </div>
          </div>
          <div class="offer-card offer-logo-card">
            <img src="{{ asset('new-store/images/Logo.svg') }}" alt="Logo" class="logo-only" />
          </div>
        </div>
        <div class="offer-card offer-tall">
          <img src="{{ asset('new-store/images/offers/offer-3.svg') }}" alt="عرض 3" />
          <div class="offer-overlay"></div>
          <div class="offer-content">
            <div class="offer-arrow"><i class="fas fa-arrow-left"></i></div>
          </div>
        </div>
      </div>
      <div class="offers-row-2">
        <div class="offer-card offer-wide">
          <img src="{{ asset('new-store/images/offers/offer-4.svg') }}" alt="عرض 4" />
          <div class="offer-overlay"></div>
          <div class="offer-content">
            <div class="offer-arrow"><i class="fas fa-arrow-left"></i></div>
          </div>
        </div>
        <div class="offer-card offer-wide">
          <img src="{{ asset('new-store/images/offers/offer-5.svg') }}" alt="عرض 5" />
          <div class="offer-overlay"></div>
          <div class="offer-content">
            <div class="offer-arrow"><i class="fas fa-arrow-left"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- 7. Contact Location --}}
<section class="contact-location-section py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="contact-location-header">
      <h2 class="contact-location-title">
        تشرّفنا بزيارتك.. <span class="text-gold">حنا بانتظارك</span>
      </h2>
      <p class="contact-location-subtitle">
        نتعاون مع كبرى الجهات التمويلية والبنوك لنضمن لك أفضل نسبة تمويل وأسرع إجراءات.
      </p>
    </div>
    <div class="map-container">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.2!2d46.7!3d24.7!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjTCsDQyJzAwLjAiTiA0NsKwNDInMDAuMCJF!5e0!3m2!1sen!2ssa!4v1234567890"
        width="100%"
        height="400"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
    <div class="contact-info-grid">
      <div class="contact-info-card">
        <div class="contact-info-icon">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="contact-info-content">
          <h3 class="contact-info-label">موقعنا</h3>
          <p class="contact-info-value">الرياض، المملكة العربية السعودية</p>
        </div>
      </div>
      <div class="contact-info-card">
        <div class="contact-info-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <div class="contact-info-content">
          <h3 class="contact-info-label">البريد الإلكتروني</h3>
          <p class="contact-info-value">Tese@test.com</p>
        </div>
      </div>
      <div class="contact-info-card">
        <div class="contact-info-icon">
          <i class="fas fa-phone"></i>
        </div>
        <div class="contact-info-content">
          <h3 class="contact-info-label">خدمة العملاء</h3>
          <p class="contact-info-value">056 9567 947</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- 8. Testimonials --}}
@if($testimonials && $testimonials->isNotEmpty())
<section class="testimonials-section py-16 bg-gray-50" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="testimonials-header text-center mb-12">
      <h2 class="testimonials-title text-[36px] font-extrabold mb-4">
        <span class="text-primary">تجارب</span> <span class="text-gold">نفخر بها</span>
      </h2>
    </div>

    <div class="testimonials-carousel-wrapper">
      <div class="testimonials-viewport overflow-hidden">
        <div class="testimonials-track flex transition-transform duration-500 ease-in-out" id="about-testimonials-track">
          @php $chunks = $testimonials->chunk(3); @endphp
          @foreach($chunks as $chunk)
          <div class="testimonials-page min-w-full grid grid-cols-1 lg:grid-cols-3 gap-8">
            @foreach($chunk as $t)
            <div class="testimonial-card bg-white rounded-[20px] p-10 border border-[#E5E7EB] flex flex-col gap-6 transition-all hover:-translate-y-1 hover:border-primary">
              <div class="quote-icon flex justify-center mb-2">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                  <path d="M14 26H20L16 34H14V26ZM26 26H32L28 34H26V26Z" fill="#d4a017" opacity="0.3"/>
                  <path d="M12 24H18L14 34H12V24ZM26 24H32L28 34H26V24Z" fill="#1A3263" opacity="0.15"/>
                </svg>
              </div>
              <p class="testimonial-text text-center text-gray-600 text-base leading-relaxed min-h-[100px] flex items-center justify-center">{{ $t->content }}</p>
              <div class="rating-stars flex justify-center gap-2 py-4 border-y border-[#E5E7EB]">
                @for($i = 0; $i < $t->rating; $i++)
                  <i class="fas fa-star text-gold text-xl"></i>
                @endfor
                @for($i = $t->rating; $i < 5; $i++)
                  <i class="far fa-star text-gold text-xl"></i>
                @endfor
              </div>
              <div class="customer-info flex items-center gap-4">
                <div class="customer-avatar w-14 h-14 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                  @if($t->image)
                    <img src="{{ asset('storage/' . $t->image) }}" alt="{{ $t->name }}" class="w-full h-full rounded-full object-cover" loading="lazy" />
                  @else
                    <span class="text-white text-2xl font-bold">{{ mb_substr($t->name, 0, 1) }}</span>
                  @endif
                </div>
                <div class="customer-details">
                  <h4 class="customer-name text-lg font-bold text-primary">{{ $t->name }}</h4>
                  @if($t->title)
                    <p class="customer-title text-sm text-gray-500">{{ $t->title }}</p>
                  @endif
                </div>
              </div>
            </div>
            @endforeach
          </div>
          @endforeach
        </div>
      </div>

      @if($chunks->count() > 1)
      <div class="testimonials-nav-area flex items-center justify-center gap-4 mt-8">
        <button type="button" class="testimonials-nav-btn w-11 h-11 bg-primary-dark hover:bg-primary text-white rounded-full flex items-center justify-center transition-all" id="about-testimonials-prev">
          <i class="fas fa-arrow-right"></i>
        </button>
        <div class="testimonials-pagination flex gap-2" id="about-testimonials-dots">
          @foreach($chunks as $i => $chunk)
            <button class="testimonials-dot w-[10px] h-[10px] rounded-full bg-gray-300 transition-all cursor-pointer {{ $i === 0 ? 'active bg-primary w-[28px] rounded-[5px]' : '' }}"></button>
          @endforeach
        </div>
        <button type="button" class="testimonials-nav-btn w-11 h-11 bg-primary-dark hover:bg-primary text-white rounded-full flex items-center justify-center transition-all" id="about-testimonials-next">
          <i class="fas fa-arrow-left"></i>
        </button>
      </div>
      @endif
    </div>
  </div>
</section>
@endif

@endsection

@push('scripts')
<script>
  (function() {
    const track = document.getElementById('about-testimonials-track');
    const prevBtn = document.getElementById('about-testimonials-prev');
    const nextBtn = document.getElementById('about-testimonials-next');
    const dotsContainer = document.getElementById('about-testimonials-dots');

    if (!track) return;

    const pages = track.querySelectorAll('.testimonials-page');
    const total = pages.length;
    let current = 0;

    if (total <= 1) {
      const navArea = track.closest('.testimonials-carousel-wrapper')?.querySelector('.testimonials-nav-area');
      if (navArea) navArea.style.display = 'none';
      return;
    }

    const dots = dotsContainer ? dotsContainer.querySelectorAll('.testimonials-dot') : [];

    function goTo(index) {
      current = (index + total) % total;
      const viewportWidth = track.parentElement.offsetWidth;
      track.style.transform = `translateX(${current * viewportWidth}px)`;

      dots.forEach((dot, i) => {
        if (i === current) {
          dot.classList.add('active', 'bg-primary', 'w-[28px]', 'rounded-[5px]');
          dot.classList.remove('bg-gray-300', 'w-[10px]', 'h-[10px]', 'rounded-full');
        } else {
          dot.classList.remove('active', 'bg-primary', 'w-[28px]', 'rounded-[5px]');
          dot.classList.add('bg-gray-300');
        }
      });
    }

    if (prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));
    dots.forEach((dot, i) => dot.addEventListener('click', () => goTo(i)));

    window.addEventListener('resize', () => {
      const vw = track.parentElement.offsetWidth;
      track.style.transform = `translateX(${current * vw}px)`;
    });

    setInterval(() => goTo(current + 1), 6000);
  })();
</script>

<script>
(function() {
  const partnersData = @json($partners->map(fn($p) => ['src' => asset('storage/'.$p->logo), 'alt' => $p->name])->values());

  if (window.innerWidth > 768) return;

  const track = document.getElementById('partners-mobile-track');
  const dotsContainer = document.getElementById('partners-dots');
  const prevBtn = document.querySelector('.partners-prev');
  const nextBtn = document.querySelector('.partners-next');
  if (!track) return;

  const perPage = 4;
  const totalPages = Math.ceil(partnersData.length / perPage);
  let pageIndex = 0;

  function buildPages(data) {
    let html = '';
    const pages = Math.ceil(data.length / perPage);
    for (let i = 0; i < pages; i++) {
      html += '<div class="partners-mobile-page">';
      data.slice(i * perPage, i * perPage + perPage).forEach(function(p) {
        html += '<div class="partner-logo"><img src="' + p.src + '" alt="' + p.alt + '" /></div>';
      });
      html += '</div>';
    }
    return html;
  }

  track.innerHTML = buildPages(partnersData) + buildPages(partnersData);
  track.style.transform = 'translateX(0)';
  track.style.transition = 'none';

  for (let d = 0; d < totalPages; d++) {
    const dot = document.createElement('span');
    dot.className = 'partners-dot' + (d === 0 ? ' active' : '');
    dotsContainer.appendChild(dot);
  }

  function syncDots() {
    dotsContainer.querySelectorAll('.partners-dot').forEach(function(dot, i) {
      dot.classList.toggle('active', i === (pageIndex % totalPages));
    });
  }

  function goTo(index) {
    pageIndex = index;
    track.style.transition = 'transform 0.5s ease-in-out';
    track.style.transform = 'translateX(' + (pageIndex * 100) + '%)';
    syncDots();
    if (pageIndex >= totalPages) {
      setTimeout(function() {
        track.style.transition = 'none';
        track.style.transform = 'translateX(0)';
        pageIndex = 0;
      }, 500);
    }
  }

  prevBtn.onclick = function() { goTo(pageIndex <= 0 ? totalPages - 1 : pageIndex - 1); };
  nextBtn.onclick = function() { goTo(pageIndex + 1); };
  setInterval(function() { goTo(pageIndex + 1); }, 3000);
  syncDots();
})();
</script>
@endpush
