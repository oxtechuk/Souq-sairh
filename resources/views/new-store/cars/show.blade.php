@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - ' . $car->name . ' ' . $car->model)

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/car-details-hero/car-details-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/technical-specs/technical-specs.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/car-gallery/car-gallery.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/features-list/features-list.css') }}" />
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
              {{ $car->name }} <span dir="ltr">{{ $car->model }}</span>
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

        {{-- Buttons --}}
        <div class="flex gap-4">
          <a href="{{ route('new.booking', ['car_id' => $car->id]) }}"
             class="flex-1 bg-primary text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-dark transition-all text-center">
            اطلبها الآن
          </a>
          <a href="{{ route('new.compare', ['cars' => $car->id]) }}"
             class="bg-white text-primary border-2 border-primary py-4 px-6 rounded-lg font-bold hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
            <i class="fas fa-code-compare"></i>
            أضف للمقارنة
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

{{-- 2. Technical Specifications --}}
@if($car->specifications && count($car->specifications) > 0)
<section class="py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-primary text-right mb-8">المواصفات التقنية</h2>
    <div id="technical-specs-grid" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
      @foreach($car->specifications as $spec)
        <div class="spec-card">
          <div class="spec-icon-wrapper">
            <i class="fas fa-cog spec-icon"></i>
          </div>
          <div class="spec-content">
            <p class="spec-label">{{ $spec->name }}</p>
            <p class="spec-value">{{ $spec->pivot->value ?? $spec->type }}</p>
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
</script>
@endpush