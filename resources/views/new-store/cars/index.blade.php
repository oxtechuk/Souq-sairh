@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - جميع السيارات')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/all-cars-hero/all-cars-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/cars-search-section/cars-search-section.css') }}" />
<style>
  .brand-item.selected {
    background: #1A3263 !important;
    color: white;
    transform: translateX(-2px);
  }
  .brand-item.selected .brand-name {
    color: white;
  }
  .brand-item.selected .brand-count {
    background: rgba(255,255,255,0.2);
    color: white;
  }
  .brand-item.selected .brand-icon {
    background: rgba(255,255,255,0.2);
    color: white;
  }
</style>
@endpush

@section('content')

{{-- 1. Hero Section --}}
@php $breadcrumbBg = $settings->get('breadcrumb_bg'); @endphp
<section class="all-cars-hero" dir="rtl" style="{{ $breadcrumbBg ? 'background-image: url(' . asset('storage/' . $breadcrumbBg) . ');' : '' }}">
  <div class="hero-overlay"></div>
  <div class="hero-content">
  </div>
</section>

{{-- 2. Cars Search Section --}}
<section class="cars-search-section bg-white py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Title --}}
    <div class="text-center mb-8">
      <h2 class="text-3xl font-extrabold text-primary mb-2">
        اختر من <span class="text-gold">مجموعتنا المميزة</span>
      </h2>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

      {{-- Filter Overlay (mobile) --}}
      <div class="filter-overlay" id="filter-overlay"></div>

      {{-- Search Sidebar --}}
      <div class="lg:col-span-1 order-2 lg:order-1 search-sidebar-wrapper" id="search-sidebar-wrapper">
        <div class="search-sidebar">

          {{-- Search Header --}}
          <div class="search-header">
            <button class="search-btn">
              <i class="fas fa-search"></i>
              <span>البحث</span>
            </button>
          </div>

          {{-- Tabs --}}
          <div class="search-tabs">
            <button type="button" class="search-tab active" data-tab="brands">
              العلامات
            </button>
            <button type="button" class="search-tab" data-tab="other">
              فلاتر أخرى
            </button>
          </div>

          {{-- Brand List --}}
          <div class="brand-list" id="brand-list">
            @include('new-store.partials.brand-list-items', ['brands' => $brands])
          </div>

          {{-- Other Filters --}}
          <div class="other-filters" id="other-filters" style="display: none;">

            {{-- Price Range --}}
            <div class="filter-section">
              <div class="filter-section-header">
                <h4 class="filter-section-title">نطاق السعر</h4>
                <i class="fas fa-sliders-h"></i>
              </div>
              <div class="price-range">
                <div class="price-input-group">
                  <label class="price-label">من</label>
                  <div class="price-input-wrapper">
                    <input type="text" value="{{ request('min_price') ? number_format(request('min_price')).' ر.س' : '0 ر.س' }}" class="price-input" id="price-min-display" readonly />
                    <i class="fas fa-pen price-edit-icon"></i>
                  </div>
                  <input type="range" min="0" max="500000" value="{{ request('min_price', 0) }}" class="price-slider filter-input" id="price-min" name="min_price" />
                </div>
                <div class="price-input-group">
                  <label class="price-label">إلى</label>
                  <div class="price-input-wrapper">
                    <input type="text" value="{{ request('max_price') ? number_format(request('max_price')).' ر.س' : '200,000 ر.س' }}" class="price-input" id="price-max-display" readonly />
                    <i class="fas fa-pen price-edit-icon"></i>
                  </div>
                  <input type="range" min="0" max="500000" value="{{ request('max_price', 200000) }}" class="price-slider filter-input" id="price-max" name="max_price" />
                </div>
              </div>
            </div>

            {{-- Engine Type --}}
            <div class="filter-section">
              <h4 class="filter-section-title">نظام المحرك</h4>
              <select name="engine_type" class="filter-select filter-input">
                <option value="">الكل</option>
                <option value="petrol" {{ request('engine_type') == 'petrol' ? 'selected' : '' }}>بنزين</option>
                <option value="diesel" {{ request('engine_type') == 'diesel' ? 'selected' : '' }}>ديزل</option>
                <option value="hybrid" {{ request('engine_type') == 'hybrid' ? 'selected' : '' }}>هايبرد</option>
                <option value="electric" {{ request('engine_type') == 'electric' ? 'selected' : '' }}>كهربائي</option>
              </select>
            </div>

            {{-- Transmission --}}
            <div class="filter-section">
              <h4 class="filter-section-title">ناقل الحركة</h4>
              <select name="transmission" class="filter-select filter-input">
                <option value="">الكل</option>
                <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>أوتوماتيك</option>
                <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>يدوي</option>
              </select>
            </div>

            {{-- Fuel Type --}}
            <div class="filter-section">
              <h4 class="filter-section-title">نوع الوقود</h4>
              <select name="fuel_type" class="filter-select filter-input">
                <option value="">الكل</option>
                <option value="petrol" {{ request('fuel_type') == 'petrol' ? 'selected' : '' }}>بنزين</option>
                <option value="diesel" {{ request('fuel_type') == 'diesel' ? 'selected' : '' }}>ديزل</option>
                <option value="electric" {{ request('fuel_type') == 'electric' ? 'selected' : '' }}>كهربائي</option>
              </select>
            </div>

          </div>
        </div>
      </div>

      {{-- Right Content Area --}}
      <div class="lg:col-span-3 order-1 lg:order-2">

        {{-- Mobile Filter Toggle + Search Bar --}}
        <form action="{{ route('new.cars.index') }}" method="GET" class="flex gap-3 mb-6" dir="rtl" id="search-form">
          <button type="button" id="mobile-filter-btn" class="mobile-filter-toggle lg:hidden">
            <i class="fas fa-sliders-h"></i>
            <span>الفلاتر</span>
          </button>

          <div class="search-input-wrapper flex-1">
            <input type="text" name="search" placeholder="ابحث عن سيارة..."
              value="{{ request('search') ?? request('q') }}"
              class="search-input filter-input" />
          </div>

          <button type="submit" class="search-button">
            <i class="fas fa-search"></i>
            <span>بحث</span>
          </button>
        </form>

        {{-- Filter Tabs --}}
        <div class="filter-tabs-row mb-6" dir="rtl">
          <button type="button" class="filter-tab {{ !request('type') ? 'active' : '' }}" data-type="">
            الكل
          </button>
          @foreach($types as $key => $label)
            <button type="button" class="filter-tab {{ request('type') == $key ? 'active' : '' }}" data-type="{{ $key }}">
              {{ $label }}
            </button>
          @endforeach
        </div>

        {{-- Results Count --}}
        <div class="flex items-center justify-end mb-6">
          <p class="text-gray-600">
            <span class="text-gold font-bold" id="results-count">{{ $cars->total() }}</span> سيارة متاحة
          </p>
        </div>

        {{-- Cars Grid --}}
        <div id="cars-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          @forelse($cars as $car)
            @include('new-store.partials.car-card', ['car' => $car])
          @empty
            <div class="col-span-full text-center py-12">
              <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
              <h3 class="text-xl font-bold text-gray-500">لا توجد سيارات تطابق بحثك</h3>
            </div>
          @endforelse
        </div>

        {{-- Load More Button --}}
        @if($cars->hasPages())
          <div class="text-center mt-8" id="load-more-container">
            <button id="load-more-btn" class="bg-white text-primary border-2 border-primary px-8 py-3 rounded-lg font-bold hover:bg-primary hover:text-white transition-all">
              تحميل المزيد
            </button>
          </div>
          <div class="hidden pagination-links">
            {{ $cars->links() }}
          </div>
        @endif

      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
  // Price Slider Sync (display only — AJAX fires on native 'change' release)
  const priceMin = document.getElementById('price-min');
  const priceMax = document.getElementById('price-max');
  const priceMinDisplay = document.getElementById('price-min-display');
  const priceMaxDisplay = document.getElementById('price-max-display');

  function formatPrice(value) {
    return Number(value).toLocaleString('en-US') + ' ر.س';
  }

  function updatePriceSlider(slider, display) {
    display.value = formatPrice(slider.value);
    var pct = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
    slider.style.background = 'linear-gradient(to left, #1A3263 0%, #1A3263 ' + pct + '%, #E5E7EB ' + pct + '%, #E5E7EB 100%)';
  }

  if (priceMin) {
    priceMin.addEventListener('input', function () { updatePriceSlider(this, priceMinDisplay); });
    updatePriceSlider(priceMin, priceMinDisplay);
  }

  if (priceMax) {
    priceMax.addEventListener('input', function () { updatePriceSlider(this, priceMaxDisplay); });
    updatePriceSlider(priceMax, priceMaxDisplay);
  }

  // Mobile filter toggle
  const filterBtn = document.getElementById('mobile-filter-btn');
  const sidebarWrapper = document.getElementById('search-sidebar-wrapper');
  const filterOverlay = document.getElementById('filter-overlay');

  function openFilter() {
    sidebarWrapper.classList.add('open');
    filterOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeFilter() {
    sidebarWrapper.classList.remove('open');
    filterOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (filterBtn) filterBtn.addEventListener('click', openFilter);
  if (filterOverlay) filterOverlay.addEventListener('click', closeFilter);

  // Search Tabs logic
  var searchTabs = document.querySelectorAll('.search-tab');
  var brandList = document.getElementById('brand-list');
  var otherFilters = document.getElementById('other-filters');

  searchTabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      searchTabs.forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');

      if (tab.dataset.tab === 'brands') {
        brandList.style.display = 'block';
        otherFilters.style.display = 'none';
      } else {
        brandList.style.display = 'none';
        otherFilters.style.display = 'block';
      }
    });
  });

  // Build query string manually to keep brand[] unencoded
  function collectParams() {
    var parts = [];

    var searchInput = document.querySelector('.search-input');
    if (searchInput && searchInput.value) parts.push('search=' + encodeURIComponent(searchInput.value));

    if (currentType) parts.push('type=' + encodeURIComponent(currentType));

    var selectedBrand = document.querySelector('#brand-list .brand-item.selected');
    if (selectedBrand) parts.push('brand_id=' + encodeURIComponent(selectedBrand.dataset.brandId));

    if (priceMin && parseInt(priceMin.value) > 0) parts.push('min_price=' + encodeURIComponent(priceMin.value));
    if (priceMax && parseInt(priceMax.value) < 500000) parts.push('max_price=' + encodeURIComponent(priceMax.value));

    var engineSelect = document.querySelector('#other-filters select[name="engine_type"]');
    if (engineSelect && engineSelect.value) parts.push('engine_type=' + encodeURIComponent(engineSelect.value));

    var transSelect = document.querySelector('#other-filters select[name="transmission"]');
    if (transSelect && transSelect.value) parts.push('transmission=' + encodeURIComponent(transSelect.value));

    var fuelSelect = document.querySelector('#other-filters select[name="fuel_type"]');
    if (fuelSelect && fuelSelect.value) parts.push('fuel_type=' + encodeURIComponent(fuelSelect.value));

    return parts.join('&');
  }

  // Brand single-select: clicking selects one, deselects others
  document.getElementById('brand-list').addEventListener('click', function (e) {
    var item = e.target.closest('.brand-item');
    if (!item) return;

    var wasSelected = item.classList.contains('selected');
    this.querySelectorAll('.brand-item').forEach(function (el) { el.classList.remove('selected'); });

    if (!wasSelected) {
      item.classList.add('selected');
    }

    currentPage = 1;
    fetchCars();
  });

  // AJAX Filtering & Load More
  var currentType = '{{ request('type') }}';
  var currentPage = 1;
  var lastPage = {{ $cars->lastPage() }};
  var loadMoreBtn = document.getElementById('load-more-btn');
  var loadMoreContainer = document.getElementById('load-more-container');
  var activeRequest = null;

  async function fetchCars(url, append) {
    if (append === undefined) append = false;

    // Cancel any in-flight request
    if (activeRequest) { activeRequest.abort(); }

    var controller = new AbortController();
    activeRequest = controller;

    var grid = document.getElementById('cars-grid');
    var resultsCount = document.getElementById('results-count');

    if (grid) grid.style.opacity = '0.5';

    try {
      var finalUrl = url || ('{{ route('new.cars.index') }}?' + collectParams());

      var response = await fetch(finalUrl, {
        signal: controller.signal,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });

      var data = await response.json();

      if (data.html !== undefined) {
        if (append) {
          grid.insertAdjacentHTML('beforeend', data.html);
        } else {
          grid.innerHTML = data.html;
        }

        if (resultsCount) resultsCount.textContent = data.total;

        if (data.brands_html) {
          var bl = document.getElementById('brand-list');
          if (bl) bl.innerHTML = data.brands_html;
        }

        if (data.last_page) lastPage = data.last_page;

        if (loadMoreContainer) {
          if (data.next_page_url) {
            loadMoreContainer.classList.remove('hidden');
          } else {
            loadMoreContainer.classList.add('hidden');
          }
        }

        window.history.pushState({}, '', finalUrl);
      }
    } catch (e) {
      if (e.name !== 'AbortError') console.error(e);
    } finally {
      if (grid) grid.style.opacity = '1';
      if (activeRequest === controller) activeRequest = null;
    }
  }

  // Load More
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', async function () {
      currentPage++;
      var qs = collectParams();
      if (qs) qs += '&';
      qs += 'page=' + currentPage;
      await fetchCars('{{ route('new.cars.index') }}?' + qs, true);

      if (currentPage >= lastPage) {
        loadMoreContainer.classList.add('hidden');
      }
    });
  }

  // Initial page check
  if (loadMoreContainer && currentPage >= lastPage) {
    loadMoreContainer.classList.add('hidden');
  }

  // Bind filter inputs (delegated — brand list DOM gets replaced)
  document.addEventListener('change', function (e) {
    if (e.target.classList.contains('filter-input')) {
      currentPage = 1;
      fetchCars();
    }
  });

  // Bind search form submit
  var searchForm = document.getElementById('search-form');
  if (searchForm) {
    searchForm.addEventListener('submit', function (e) {
      e.preventDefault();
      currentPage = 1;
      fetchCars();
    });
  }

  // Bind filter tabs (now buttons)
  document.querySelectorAll('.filter-tab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.filter-tab').forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');
      currentType = tab.dataset.type;
      currentPage = 1;
      fetchCars();
    });
  });
</script>
@endpush