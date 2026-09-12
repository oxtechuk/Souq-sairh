@extends('new-store.layouts.app')

@php
    $brandName = trim($car->brand->name ?? '');
    $displayName = $car->display_name;
    if ($brandName !== '' && !\Illuminate\Support\Str::contains(mb_strtolower($displayName), mb_strtolower($brandName))) {
        $fullCarTitle = $brandName . ' ' . $displayName;
    } else {
        $fullCarTitle = $displayName;
    }
@endphp

@section('title', 'سعر ومواصفات ' . $fullCarTitle . ' ' . $car->year . ' كاش وتقسيط | سوق سيارة')

@section('meta_description', 'تعرف على سعر ومواصفات ' . $fullCarTitle . ' ' . $car->year . ' في السعودية. اشتريها كاش بسعر ' . number_format($car->cash_price) . ' ريال أو بالتقسيط الشهري من ' . number_format($car->min_installment) . ' ريال مع توصيل مجاني وضمان شامل.')

@section('meta')
@php
    $carImgUrl = $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png');
    $carPrice = (int) ($car->activeOffer && $car->activeOffer->special_price ? $car->activeOffer->special_price : $car->cash_price);
    $cleanDesc = !empty($car->description) ? \Illuminate\Support\Str::limit(strip_tags($car->description), 160) : ('اشتري ' . $fullCarTitle . ' ' . $car->year . ' كاش أو بالتقسيط من سوق سيارة السعودية مع توصيل مجاني وضمان شامل.');
@endphp
<meta property="og:title" content="سعر ومواصفات {{ $fullCarTitle }} {{ $car->year }} | سوق سيارة">
<meta property="og:description" content="{{ $cleanDesc }}">
<meta property="og:image" content="{{ $carImgUrl }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="product">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="سعر ومواصفات {{ $fullCarTitle }} {{ $car->year }} | سوق سيارة">
<meta name="twitter:description" content="{{ $cleanDesc }}">
<meta name="twitter:image" content="{{ $carImgUrl }}">

{{-- JSON-LD Schema for Car / Vehicle --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Car",
  "name": "{{ $fullCarTitle }} {{ $car->year }}",
  "image": "{{ $carImgUrl }}",
  "description": "{{ addslashes($cleanDesc) }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $brandName }}"
  },
  "model": "{{ $car->model }}",
  "vehicleModelDate": "{{ $car->year }}",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "SAR",
    "price": "{{ $carPrice }}",
    "availability": "https://schema.org/InStock",
    "url": "{{ url()->current() }}"
  }
}
</script>

{{-- BreadcrumbList Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "الرئيسية",
    "item": "{{ route('new.home') }}"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "السيارات",
    "item": "{{ route('new.cars.index') }}"
  },{
    "@type": "ListItem",
    "position": 3,
    "name": "{{ $fullCarTitle }}",
    "item": "{{ url()->current() }}"
  }]
}
</script>

{{-- FAQPage Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "هل يمكن شراء {{ $fullCarTitle }} {{ $car->year }} بالتقسيط بدون دفعة أولى؟",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "نعم، يوفر سوق سيارة برامج تمويلية مرنة بالتعاون مع كبرى البنوك وشركات التمويل السعودية، مع إمكانية التقسيط بدون دفعة أولى وبأقساط تبدأ من {{ number_format($car->min_installment ?? 1500) }} ريال شهرياً."
    }
  },{
    "@type": "Question",
    "name": "ما هي مدة ونوع الضمان المتوفر على سيارة {{ $fullCarTitle }}؟",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "تأتي السيارة بضمان شامل ومطابق للمواصفات السعودية مع إمكانية تمديد الضمان وخدمة المساعدة على الطريق مجاناً."
    }
  },{
    "@type": "Question",
    "name": "هل يتوفر شحن وتوصيل السيارة لكافة مدن السعودية؟",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "نعم، نقدم خدمة توصيل وشحن آمنة ومجانية للسيارة إلى باب منزلك في جميع مدن ومحافظات المملكة العربية السعودية."
    }
  }]
}
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/car-details-hero/car-details-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/technical-specs/technical-specs.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/car-gallery/car-gallery.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/features-list/features-list.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/compare-cta/compare-cta.css') }}" />
@endpush

