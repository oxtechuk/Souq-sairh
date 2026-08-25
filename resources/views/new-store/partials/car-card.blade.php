@php
    $displayName = $car->display_name;

    $hasDiscount = false;
    $discountPrice = null;
    if (isset($car->activeOffer) && $car->activeOffer && !empty($car->activeOffer->special_price) && $car->activeOffer->special_price < $car->cash_price) {
        $hasDiscount = true;
        $discountPrice = $car->activeOffer->special_price;
    }
@endphp

<div class="car-card shrink-0 w-[280px] bg-white rounded-[18px] overflow-hidden border border-[#B8C3D8] hover:shadow-lg transition-shadow" dir="rtl">
  {{-- Card Header: Car Image filling full area --}}
  <div class="relative bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 animate-pulse h-[215px] overflow-hidden rounded-t-[18px]">
    <img src="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}"
      alt="{{ $displayName }}"
      class="w-full h-full object-cover transition-all duration-500 opacity-0 hover:scale-105" loading="lazy"
      style="image-rendering: -webkit-optimize-contrast; image-rendering: high-quality;"
      onload="this.classList.remove('opacity-0'); this.parentElement.classList.remove('animate-pulse', 'bg-gradient-to-r', 'from-gray-200', 'via-gray-100', 'to-gray-200');" />

    <span class="absolute top-4 left-5 bg-primary/90 text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-sm z-10">
      {{ $car->year }}
    </span>

    <a href="{{ route('new.compare', ['cars' => $car->id]) }}"
      class="absolute bottom-4 right-5 bg-[#FFF1C2]/95 hover:bg-[#FFE8A0] text-primary px-4 py-1.5 rounded-full font-bold text-xs flex items-center justify-center gap-2 whitespace-nowrap transition-colors shadow-sm z-10">
      <span>أضف للمقارنة</span>
      <i class="fas fa-code-compare text-xs"></i>
    </a>
  </div>

  {{-- Title (No duplicated model) --}}
  <div class="h-[62px] px-4 flex items-center justify-center border-b border-[#C9D1E2]">
    <h3 class="text-[17px] font-extrabold text-primary text-center leading-tight line-clamp-2">
      {{ $displayName }}
    </h3>
  </div>

  {{-- Pricing Grid (No repeated price if no discount) --}}
  <div class="grid grid-cols-2 h-[108px] border-b border-[#C9D1E2]">
    <div class="flex flex-col items-center justify-center border-l border-[#C9D1E2]">
      <p class="text-[11px] text-primary mb-1">سعر الكاش</p>
      @if($hasDiscount)
        <p class="text-[17px] font-extrabold text-gold mb-0.5">{{ number_format($discountPrice) }} ريال</p>
        <p class="text-[11px] text-gray-500 line-through">{{ number_format($car->cash_price) }} ريال</p>
      @else
        <p class="text-[17px] font-extrabold text-gold">{{ number_format($car->cash_price) }} ريال</p>
      @endif
    </div>

    <div class="flex flex-col items-center justify-center">
      <p class="text-[11px] text-primary mb-1">القسط الشهري</p>
      <p class="text-[17px] font-extrabold text-gold mb-1">{{ $car->min_installment ? number_format($car->min_installment) : '---' }} ريال</p>
      <p class="text-[11px] text-gray-500">تقديري</p>
    </div>
  </div>

  {{-- Car Specifications --}}
  <div class="grid grid-cols-2 gap-x-14 gap-y-5 px-6 py-5 text-primary text-[13px]" dir="rtl">
    <div class="flex items-center justify-start gap-2">
      <i class="fas fa-gas-pump w-4 text-center flex-shrink-0"></i>
      <span>{{ $car->specs['fuel_type'] ?? 'بنزين' }}</span>
    </div>

    <div class="flex items-center justify-start gap-2">
      <i class="fas fa-cogs w-4 text-center flex-shrink-0"></i>
      <span>{{ $car->specs['transmission'] ?? 'أتوماتيك' }}</span>
    </div>

    <div class="flex items-center justify-start gap-2">
      <i class="fas fa-chair w-4 text-center flex-shrink-0"></i>
      <span dir="ltr">{{ $car->specs['engine_size'] ?? ($car->specs['engine_capacity'] ?? '5.0') }}</span>
    </div>

    <div class="flex items-center justify-start gap-2">
      <i class="fas fa-car-side w-4 text-center flex-shrink-0"></i>
      @php
        $typeLabels = ['sedan' => 'سيدان', 'suv' => 'SUV', 'coupe' => 'كوبيه', 'hatchback' => 'هاتشباك', 'pickup' => 'بيك أب', 'van' => 'فان', 'other' => 'أخرى'];
      @endphp
      <span dir="ltr">{{ $typeLabels[$car->type] ?? 'SUV' }}</span>
    </div>
  </div>

  {{-- Action Button --}}
  <div class="px-8 pb-5">
    <a href="{{ route('new.cars.show', $car->slug) }}"
      class="w-full h-[62px] flex items-center justify-center bg-white text-primary border border-primary hover:bg-primary hover:text-white rounded-md font-extrabold text-[20px] transition-all">
      عرض التفاصيل
    </a>
  </div>
</div>
