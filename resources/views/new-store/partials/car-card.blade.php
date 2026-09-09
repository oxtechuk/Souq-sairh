@php
    $displayName = $car->display_name;

    $hasDiscount = false;
    $discountPrice = null;
    if (isset($car->activeOffer) && $car->activeOffer && !empty($car->activeOffer->special_price) && $car->activeOffer->special_price < $car->cash_price) {
        $hasDiscount = true;
        $discountPrice = $car->activeOffer->special_price;
    }

    $thumbnail = $car->thumbnail;
    if ($thumbnail) {
        if (\Illuminate\Support\Str::startsWith($thumbnail, ['http://', 'https://'])) {
            $carThumbnailUrl = $thumbnail;
        } elseif (\Illuminate\Support\Str::startsWith($thumbnail, 'new-store')) {
            $carThumbnailUrl = asset($thumbnail);
        } else {
            $carThumbnailUrl = asset('storage/' . $thumbnail);
        }
    } else {
        $carThumbnailUrl = asset('new-store/images/car-1.png');
    }

    $typeLabels = [
        'sedan' => 'سيدان',
        'suv' => 'SUV',
        'coupe' => 'كوبيه',
        'hatchback' => 'هاتشباك',
        'pickup' => 'بيك أب',
        'van' => 'فان',
        'other' => 'أخرى'
    ];
@endphp

<div class="car-card shrink-0 w-[280px] bg-white rounded-[22px] overflow-hidden border border-[#D5DDEB] hover:border-primary/40 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group" dir="rtl">
  
  {{-- Card Header: Car Image with High Quality & No Pixelation --}}
  <div class="relative bg-gradient-to-b from-[#F6F8FB] via-[#EEF2F8] to-[#E5ECF6] h-[195px] overflow-hidden rounded-t-[21px] flex items-center justify-center p-3">
    
    <img src="{{ $carThumbnailUrl }}"
      alt="{{ $displayName }}"
      class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105"
      loading="lazy"
      decoding="async"
      style="image-rendering: auto; -webkit-backface-visibility: hidden; backface-visibility: hidden; transform: translateZ(0);"
      onerror="this.onerror=null; this.src='{{ asset('new-store/images/car-1.png') }}';" />

    {{-- Year Badge --}}
    <span class="absolute top-3.5 left-3.5 bg-primary/95 text-white px-3 py-1 rounded-full text-xs font-extrabold shadow-sm z-10 backdrop-blur-sm tracking-wide">
      {{ $car->year }}
    </span>

    {{-- Special Offer Badge if available --}}
    @if($hasDiscount)
      <span class="absolute top-3.5 right-3.5 bg-red-600 text-white px-2.5 py-1 rounded-full text-[11px] font-bold shadow-sm z-10 flex items-center gap-1">
        <i class="fas fa-tag text-[10px]"></i>
        <span>عرض خاص</span>
      </span>
    @endif
  </div>

  {{-- Title --}}
  <div class="h-[58px] px-4 pt-2.5 flex items-center justify-center">
    <h3 class="text-[16px] font-extrabold text-primary text-center leading-snug line-clamp-2 group-hover:text-primary-light transition-colors">
      {{ $displayName }}
    </h3>
  </div>

  {{-- Pricing Grid --}}
  <div class="grid grid-cols-2 py-2.5 px-3 border-t border-b border-[#E6ECF5] bg-[#FCFDFE]">
    {{-- Cash Price --}}
    <div class="flex flex-col items-center justify-center border-l border-[#E6ECF5] px-2">
      <p class="text-[11px] text-gray-500 font-medium mb-0.5">سعر الكاش</p>
      @if($hasDiscount)
        <p class="text-[16px] font-extrabold text-gold leading-tight">
          {{ number_format($discountPrice) }} <span class="text-[11px] font-bold text-primary">ر.س</span>
        </p>
        <p class="text-[11px] text-gray-400 line-through">{{ number_format($car->cash_price) }} ر.س</p>
      @else
        <p class="text-[16px] font-extrabold text-gold leading-tight">
          {{ number_format($car->cash_price) }} <span class="text-[11px] font-bold text-primary">ر.س</span>
        </p>
      @endif
    </div>

    {{-- Monthly Installment --}}
    <div class="flex flex-col items-center justify-center px-2">
      <p class="text-[11px] text-gray-500 font-medium mb-0.5">القسط الشهري</p>
      <p class="text-[16px] font-extrabold text-primary leading-tight">
        {{ $car->min_installment ? number_format($car->min_installment) : '---' }} <span class="text-[11px] font-bold text-gold">ر.س</span>
      </p>
      <p class="text-[10px] text-gray-400">شهرياً / تقريبي</p>
    </div>
  </div>

  {{-- Car Specifications (Enhanced Micro Badges) --}}
  <div class="grid grid-cols-2 gap-2 px-3.5 py-3 bg-[#F8FAFD] text-[12px]">
    {{-- Fuel Type --}}
    <div class="flex items-center gap-2 bg-white px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
      <i class="fas fa-gas-pump text-primary text-[13px] w-4 text-center flex-shrink-0"></i>
      <span class="truncate font-semibold text-gray-700">{{ $car->specs['fuel_type'] ?? ($car->specs['fuel'] ?? 'بنزين') }}</span>
    </div>

    {{-- Transmission --}}
    <div class="flex items-center gap-2 bg-white px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
      <i class="fas fa-cogs text-primary text-[13px] w-4 text-center flex-shrink-0"></i>
      <span class="truncate font-semibold text-gray-700">{{ $car->specs['transmission'] ?? 'أوتوماتيك' }}</span>
    </div>

    {{-- Engine Size --}}
    <div class="flex items-center gap-2 bg-white px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
      <i class="fas fa-gauge-high text-primary text-[13px] w-4 text-center flex-shrink-0"></i>
      <span class="truncate font-semibold text-gray-700" dir="ltr">{{ $car->specs['engine_size'] ?? ($car->specs['engine_capacity'] ?? '5.0') }}</span>
    </div>

    {{-- Body Type --}}
    <div class="flex items-center gap-2 bg-white px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
      <i class="fas fa-car-side text-primary text-[13px] w-4 text-center flex-shrink-0"></i>
      <span class="truncate font-semibold text-gray-700">{{ $typeLabels[$car->type] ?? ($car->specs['type'] ?? 'SUV') }}</span>
    </div>
  </div>

  {{-- Action Buttons (Compare + Details Side by Side) --}}
  <div class="p-3.5 pt-2 flex items-center gap-2 border-t border-[#E6ECF5]">
    {{-- Button 1: Details / Car Page --}}
    <a href="{{ route('new.cars.show', $car->slug) }}"
      class="flex-1 h-[44px] flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold text-[14px] shadow-sm hover:shadow-md transition-all active:scale-[0.98]">
      <span>عرض التفاصيل</span>
      <i class="fas fa-chevron-left text-[11px] transition-transform group-hover:-translate-x-1"></i>
    </a>

    {{-- Button 2: Compare Button --}}
    <a href="{{ route('new.compare', ['cars' => $car->id]) }}"
      title="أضف للمقارنة"
      class="h-[44px] px-3.5 flex items-center justify-center gap-1.5 bg-[#FFF9E6] hover:bg-[#FFE8A0] text-primary border border-[#FFE28A] hover:border-[#FFD255] rounded-xl font-bold text-[13px] transition-all whitespace-nowrap active:scale-[0.98] shadow-sm">
      <i class="fas fa-code-compare text-primary text-[13px]"></i>
      <span>قارن</span>
    </a>
  </div>

</div>