@section('content')

{{-- 1. Car Details Hero --}}
<section class="car-details-hero" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

      {{-- Right: Info Card --}}
      <div class="info-card space-y-6">

        {{-- Rating & Title --}}
        <div>
          <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-extrabold text-primary text-right" id="car-title">
              {{ $fullCarTitle }}
            </h1>
            <div class="flex items-center gap-2">
              <i class="fas fa-star text-gold text-xl"></i>
              <span class="text-2xl font-bold text-primary">{{ $car->rating ?? '4.9' }}</span>
              <span class="text-sm text-gray-500">({{ $car->rating_count ?? '127' }} تقييم)</span>
            </div>
          </div>
        </div>

        {{-- Price --}}
        <div class="border-t border-b border-gray-200 py-6">
          <p class="text-sm text-gray-500 text-right mb-2">
            {{ $car->activeOffer && $car->activeOffer->special_price ? 'السعر بعد الخصم' : 'السعر النقدي' }}
          </p>
          @if($car->activeOffer && $car->activeOffer->special_price)
            <p class="text-4xl font-extrabold text-primary text-right mb-2">
              {{ number_format($car->activeOffer->special_price) }} ريال
            </p>
            <p class="text-xl text-gray-400 line-through text-right">{{ number_format($car->cash_price) }} ريال</p>
          @else
            <p class="text-4xl font-extrabold text-primary text-right mb-2">
              {{ number_format($car->cash_price) }} ريال
            </p>
          @endif
          @if($car->min_installment)
            <p class="text-sm text-gray-500 text-right">من أو {{ number_format($car->min_installment) }} ريال شهرياً</p>
          @endif
        </div>

        {{-- Booking Button --}}
        <div>
          <a href="{{ route('new.booking', ['car_id' => $car->id]) }}"
             class="w-full block bg-primary text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-dark transition-all text-center">
            اطلبها الآن
          </a>
        </div>

        {{-- Separator --}}
        <div class="border-t border-gray-200"></div>

        {{-- Features / Trust Badges --}}
        <div class="grid grid-cols-3 gap-4">
          <div class="feature-badge">
            <i class="fas fa-medal text-green-500 text-2xl mb-2"></i>
            <p class="text-sm font-bold text-primary">ضمان شامل</p>
          </div>
          <div class="feature-badge">
            <i class="fas fa-shield-alt text-green-500 text-2xl mb-2"></i>
            <p class="text-sm font-bold text-primary">جودة مضمونة</p>
          </div>
          <div class="feature-badge">
            <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
            <p class="text-sm font-bold text-primary">توصيل مجاني</p>
          </div>
        </div>

        {{-- Description --}}
        @if(!empty(trim($car->description ?? '')))
        <div class="car-description-card mt-6">
          <div class="desc-header">
            <div class="desc-icon">
              <i class="fas fa-file-lines"></i>
            </div>
            <h3 class="desc-title">وصف السيارة</h3>
          </div>
          <div class="desc-body">
            {!! nl2br(e($car->description)) !!}
          </div>
        </div>
        @endif

      </div>

      {{-- Left: Images --}}
      <div class="space-y-6">

        {{-- Main Image with Thumbnails --}}
        <div class="flex gap-4 flex-row">

          {{-- Thumbnails - Vertical --}}
          <div id="hero-thumbnails" class="flex flex-col gap-4">
            <div class="thumbnail-item active" onclick="changeMainImage(this, '{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}')">
              <img src="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}" alt="{{ $car->name }}" class="w-full h-auto object-contain" />
            </div>
            @foreach($car->images->take(4) as $image)
              <div class="thumbnail-item" onclick="changeMainImage(this, '{{ asset('storage/'.$image->image_path) }}')">
                <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $car->name }}" class="w-full h-auto object-contain" loading="lazy" />
              </div>
            @endforeach
          </div>

          {{-- Main Image --}}
          <div class="main-image-box flex-1">
            <img id="mainImage"
                 src="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}"
                 alt="{{ $car->name }}"
                 class="w-full h-auto object-contain" />
          </div>

        </div>

        {{-- Colors --}}
        @if($car->colors && count($car->colors) > 0)
        <div class="colors-box">
          <h3 class="text-lg font-bold text-primary text-center mb-4">الألوان المتاحة</h3>
          <div id="color-options" class="flex gap-3 justify-center flex-row">
            @foreach($car->colors as $index => $color)
              @php $colorHex = is_array($color) ? ($color['hex'] ?? '#000') : $color; @endphp
              <button class="color-btn {{ $index === 0 ? 'selected' : '' }}"
                      style="background-color: {{ $colorHex }}; {{ $colorHex === '#FFFFFF' ? 'border-color: #000;' : '' }}"
                      onclick="selectColor(this)"></button>
            @endforeach
          </div>
        </div>
        @endif

      </div>

    </div>

  </div>
