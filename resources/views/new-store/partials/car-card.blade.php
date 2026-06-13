<div class="car-card shrink-0 w-[280px] bg-white rounded-[18px] overflow-hidden border border-[#B8C3D8]" dir="rtl">
  <div class="relative bg-[#EEF2F7] h-[215px] px-5 pt-5">
    <span class="absolute top-4 left-5 bg-primary text-white px-5 py-2 rounded-full text-xs font-bold">
      {{ $car->year }}
    </span>

    <img src="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}"
      alt="{{ $car->name }}"
      class="w-full h-[135px] object-contain mt-8" loading="lazy" />

    <a href="{{ route('new.compare', ['cars' => $car->id]) }}"
      class="absolute bottom-4 right-5 bg-[#FFF1C2] text-primary px-4 py-2 rounded-full font-bold text-xs flex items-center justify-center gap-2 whitespace-nowrap hover:bg-[#FFE8A0] transition-colors">
      <span>أضف للمقارنة</span>
      <i class="fas fa-code-compare text-xs"></i>
    </a>
  </div>

  <div class="h-[62px] px-4 flex items-center justify-center border-b border-[#C9D1E2]">
    <h3 class="text-[18px] font-extrabold text-primary text-center leading-tight">
      {{ $car->name }} <span dir="ltr">{{ $car->model }}</span>
    </h3>
  </div>

  <div class="grid grid-cols-2 h-[108px] border-b border-[#C9D1E2]">
    <div class="flex flex-col items-center justify-center border-l border-[#C9D1E2]">
      <p class="text-[11px] text-primary mb-1">سعر الكاش</p>
      <p class="text-[17px] font-extrabold text-gold mb-1">{{ number_format($car->cash_price) }} ريال</p>
      <p class="text-[11px] text-gray-500 line-through">{{ number_format($car->cash_price) }} ريال</p>
    </div>

    <div class="flex flex-col items-center justify-center">
      <p class="text-[11px] text-primary mb-1">القسط الشهري</p>
      <p class="text-[17px] font-extrabold text-gold mb-1">{{ $car->min_installment ? number_format($car->min_installment) : '---' }} ريال</p>
      <p class="text-[11px] text-gray-500">تقديري</p>
    </div>
  </div>

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

  <div class="px-8 pb-5">
    <a href="{{ route('new.cars.show', $car->slug) }}"
      class="w-full h-[62px] flex items-center justify-center bg-white text-primary border border-primary hover:bg-primary hover:text-white rounded-md font-extrabold text-[20px] transition-all">
      عرض التفاصيل
    </a>
  </div>
</div>
