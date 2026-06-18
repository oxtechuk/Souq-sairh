@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - حجز سيارة')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/all-cars-hero/all-cars-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/contact-form/contact-form.css') }}" />
@endpush

@section('content')

{{-- 1. Hero Section --}}
<section class="all-cars-hero" dir="rtl">
  <div class="all-cars-hero-bg"></div>
  <div class="all-cars-hero-content">
    <h1 class="all-cars-hero-title">احجز <span>سيارتك</span></h1>
    <p class="all-cars-hero-subtitle">اختر سيارتك المفضلة واحجز موعداً للتجربة أو تقديم طلب تمويل</p>
  </div>
</section>

{{-- 2. Booking Form Section --}}
<section class="contact-form-section" dir="rtl">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center font-bold text-lg">
        {{ session('success') }}
      </div>
    @endif

    <div class="contact-form-header">
      <h2 class="contact-form-title">طلب حجز سيارة</h2>
      <p class="contact-form-subtitle">املأ النموذج أدناه وسنقوم بالتواصل معك في أقرب وقت.</p>
    </div>

    {{-- Type Tabs --}}
    <div class="contact-type-tabs">
      <button type="button" class="contact-type-tab active" data-type="individuals">
        <i class="fas fa-user"></i>
        أفراد
      </button>
      <button type="button" class="contact-type-tab" data-type="companies">
        <i class="fas fa-building"></i>
        شركات
      </button>
      <button type="button" class="contact-type-tab" data-type="financing">
        <i class="fas fa-credit-card"></i>
        تمويل
      </button>
    </div>

    <form action="{{ route('new.booking.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Row 1: Name + Phone (visible for all tabs) --}}
      <div class="contact-form-grid">
        <div class="contact-form-group">
          <label>الاسم الكامل <span>*</span></label>
          <div class="contact-input-wrapper">
            <i class="fas fa-user"></i>
            <input type="text" name="client_name" required placeholder="أدخل اسمك الكامل" value="{{ old('client_name') }}" />
          </div>
          @error('client_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="contact-form-group">
          <label>رقم الجوال <span>*</span></label>
          <div class="contact-input-wrapper">
            <i class="fas fa-phone"></i>
            <input type="tel" name="client_phone" required placeholder="05xxxxxxxx" dir="ltr" value="{{ old('client_phone') }}" />
          </div>
          @error('client_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
      </div>

      {{-- Row 2: Car + City (visible for all tabs) --}}
      <div class="contact-form-grid">
        <div class="contact-form-group">
          <label>موديل السيارة المطلوب <span>*</span></label>
          <div class="contact-input-wrapper">
            <i class="fas fa-car"></i>
            <select name="car_id" id="booking-car-select">
              <option value="">{{ __('اختر سيارة (اختياري)') }}</option>
              @foreach($cars as $car)
                <option value="{{ $car->id }}"
                  data-thumbnail="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}"
                  data-brand="{{ $car->brand->name ?? '' }}"
                  data-name="{{ $car->name }}"
                  data-year="{{ $car->year ?? '' }}"
                  data-price="{{ $car->cash_price }}"
                  data-installment="{{ $car->min_installment ?? '' }}"
                  {{ $selectedCar && $selectedCar->id == $car->id ? 'selected' : '' }}>
                  {{ $car->brand->name ?? '' }} {{ $car->name }} {{ $car->year ? '(' . $car->year . ')' : '' }}
                </option>
              @endforeach
            </select>
            <i class="fas fa-chevron-down select-arrow"></i>
          </div>
          @error('car_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="contact-form-group">
          <label>المدينة <span>*</span></label>
          <div class="contact-input-wrapper">
            <i class="fas fa-map-marker-alt"></i>
            <select name="city">
              <option value="" disabled selected>اختر المدينة...</option>
              <option value="الرياض">الرياض</option>
              <option value="جدة">جدة</option>
              <option value="الدمام">الدمام</option>
              <option value="مكة المكرمة">مكة المكرمة</option>
              <option value="المدينة المنورة">المدينة المنورة</option>
            </select>
            <i class="fas fa-chevron-down select-arrow"></i>
          </div>
        </div>
      </div>

      {{-- Selected Offer Banner --}}
      @if($selectedOffer)
      <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-4 flex items-center gap-3 border border-red-200 mb-4">
        <div class="w-12 h-12 rounded-full bg-red-500 text-white flex items-center justify-center flex-shrink-0" style="font-size:20px;">
          <i class="fas fa-tag"></i>
        </div>
        <div class="flex-1">
          <p class="font-bold text-red-700 text-sm">{{ $selectedOffer->title }}</p>
          @if($selectedOffer->discount_percent)
            <p class="text-sm text-red-600 font-bold">{{ __('خصم') }} {{ $selectedOffer->discount_percent }}%</p>
          @elseif($selectedOffer->discount_value)
            <p class="text-sm text-red-600 font-bold">{{ __('خصم') }} {{ number_format($selectedOffer->discount_value) }} {{ __('ريال') }}</p>
          @endif
        </div>
      </div>
      @endif

      {{-- Selected Car Preview --}}
      <div id="booking-car-preview" class="bg-gray-50 rounded-xl p-4 flex items-center gap-4 border border-gray-200 mb-4" style="display:{{ $selectedCar ? '' : 'none' }}">
        <div class="w-20 h-20 rounded-lg overflow-hidden bg-white flex-shrink-0">
          <img id="preview-thumb" src="{{ $selectedCar ? ($selectedCar->thumbnail ? asset('storage/'.$selectedCar->thumbnail) : asset('new-store/images/car-1.png')) : '' }}" alt="" class="w-full h-full object-contain">
        </div>
        <div>
          <p id="preview-title" class="font-bold text-primary">{{ $selectedCar ? ($selectedCar->brand->name ?? '') . ' ' . $selectedCar->name : '' }}</p>
          <p id="preview-price" class="text-sm text-gray-500">{{ $selectedCar ? number_format($selectedCar->cash_price) . ' ريال' : '' }}</p>
          <p id="preview-installment" class="text-sm text-gold" style="display:{{ $selectedCar && $selectedCar->min_installment ? '' : 'none' }}">{{ $selectedCar && $selectedCar->min_installment ? 'من ' . number_format($selectedCar->min_installment) . ' ريال / شهرياً' : '' }}</p>
        </div>
      </div>

      {{-- Row 3: Salary + Obligations (visible for all tabs) --}}
      <div class="contact-ranges-row">
        <div class="contact-range-group">
          <label class="block text-right text-sm font-bold mb-2" style="color:#1A3263;">الراتب <span style="color:#e53e3e;">*</span></label>
          <div class="contact-range-grid" id="booking-salary-group">
            <button type="button" class="contact-range-btn active" data-group="booking-salary">أقل من 2,000 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-salary">2,000-2,500 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-salary">2,600-3,000 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-salary">3,000-3,500 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-salary">3,600-4,000 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-salary">أكثر من 4,000 ﷼</button>
          </div>
          <div class="contact-input-wrapper mt-2">
            <i class="fas fa-pen"></i>
            <input type="text" id="booking-salary-manual" class="contact-manual-input" placeholder="أدخل الراتب يدوياً (اختياري)" />
          </div>
        </div>
        <div class="contact-range-group">
          <label class="block text-right text-sm font-bold mb-2" style="color:#1A3263;">الإلتزامات الشهرية <span style="color:#e53e3e;">*</span></label>
          <div class="contact-range-grid" id="booking-obligations-group">
            <button type="button" class="contact-range-btn active" data-group="booking-obligations">أقل من 1,000 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-obligations">700-1500 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-obligations">1,000 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-obligations">1,700-2000 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-obligations">1,800-2000 ﷼</button>
            <button type="button" class="contact-range-btn" data-group="booking-obligations">أكثر من 2,000 ﷼</button>
          </div>
          <div class="contact-input-wrapper mt-2">
            <i class="fas fa-pen"></i>
            <input type="text" id="booking-obligations-manual" class="contact-manual-input" placeholder="أدخل الإلتزامات يدوياً (اختياري)" />
          </div>
        </div>
      </div>

      {{-- Tab-specific fields --}}

      {{-- Individuals: extra fields --}}
      <div id="tab-individuals" class="tab-content">
        {{-- (لا توجد حقول إضافية للأفراد) --}}
      </div>

      {{-- Companies: extra fields --}}
      <div id="tab-companies" class="tab-content" style="display:none;">
        <div class="contact-form-grid">
          <div class="contact-form-group">
            <label>اسم المؤسسة <span>*</span></label>
            <div class="contact-input-wrapper">
              <i class="fas fa-building"></i>
              <input type="text" name="company_name" placeholder="اسم المؤسسة" value="{{ old('company_name') }}" />
            </div>
          </div>
          <div class="contact-form-group">
            <label>رقم السجل التجاري</label>
            <div class="contact-input-wrapper">
              <i class="fas fa-receipt"></i>
              <input type="text" name="tax_number" placeholder="رقم السجل التجاري" value="{{ old('tax_number') }}" />
            </div>
          </div>
        </div>
      </div>

      {{-- Financing: extra fields --}}
      <div id="tab-financing" class="tab-content" style="display:none;">
        <div class="contact-form-grid">
          <div class="contact-form-group">
            <label>الدفعة الأولى <span>*</span></label>
            <div class="contact-input-wrapper">
              <i class="fas fa-money-bill"></i>
              <input type="number" name="down_payment" placeholder="المبلغ الذي تقدمه كدفعة أولى" value="{{ old('down_payment') }}" />
            </div>
          </div>
          <div class="contact-form-group">
            <label>مدة التمويل <span>*</span></label>
            <div class="contact-input-wrapper">
              <i class="fas fa-clock"></i>
              <select name="financing_period">
                <option value="" disabled selected>اختر المدة...</option>
                <option value="12">سنة واحدة</option>
                <option value="24">سنتان</option>
                <option value="36">3 سنوات</option>
                <option value="48">4 سنوات</option>
                <option value="60">5 سنوات</option>
              </select>
              <i class="fas fa-chevron-down select-arrow"></i>
            </div>
          </div>
        </div>
        <div class="contact-form-group mb-4">
          <label>البريد الإلكتروني</label>
          <div class="contact-input-wrapper">
            <i class="fas fa-envelope"></i>
            <input type="email" name="client_email" placeholder="example@email.com" dir="ltr" value="{{ old('client_email') }}" />
          </div>
        </div>
      </div>

      {{-- File Upload (visible for all tabs) --}}
      <div class="contact-upload-group mb-4">
        <label>رفع الملفات <span>(اختياري)</span></label>
        <div class="contact-upload-zone" id="booking-upload-zone" onclick="document.getElementById('booking-file-input').click()">
          <i class="fas fa-cloud-upload-alt"></i>
          <p>إضغط أو أسحب الملفات هنا<br/><small>صور الهوية، رخصة القيادة، كشف الراتب (JPG, PNG, PDF)</small></p>
        </div>
        <input type="file" name="documents[]" id="booking-file-input" accept=".jpg,.jpeg,.png,.pdf" multiple style="display:none" />
        <div class="contact-upload-preview" id="booking-upload-preview"></div>
      </div>

      <input type="hidden" name="salary_range" id="booking-salary-range" value="" />
      <input type="hidden" name="obligations_range" id="booking-obligations-range" value="" />
      <input type="hidden" name="contact_type" id="booking-contact-type" value="individuals" />

      {{-- Notes + Submit --}}
      <div class="contact-notes-row">
        <div class="contact-notes-group">
          <label>ملاحظات إضافية</label>
          <textarea name="notes" placeholder="أخبرنا عن احتياجاتك أو استفسارك...">{{ old('notes') }}</textarea>
        </div>
        <div class="flex items-end">
          <button type="submit" class="contact-submit-btn">
            <i class="fas fa-paper-plane"></i>
            إرسال الطلب
          </button>
        </div>
      </div>

    </form>
  </div>
</section>

@endsection

@push('scripts')
<script>
  document.querySelectorAll('.contact-type-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.contact-type-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const type = tab.dataset.type;
      document.getElementById('booking-contact-type').value = type;
      document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
      const target = document.getElementById('tab-' + type);
      if (target) target.style.display = '';
    });
  });

  document.querySelectorAll('.contact-range-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.dataset.group;
      document.querySelectorAll('.contact-range-btn[data-group="' + group + '"]').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      if (group === 'booking-salary') {
        document.getElementById('booking-salary-range').value = btn.textContent.trim();
        document.getElementById('booking-salary-manual').value = '';
      } else if (group === 'booking-obligations') {
        document.getElementById('booking-obligations-range').value = btn.textContent.trim();
        document.getElementById('booking-obligations-manual').value = '';
      }
    });
  });

  document.getElementById('booking-salary-manual').addEventListener('input', function() {
    document.querySelectorAll('.contact-range-btn[data-group="booking-salary"]').forEach(b => b.classList.remove('active'));
    document.getElementById('booking-salary-range').value = this.value ? this.value + ' ريال' : '';
  });

  document.getElementById('booking-obligations-manual').addEventListener('input', function() {
    document.querySelectorAll('.contact-range-btn[data-group="booking-obligations"]').forEach(b => b.classList.remove('active'));
    document.getElementById('booking-obligations-range').value = this.value ? this.value + ' ريال' : '';
  });

  document.getElementById('booking-file-input').addEventListener('change', function() {
    const preview = document.getElementById('booking-upload-preview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
      const div = document.createElement('div');
      div.className = 'contact-upload-file';
      div.innerHTML = '<i class="fas fa-file"></i> ' + file.name;
      preview.appendChild(div);
    });
  });

  // Live car preview on select change
  const carSelect = document.getElementById('booking-car-select');
  const previewCard = document.getElementById('booking-car-preview');
  const previewThumb = document.getElementById('preview-thumb');
  const previewTitle = document.getElementById('preview-title');
  const previewPrice = document.getElementById('preview-price');
  const previewInstallment = document.getElementById('preview-installment');

  carSelect.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (opt && opt.value) {
      previewThumb.src = opt.dataset.thumbnail || '';
      previewThumb.alt = opt.dataset.name || '';
      previewTitle.textContent = (opt.dataset.brand || '') + ' ' + (opt.dataset.name || '');
      previewPrice.textContent = opt.dataset.price ? Number(opt.dataset.price).toLocaleString('en-US') + ' ريال' : '';
      if (opt.dataset.installment) {
        previewInstallment.textContent = 'من ' + Number(opt.dataset.installment).toLocaleString('en-US') + ' ريال / شهرياً';
        previewInstallment.style.display = '';
      } else {
        previewInstallment.style.display = 'none';
      }
      previewCard.style.display = '';
    } else {
      previewCard.style.display = 'none';
    }
  });
</script>
@endpush