</section>

{{-- 2. Technical Specifications (Relational - from pivot table) --}}
@if($car->specifications && count($car->specifications) > 0)
<section class="py-10" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-primary text-right mb-8">المواصفات التقنية</h2>
    <div id="technical-specs-grid" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
      @foreach($car->specifications as $spec)
        <div class="spec-card">
          <div class="spec-icon-wrapper">
            @if($spec->icon)
              <i class="fas fa-{{ $spec->icon }} spec-icon"></i>
            @else
              <i class="fas fa-cog spec-icon"></i>
            @endif
          </div>
          <div class="spec-content">
            <p class="spec-label">{{ $spec->name }}</p>
            <p class="spec-value">{{ $spec->pivot->value ?? $spec->type ?? '—' }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 2.5 Specs JSON (from `specs` JSON column) --}}
@php
  $specsMap = [
      'fuel_type'       => ['label' => 'نوع الوقود',       'icon' => 'fa-gas-pump'],
      'transmission'    => ['label' => 'ناقل الحركة',      'icon' => 'fa-gears'],
      'engine_size'     => ['label' => 'حجم المحرك',       'icon' => 'fa-engine'],
      'engine_capacity' => ['label' => 'سعة المحرك',       'icon' => 'fa-engine'],
      'engine_type'     => ['label' => 'نوع المحرك',       'icon' => 'fa-bolt'],
      'horsepower'      => ['label' => 'القوة (حصان)',      'icon' => 'fa-gauge-high'],
      'torque'          => ['label' => 'عزم الدوران',      'icon' => 'fa-rotate'],
      'seats'           => ['label' => 'عدد المقاعد',      'icon' => 'fa-chair'],
      'doors'           => ['label' => 'عدد الأبواب',      'icon' => 'fa-door-open'],
      'drive_type'      => ['label' => 'نظام الدفع',       'icon' => 'fa-car-side'],
      'mileage'         => ['label' => 'المسافة (كم)',     'icon' => 'fa-road'],
      'color'           => ['label' => 'اللون',            'icon' => 'fa-palette'],
      'warranty'        => ['label' => 'الضمان',           'icon' => 'fa-shield-halved'],
      'acceleration'    => ['label' => '0-100 كم/ساعة',   'icon' => 'fa-stopwatch'],
      'top_speed'       => ['label' => 'السرعة القصوى',   'icon' => 'fa-tachometer-alt'],
      'fuel_tank'       => ['label' => 'خزان الوقود (لتر)', 'icon' => 'fa-fill-drip'],
      'weight'          => ['label' => 'الوزن (كجم)',      'icon' => 'fa-weight-hanging'],
  ];
  $carSpecs = $car->specs ?? [];
  $visibleSpecs = collect($carSpecs)->filter(fn($v) => !empty($v));
