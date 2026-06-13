@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - مقارنة السيارات')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/car-comparison/car-comparison.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/comparison-table/comparison-table.css') }}" />
<style>
    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 50;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }
    .search-dropdown.active {
        display: block;
    }
    .search-item {
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .search-item:hover {
        background: #f3f4f6;
    }
    .search-item img {
        width: 50px;
        height: 40px;
        object-fit: cover;
        border-radius: 0.25rem;
    }
</style>
@endpush

@section('content')

{{-- 1. Car Comparison Header & Grid --}}
<section class="car-comparison-section py-16 bg-gray-50" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="text-center mb-12">
      <h1 class="text-4xl font-extrabold text-primary mb-4">قارن <span class="text-gold">بين السيارات</span></h1>
      <p class="text-lg text-gray-600">قارن بين سيارتين لتحدد اختيارك الأفضل</p>
    </div>

    <div class="comparison-grid flex flex-col md:flex-row gap-8 justify-center">
      
      @for($i = 0; $i < 2; $i++)
        @if(isset($cars[$i]))
          {{-- Selected Car Slot --}}
          <div class="comparison-slot car-comparison-card bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex-1 relative">
            <div class="comparison-card-image-section relative h-48 bg-gray-50 rounded-xl mb-6 flex items-center justify-center p-4">
              <span class="absolute top-4 left-4 bg-primary text-white px-3 py-1 rounded-full text-xs font-bold">{{ $cars[$i]->year }}</span>
              <img src="{{ $cars[$i]->thumbnail ? asset('storage/'.$cars[$i]->thumbnail) : asset('new-store/images/car-1.png') }}" alt="{{ $cars[$i]->name }}" class="max-h-full object-contain" loading="lazy" />
              <button class="absolute bottom-4 right-4 bg-white/90 hover:bg-white text-primary px-4 py-2 rounded-lg shadow text-sm font-bold transition-colors flex items-center gap-2" onclick="openSearchModal({{ $i }})">
                <i class="fas fa-sync-alt"></i> تغيير
              </button>
            </div>

            <div class="flex items-start justify-between mb-6">
              <h3 class="text-xl font-bold text-primary">{{ $cars[$i]->name }} <span dir="ltr">{{ $cars[$i]->model }}</span></h3>
              <a href="{{ route('new.compare', ['cars' => getRemainingCars($cars, $i)]) }}" class="text-red-500 hover:bg-red-50 p-2 rounded-full transition-colors" title="حذف">
                <i class="fas fa-trash-alt"></i>
              </a>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-6">
              <div>
                <p class="text-xs text-gray-500 mb-1">سعر الكاش</p>
                <p class="text-lg font-bold text-primary">{{ number_format($cars[$i]->cash_price) }} ريال</p>
              </div>
              <div class="border-r border-gray-100 pr-4">
                <p class="text-xs text-gray-500 mb-1">القسط الشهري</p>
                <p class="text-lg font-bold text-primary">{{ $cars[$i]->min_installment ?? '---' }} ريال</p>
                <p class="text-[10px] text-gray-400">تقريبي</p>
              </div>
            </div>
          </div>
        @else
          {{-- Empty Add Car Slot --}}
          <div class="comparison-slot add-car-slot bg-white border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center min-h-[350px] flex-1 cursor-pointer hover:border-primary hover:bg-blue-50 transition-colors group relative" id="search-slot-{{ $i }}">
            <div class="w-16 h-16 bg-gray-100 group-hover:bg-primary group-hover:text-white rounded-full flex items-center justify-center text-2xl text-gray-400 mb-4 transition-colors" onclick="toggleSearch({{ $i }})">
              <i class="fas fa-plus"></i>
            </div>
            <p class="font-bold text-gray-500 group-hover:text-primary transition-colors">أضف سيارة للمقارنة</p>
            
            {{-- Search Dropdown Overlay inside empty slot --}}
            <div class="absolute inset-0 bg-white rounded-2xl p-6 hidden z-20 shadow-lg border border-gray-100 flex-col" id="search-container-{{ $i }}">
              <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-primary">ابحث عن سيارة</h4>
                <button class="text-gray-400 hover:text-red-500" onclick="toggleSearch({{ $i }})"><i class="fas fa-times"></i></button>
              </div>
              <div class="relative flex-1 flex flex-col">
                <input type="text" placeholder="اسم السيارة..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary mb-2" onkeyup="searchCars(this.value, {{ $i }})">
                <div class="search-results overflow-y-auto flex-1 bg-gray-50 rounded-lg p-2" id="search-results-{{ $i }}">
                   <p class="text-sm text-gray-400 text-center py-4">اكتب للبحث...</p>
                </div>
              </div>
            </div>
          </div>
        @endif
      @endfor
      
    </div>
  </div>
</section>

{{-- 2. Comparison Table --}}
@if(count($cars) > 0)
<section class="comparison-table-section py-16 bg-white" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="comparison-table-header mb-12 text-center">
      <h2 class="text-3xl font-extrabold text-primary mb-2">جدول المقارنة التفصيلي</h2>
      <p class="text-gray-500">مقارنة تفصيلية بين المواصفات والأسعار</p>
    </div>

    <div class="comparison-table bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
      
      {{-- Overview Section --}}
      <div class="bg-blue-50 py-4 px-6 border-b border-gray-200">
        <h3 class="font-bold text-primary text-lg">نظرة عامة</h3>
      </div>
      <div class="grid grid-cols-3 border-b border-gray-100">
        <div class="p-4 text-gray-500 font-bold bg-gray-50">الموديل</div>
        <div class="p-4 font-bold text-primary border-r border-gray-100">{{ isset($cars[0]) ? $cars[0]->name . ' ' . $cars[0]->year : '---' }}</div>
        <div class="p-4 font-bold text-primary border-r border-gray-100">{{ isset($cars[1]) ? $cars[1]->name . ' ' . $cars[1]->year : '---' }}</div>
      </div>
      <div class="grid grid-cols-3 border-b border-gray-100">
        <div class="p-4 text-gray-500 font-bold bg-gray-50">سعر الكاش</div>
        <div class="p-4 text-gold font-bold border-r border-gray-100">{{ isset($cars[0]) ? number_format($cars[0]->cash_price) . ' ريال' : '---' }}</div>
        <div class="p-4 text-gold font-bold border-r border-gray-100">{{ isset($cars[1]) ? number_format($cars[1]->cash_price) . ' ريال' : '---' }}</div>
      </div>

      {{-- Specs Section --}}
      @php
        $allSpecs = [];
        foreach($cars as $car) {
            foreach($car->specifications as $spec) {
                $allSpecs[$spec->name] = $spec->name;
            }
        }
      @endphp

      @if(count($allSpecs) > 0)
        <div class="bg-blue-50 py-4 px-6 border-b border-y border-gray-200 mt-4">
          <h3 class="font-bold text-primary text-lg">المواصفات التقنية</h3>
        </div>
        @foreach($allSpecs as $specName)
          <div class="grid grid-cols-3 border-b border-gray-100">
            <div class="p-4 text-gray-500 font-bold bg-gray-50">{{ $specName }}</div>
            <div class="p-4 text-gray-700 border-r border-gray-100">
                @if(isset($cars[0]))
                    @php $s1 = $cars[0]->specifications->firstWhere('name', $specName); @endphp
                    {{ $s1 ? ($s1->pivot->value ?? $s1->type) : '---' }}
                @else --- @endif
            </div>
            <div class="p-4 text-gray-700 border-r border-gray-100">
                @if(isset($cars[1]))
                    @php $s2 = $cars[1]->specifications->firstWhere('name', $specName); @endphp
                    {{ $s2 ? ($s2->pivot->value ?? $s2->type) : '---' }}
                @else --- @endif
            </div>
          </div>
        @endforeach
      @endif

    </div>
  </div>
</section>
@endif


@endsection

@php
function getRemainingCars($cars, $indexToRemove) {
    $ids = [];
    foreach($cars as $idx => $car) {
        if($idx !== $indexToRemove) $ids[] = $car->id;
    }
    return implode(',', $ids);
}
@endphp

@push('scripts')
<script>
  let existingCarIds = [
      @foreach($cars as $c) {{ $c->id }}, @endforeach
  ];

  function toggleSearch(index) {
      const container = document.getElementById('search-container-' + index);
      if(container.classList.contains('hidden')) {
          container.classList.remove('hidden');
          container.classList.add('flex');
      } else {
          container.classList.add('hidden');
          container.classList.remove('flex');
      }
  }

  function openSearchModal(index) {
      // If we want to replace an existing car, we can just reload the page without it, or show a search modal over it.
      // Easiest is to remove it first and then open search.
      existingCarIds.splice(index, 1);
      const url = new URL(window.location.href);
      url.searchParams.set('cars', existingCarIds.join(','));
      window.location.href = url.toString();
  }

  let searchTimeout = null;
  function searchCars(query, index) {
      clearTimeout(searchTimeout);
      const resultsContainer = document.getElementById('search-results-' + index);
      
      if(query.length < 2) {
          resultsContainer.innerHTML = '<p class="text-sm text-gray-400 text-center py-4">اكتب المزيد للبحث...</p>';
          return;
      }

      resultsContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-primary"></i></div>';

      searchTimeout = setTimeout(() => {
          fetch(`{{ route('new.compare.search') }}?q=${encodeURIComponent(query)}`)
              .then(res => res.json())
              .then(data => {
                  resultsContainer.innerHTML = '';
                  if(data.length === 0) {
                      resultsContainer.innerHTML = '<p class="text-sm text-red-400 text-center py-4">لا توجد نتائج</p>';
                      return;
                  }
                  
                  data.forEach(car => {
                      const div = document.createElement('div');
                      div.className = 'flex items-center gap-3 p-2 hover:bg-white rounded cursor-pointer border-b border-gray-100 transition-colors';
                      div.innerHTML = `
                          <img src="${car.image}" class="w-12 h-10 object-cover rounded" loading="lazy" />
                          <div>
                              <p class="font-bold text-primary text-sm">${car.name}</p>
                              <p class="text-xs text-gray-500">${car.brand}</p>
                          </div>
                      `;
                      div.onclick = () => selectCar(car.id);
                      resultsContainer.appendChild(div);
                  });
              });
      }, 500);
  }

  function selectCar(id) {
      existingCarIds.push(id);
      const url = new URL(window.location.href);
      url.searchParams.set('cars', existingCarIds.join(','));
      window.location.href = url.toString();
  }
</script>
@endpush