@endphp

@if($visibleSpecs->isNotEmpty())
<section class="py-10 bg-gray-50" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-primary text-right mb-8">بيانات السيارة</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      @foreach($visibleSpecs as $key => $value)
        @php
          $meta = $specsMap[$key] ?? ['label' => ucfirst(str_replace('_', ' ', $key)), 'icon' => 'fa-circle-info'];
        @endphp
        <div class="spec-card">
          <div class="spec-icon-wrapper">
            <i class="fas {{ $meta['icon'] }} spec-icon"></i>
          </div>
          <div class="spec-content">
            <p class="spec-label">{{ $meta['label'] }}</p>
            <p class="spec-value">{{ $value }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 3. Car Gallery --}}
@php
    $exteriorImages = $car->images->where('type', 'exterior')->values();
    $interiorImages = $car->images->where('type', 'interior')->values();
    if ($exteriorImages->isEmpty() && $interiorImages->isEmpty() && $car->images->isNotEmpty()) {
        $exteriorImages = $car->images;
    }
@endphp

<section class="py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

      {{-- Interior --}}
      <div class="car-gallery">
        <h3 class="text-2xl font-bold text-primary text-right mb-6">صور للسيارة من الداخل</h3>
        @if($interiorImages->isNotEmpty())
        <div class="gallery-main">
          <button class="carousel-btn carousel-btn-prev" onclick="changeGalleryImage('interior', -1)">
            <i class="fas fa-chevron-left"></i>
          </button>
          <img id="interiorMainImage" src="{{ asset('storage/' . $interiorImages[0]->image_path) }}" alt="Interior View" class="gallery-main-image" />
          <div class="carousel-counter">
            {{ $interiorImages->count() }} / <span id="interiorCounter">1</span>
          </div>
          <button class="carousel-btn carousel-btn-next" onclick="changeGalleryImage('interior', 1)">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
        <div id="interior-thumbnails" class="gallery-thumbnails">
          @foreach($interiorImages as $index => $img)
            <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" onclick="selectGalleryImage('interior', {{ $index }})">
              <img src="{{ asset('storage/' . $img->image_path) }}" alt="Interior {{ $index + 1 }}" loading="lazy" />
            </div>
          @endforeach
        </div>
        @else
        <div class="gallery-main">
          <div class="flex items-center justify-center h-full text-gray-400 text-lg">لا توجد صور متاحة</div>
        </div>
        @endif
      </div>

      {{-- Exterior --}}
      <div class="car-gallery">
        <h3 class="text-2xl font-bold text-primary text-right mb-6">صور للسيارة من الخارج</h3>
        @if($exteriorImages->isNotEmpty())
        <div class="gallery-main">
          <button class="carousel-btn carousel-btn-prev" onclick="changeGalleryImage('exterior', -1)">
            <i class="fas fa-chevron-left"></i>
          </button>
          <img id="exteriorMainImage" src="{{ asset('storage/' . $exteriorImages[0]->image_path) }}" alt="Exterior View" class="gallery-main-image" />
          <div class="carousel-counter">
            {{ $exteriorImages->count() }} / <span id="exteriorCounter">1</span>
          </div>
          <button class="carousel-btn carousel-btn-next" onclick="changeGalleryImage('exterior', 1)">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
        <div id="exterior-thumbnails" class="gallery-thumbnails">
          @foreach($exteriorImages as $index => $img)
            <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" onclick="selectGalleryImage('exterior', {{ $index }})">
              <img src="{{ asset('storage/' . $img->image_path) }}" alt="Exterior {{ $index + 1 }}" loading="lazy" />
            </div>
          @endforeach
        </div>
        @else
        <div class="gallery-main">
          <div class="flex items-center justify-center h-full text-gray-400 text-lg">لا توجد صور متاحة</div>
        </div>
        @endif
      </div>

    </div>
  </div>
</section>

{{-- 4. Features List --}}
@if($car->features_list && count($car->features_list) > 0)
<section class="py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-primary text-right mb-8">المميزات والخصائص</h2>
    <div id="features-list-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($car->features_list->chunk(5) as $chunk)
        <div class="feature-list-card">
          @foreach($chunk as $feature)
            <div class="feature-list-item">
              <i class="fas fa-check-circle feature-check-icon"></i>
              <p class="feature-list-text">{{ $feature->name }}</p>
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 5. Related Cars --}}
@if($related && $related->isNotEmpty())
<section class="py-12 bg-gray-50" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
      <h2 class="text-3xl font-extrabold text-primary">سيارات ذات صلة</h2>
      <a href="{{ route('new.cars.index') }}" class="text-gold font-bold hover:underline">عرض الكل</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($related as $rCar)
        @include('new-store.partials.car-card', ['car' => $rCar])
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 6. Frequently Asked Questions (FAQ) Section --}}
<section class="py-14 bg-white" dir="rtl">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <span class="text-gold font-bold text-sm tracking-wider uppercase bg-amber-50 px-3 py-1 rounded-full border border-amber-200">إجابات سريعة</span>
      <h2 class="text-3xl font-extrabold text-primary mt-3">الأسئلة الشائعة حول {{ $fullCarTitle }}</h2>
    </div>

    <div class="space-y-4">
      <div class="border border-gray-200 rounded-2xl p-5 hover:border-primary/30 transition-all bg-gray-50/50">
        <h3 class="font-bold text-primary text-lg flex items-center gap-3">
          <i class="fas fa-circle-question text-gold text-xl"></i>
          <span>هل يمكن شراء {{ $fullCarTitle }} {{ $car->year }} بالتقسيط بدون دفعة أولى؟</span>
        </h3>
        <p class="text-gray-600 text-sm mt-3 leading-relaxed pr-8">
          نعم، نوفر في سوق سيارة خطط تمويل ميسرة بالتعاون مع كبرى البنوك والجهات التمويلية المعتمدة في المملكة العربية السعودية، مع إمكانية التقسيط بدون دفعة أولى وبأقساط تبدأ من {{ number_format($car->min_installment ?? 1500) }} ريال شهرياً.
        </p>
      </div>

      <div class="border border-gray-200 rounded-2xl p-5 hover:border-primary/30 transition-all bg-gray-50/50">
        <h3 class="font-bold text-primary text-lg flex items-center gap-3">
          <i class="fas fa-shield-halved text-green-600 text-xl"></i>
          <span>ما هي تفاصيل الضمان على السيارة؟</span>
        </h3>
        <p class="text-gray-600 text-sm mt-3 leading-relaxed pr-8">
          تحصل السيارة على ضمان شامل متوافق مع أعلى المعايير والمواصفات المعتمدة، مع خيارات تمديد الضمان وخدمة المساعدة على الطريق مجاناً لضمان راحة بالك.
        </p>
      </div>

      <div class="border border-gray-200 rounded-2xl p-5 hover:border-primary/30 transition-all bg-gray-50/50">
        <h3 class="font-bold text-primary text-lg flex items-center gap-3">
          <i class="fas fa-truck-fast text-blue-600 text-xl"></i>
          <span>هل التوصيل مجاني لجميع مناطق ومدن المملكة؟</span>
        </h3>
        <p class="text-gray-600 text-sm mt-3 leading-relaxed pr-8">
          نعم، نوفر خدمة شحن وتوصيل آمنة ومجانية حتى باب منزلك في أي مدينة ومحافظة في جميع أنحاء المملكة العربية السعودية.
        </p>
      </div>
    </div>
  </div>
</section>

{{-- 7. Call To Action (CTA) Section --}}
<section class="compare-cta-section py-14 bg-white" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="compare-cta-banner">
      <div class="compare-cta-content">
        <h2 class="compare-cta-title">جاهز لامتلاك {{ $fullCarTitle }} {{ $car->year }}؟</h2>
        <p class="compare-cta-subtitle">
          احجز سيارتك الآن كاش أو بالتقسيط بأفضل الأسعار، أو أضفها إلى المقارنة للمفاضلة بين المواصفات بكل سهولة.
        </p>

        <div class="compare-cta-buttons">
          <a href="{{ route('new.booking', ['car_id' => $car->id]) }}" class="compare-cta-btn compare-cta-btn-primary">
            اطلبها الآن
          </a>
          <a href="{{ route('new.compare', ['cars' => $car->id]) }}" class="compare-cta-btn compare-cta-btn-secondary flex items-center justify-center gap-2">
            <i class="fas fa-code-compare"></i>
            <span>أضف للمقارنة</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
  // 1. Main Hero Image Selection
  function changeMainImage(thumbEl, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('#hero-thumbnails .thumbnail-item').forEach(el => el.classList.remove('active'));
    thumbEl.classList.add('active');
  }

  // 2. Color Selection
  function selectColor(btn) {
    document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
  }

  // 3. Gallery State
  const galleryImages = {
    exterior: [
      @foreach($exteriorImages as $img) "{{ asset('storage/' . $img->image_path) }}"{{ !$loop->last ? ',' : '' }} @endforeach
    ],
    interior: [
      @foreach($interiorImages as $img) "{{ asset('storage/' . $img->image_path) }}"{{ !$loop->last ? ',' : '' }} @endforeach
    ]
  };

  let currentGalleryIndex = { exterior: 0, interior: 0 };

  function changeGalleryImage(gallery, direction) {
    const images = galleryImages[gallery];
    if (!images || images.length === 0) return;

    currentGalleryIndex[gallery] += direction;

    if (currentGalleryIndex[gallery] < 0) {
      currentGalleryIndex[gallery] = images.length - 1;
    } else if (currentGalleryIndex[gallery] >= images.length) {
      currentGalleryIndex[gallery] = 0;
    }

    updateGalleryDisplay(gallery);
    updateActiveThumbnail(gallery);
  }

  function selectGalleryImage(gallery, index) {
    currentGalleryIndex[gallery] = index;
    updateGalleryDisplay(gallery);
    updateActiveThumbnail(gallery);
  }

  function updateGalleryDisplay(gallery) {
    const index = currentGalleryIndex[gallery];
    const images = galleryImages[gallery];
    const mainImage = document.getElementById(gallery + 'MainImage');
    const counter = document.getElementById(gallery + 'Counter');

    if (mainImage && images[index]) {
      mainImage.src = images[index];
    }
    if (counter) {
      counter.textContent = index + 1;
    }
  }

  function updateActiveThumbnail(gallery) {
    const container = document.getElementById(gallery + '-thumbnails');
    if (!container) return;

    const thumbs = container.querySelectorAll('.gallery-thumb');
    thumbs.forEach((thumb, idx) => {
      if (idx === currentGalleryIndex[gallery]) {
        thumb.classList.add('active');
      } else {
        thumb.classList.remove('active');
      }
    });
  }

  // Trigger ViewContent tracking event across Meta, TikTok, Snapchat & GTM
  if (typeof trackEvent === 'function') {
    trackEvent('view_item', {
      content_name: '{{ addslashes($car->name) }}',
      content_id: '{{ $car->id }}',
      content_type: 'product',
      value: {{ (float) ($car->activeOffer && $car->activeOffer->special_price ? $car->activeOffer->special_price : $car->cash_price) }},
      currency: 'SAR'
    });
  }
</script>
@endpush