@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - حجز سيارة')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/all-cars-hero/all-cars-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/calculator/calculator.css') }}" />
<style>
  .upload-zone {
    border: 2px dashed #D1D5DB;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
    background: #F9FAFB;
    margin-bottom: 1.5rem;
  }
  .upload-zone:hover {
    border-color: #1A3263;
    background: #F3F6FB;
  }
  .upload-zone i {
    font-size: 2rem;
    color: #1A3263;
    margin-bottom: 0.5rem;
  }
  .upload-zone p { color: #6B7280; font-size: 0.9rem; }
  .upload-zone p small { color: #9CA3AF; font-size: 0.75rem; }
  .upload-preview { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
  .upload-preview .file {
    background: #F3F6FB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    color: #1A3263;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .upload-preview .file i { color: #1A3263; }

  .car-preview {
    background: #F9FAFB;
    border: 1.5px solid #E5E7EB;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  .car-preview img {
    width: 72px;
    height: 72px;
    border-radius: 10px;
    object-fit: contain;
    background: #fff;
    flex-shrink: 0;
  }
  .car-preview .info { flex: 1; text-align: right; }
  .car-preview .info .name { font-weight: 700; color: #1A3263; font-size: 0.95rem; }
  .car-preview .info .price { font-size: 0.85rem; color: #6B7280; }
  .car-preview .info .installment { font-size: 0.8rem; color: #d4a017; }

  @media (max-width: 640px) {
    .car-preview { flex-direction: column; text-align: center; }
    .car-preview .info { text-align: center; }
  }

  .offer-banner {
    background: linear-gradient(135deg, #FEF2F2, #FEE2E2);
    border: 1px solid #FECACA;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
  }
  .offer-banner .icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #EF4444;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.1rem;
  }
  .offer-banner .text { flex: 1; text-align: right; }
  .offer-banner .text .title { font-weight: 700; color: #991B1B; font-size: 0.9rem; }
  .offer-banner .text .discount { font-size: 0.85rem; color: #DC2626; font-weight: 700; }

  .calc-form { display: none; }
  .calc-form.active { display: block; animation: fadeIn 0.3s ease; }
</style>
@endpush

@section('content')

{{-- 1. Hero --}}
<section class="all-cars-hero" dir="rtl">
  <div class="all-cars-hero-bg"></div>
  <div class="all-cars-hero-content">
    <h1 class="all-cars-hero-title">احجز <span>سيارتك</span></h1>
    <p class="all-cars-hero-subtitle">اختر سيارتك المفضلة واحجز موعداً للتجربة أو تقديم طلب تمويل</p>
  </div>
</section>

{{-- 2. Booking Form --}}
<section class="calc-section" dir="rtl">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center font-bold text-lg">
        {{ session('success') }}
      </div>
    @endif

    {{-- Tabs --}}
    <div class="calc-tabs" id="booking-tabs">
      <button type="button" class="calc-tab active" data-tab="individuals">
        <i class="fas fa-user"></i>
        أفراد
      </button>
      <button type="button" class="calc-tab" data-tab="companies">
        <i class="fas fa-building"></i>
        شركات
      </button>
      <button type="button" class="calc-tab" data-tab="financing">
        <i class="fas fa-credit-card"></i>
        تمويل
      </button>
    </div>

    {{-- Card --}}
    <div class="calc-card">

      {{-- ===================== INDIVIDUALS ===================== --}}
      <div class="calc-form active" id="form-individuals">
        <form method="POST" action="{{ route('new.booking.store') }}" enctype="multipart/form-data" onsubmit="return validateForm('individuals')">
          @csrf
          <input type="hidden" name="contact_type" value="individuals" />

          <div class="calc-grid">
            <div class="calc-group">
              <label>الاسم الكامل <span class="required">*</span></label>
              <input type="text" name="client_name" required placeholder="أدخل اسمك الكامل" value="{{ old('client_name') }}" />
              @error('client_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="calc-group">
              <label>رقم الجوال <span class="required">*</span></label>
              <input type="tel" name="client_phone" required placeholder="05xxxxxxxx" dir="ltr" value="{{ old('client_phone') }}" />
              @error('client_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>موديل السيارة المطلوب</label>
              <select name="car_id" onchange="updatePreview()">
                <option value="">اختر سيارة...</option>
                @foreach($cars as $car)
                  <option value="{{ $car->id }}"
                    data-thumb="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}"
                    data-name="{{ $car->brand->name ?? '' }} {{ $car->name }}"
                    data-price="{{ number_format($car->cash_price) }}"
                    data-installment="{{ $car->min_installment ? number_format($car->min_installment) : '' }}"
                    {{ $selectedCar && $selectedCar->id == $car->id ? 'selected' : '' }}>
                    {{ $car->brand->name ?? '' }} {{ $car->name }} {{ $car->year ? '(' . $car->year . ')' : '' }}
                  </option>
                @endforeach
              </select>
              @error('car_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="calc-group">
              <label>المدينة <span class="required">*</span></label>
              <select name="city" required>
                <option value="">اختر المدينة...</option>
                <option value="الرياض">الرياض</option>
                <option value="جدة">جدة</option>
                <option value="الدمام">الدمام</option>
                <option value="مكة المكرمة">مكة المكرمة</option>
                <option value="المدينة المنورة">المدينة المنورة</option>
                <option value="الخبر">الخبر</option>
                <option value="تبوك">تبوك</option>
                <option value="القصيم">القصيم</option>
                <option value="أبها">أبها</option>
              </select>
            </div>
          </div>

          {{-- Car Preview --}}
          <div class="car-preview" id="car-preview" style="display:none;">
            <img id="preview-thumb" src="" alt="" />
            <div class="info">
              <p class="name" id="preview-name"></p>
              <p class="price" id="preview-price"></p>
              <p class="installment" id="preview-installment" style="display:none;"></p>
            </div>
          </div>

          {{-- Offer Banner --}}
          @if($selectedOffer)
          <div class="offer-banner">
            <div class="icon"><i class="fas fa-tag"></i></div>
            <div class="text">
              <p class="title">{{ $selectedOffer->title }}</p>
              @if($selectedOffer->discount_percent)
                <p class="discount">خصم {{ $selectedOffer->discount_percent }}%</p>
              @elseif($selectedOffer->discount_value)
                <p class="discount">خصم {{ number_format($selectedOffer->discount_value) }} ريال</p>
              @endif
            </div>
          </div>
          @endif

          <div class="calc-grid">
            <div class="calc-group">
              <label>الراتب <span class="required">*</span></label>
              <div class="calc-ranges" id="ind-salary">
                <button type="button" class="calc-range-btn active" data-value="3,500-4,000 ﷼">3,500-4,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="4,000-5,000 ﷼">4,000-5,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="5,000-7,000 ﷼">5,000-7,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="7,000-10,000 ﷼">7,000-10,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="10,000-15,000 ﷼">10,000-15,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="أكثر من 15,000 ﷼">أكثر من 15,000 ﷼</button>
              </div>
            </div>
            <div class="calc-group">
              <label>الإلتزامات الشهرية <span class="required">*</span></label>
              <div class="calc-ranges" id="ind-obligations">
                <button type="button" class="calc-range-btn active" data-value="أقل من 1,000 ﷼">أقل من 1,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="1,000-1,500 ﷼">1,000-1,500 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="1,500-2,000 ﷼">1,500-2,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="2,000-2,500 ﷼">2,000-2,500 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="أكثر من 2,500 ﷼">أكثر من 2,500 ﷼</button>
              </div>
            </div>
          </div>

          <input type="hidden" name="salary_range" id="ind-salary-val" value="3,500-4,000 ﷼" />
          <input type="hidden" name="obligations_range" id="ind-obligations-val" value="أقل من 1,000 ﷼" />

          <div class="calc-group" style="margin-bottom:1rem;">
            <label>ملاحظات إضافية</label>
            <textarea name="notes" placeholder="أخبرنا عن احتياجاتك أو استفسارك...">{{ old('notes') }}</textarea>
          </div>

          {{-- Upload --}}
          <div class="upload-zone" onclick="document.getElementById('file-input').click()">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>إضغط أو أسحب الملفات هنا<br/><small>صور الهوية، رخصة القيادة، كشف الراتب (JPG, PNG, PDF)</small></p>
          </div>
          <input type="file" name="documents[]" id="file-input" accept=".jpg,.jpeg,.png,.pdf" multiple style="display:none" />
          <div class="upload-preview" id="upload-preview"></div>

          <button type="submit" class="calc-submit">
            <i class="fas fa-paper-plane"></i>
            إرسال الطلب
          </button>
        </form>
      </div>

      {{-- ===================== COMPANIES ===================== --}}
      <div class="calc-form" id="form-companies">
        <form method="POST" action="{{ route('new.booking.store') }}" enctype="multipart/form-data" onsubmit="return validateForm('companies')">
          @csrf
          <input type="hidden" name="contact_type" value="companies" />

          <div class="calc-grid">
            <div class="calc-group">
              <label>اسم المؤسسة <span class="required">*</span></label>
              <input type="text" name="company_name" required placeholder="اسم المؤسسة" value="{{ old('company_name') }}" />
            </div>
            <div class="calc-group">
              <label>اسم المسؤول <span class="required">*</span></label>
              <input type="text" name="client_name" required placeholder="اسم الشخص المسؤول" value="{{ old('client_name') }}" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>رقم الجوال <span class="required">*</span></label>
              <input type="tel" name="client_phone" required placeholder="05xxxxxxxx" dir="ltr" value="{{ old('client_phone') }}" />
            </div>
            <div class="calc-group">
              <label>البريد الإلكتروني</label>
              <input type="email" name="client_email" placeholder="company@example.com" dir="ltr" value="{{ old('client_email') }}" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>موديل السيارة المطلوب</label>
              <select name="car_id" onchange="updatePreview()">
                <option value="">اختر سيارة...</option>
                @foreach($cars as $car)
                  <option value="{{ $car->id }}"
                    data-thumb="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}"
                    data-name="{{ $car->brand->name ?? '' }} {{ $car->name }}"
                    data-price="{{ number_format($car->cash_price) }}"
                    data-installment="{{ $car->min_installment ? number_format($car->min_installment) : '' }}"
                    {{ $selectedCar && $selectedCar->id == $car->id ? 'selected' : '' }}>
                    {{ $car->brand->name ?? '' }} {{ $car->name }} {{ $car->year ? '(' . $car->year . ')' : '' }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="calc-group">
              <label>عدد السيارات المطلوبة <span class="required">*</span></label>
              <input type="number" name="num_cars" min="1" max="50" value="1" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>رقم السجل التجاري</label>
              <input type="text" name="tax_number" placeholder="رقم السجل التجاري" value="{{ old('tax_number') }}" />
            </div>
            <div class="calc-group">
              <label>المدينة <span class="required">*</span></label>
              <select name="city" required>
                <option value="">اختر المدينة...</option>
                <option value="الرياض">الرياض</option>
                <option value="جدة">جدة</option>
                <option value="الدمام">الدمام</option>
                <option value="مكة المكرمة">مكة المكرمة</option>
                <option value="المدينة المنورة">المدينة المنورة</option>
                <option value="الخبر">الخبر</option>
                <option value="تبوك">تبوك</option>
                <option value="القصيم">القصيم</option>
                <option value="أبها">أبها</option>
              </select>
            </div>
          </div>

          <div class="calc-group" style="margin-bottom:1rem;">
            <label>ملاحظات إضافية</label>
            <textarea name="notes" placeholder="أخبرنا عن احتياجاتك أو استفسارك...">{{ old('notes') }}</textarea>
          </div>

          <div class="upload-zone" onclick="document.getElementById('file-input-companies').click()">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>إضغط أو أسحب الملفات هنا<br/><small>صور الهوية، رخصة القيادة، كشف الراتب (JPG, PNG, PDF)</small></p>
          </div>
          <input type="file" name="documents[]" id="file-input-companies" accept=".jpg,.jpeg,.png,.pdf" multiple style="display:none" />
          <div class="upload-preview" id="upload-preview-companies"></div>

          <button type="submit" class="calc-submit">
            <i class="fas fa-paper-plane"></i>
            تقديم الطلب
          </button>
        </form>
      </div>

      {{-- ===================== FINANCING ===================== --}}
      <div class="calc-form" id="form-financing">
        <form method="POST" action="{{ route('new.booking.store') }}" enctype="multipart/form-data" onsubmit="return validateForm('financing')">
          @csrf
          <input type="hidden" name="contact_type" value="financing" />

          <div class="calc-grid">
            <div class="calc-group">
              <label>الاسم الكامل <span class="required">*</span></label>
              <input type="text" name="client_name" required placeholder="أدخل اسمك الكامل" value="{{ old('client_name') }}" />
            </div>
            <div class="calc-group">
              <label>رقم الجوال <span class="required">*</span></label>
              <input type="tel" name="client_phone" required placeholder="05xxxxxxxx" dir="ltr" value="{{ old('client_phone') }}" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>موديل السيارة المطلوب</label>
              <select name="car_id" onchange="updatePreview()">
                <option value="">اختر سيارة...</option>
                @foreach($cars as $car)
                  <option value="{{ $car->id }}"
                    data-thumb="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}"
                    data-name="{{ $car->brand->name ?? '' }} {{ $car->name }}"
                    data-price="{{ number_format($car->cash_price) }}"
                    data-installment="{{ $car->min_installment ? number_format($car->min_installment) : '' }}"
                    {{ $selectedCar && $selectedCar->id == $car->id ? 'selected' : '' }}>
                    {{ $car->brand->name ?? '' }} {{ $car->name }} {{ $car->year ? '(' . $car->year . ')' : '' }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="calc-group">
              <label>البريد الإلكتروني</label>
              <input type="email" name="client_email" placeholder="email@example.com" dir="ltr" value="{{ old('client_email') }}" />
            </div>
          </div>

          {{-- Car Preview --}}
          <div class="car-preview" id="car-preview-fin" style="display:none;">
            <img id="preview-thumb-fin" src="" alt="" />
            <div class="info">
              <p class="name" id="preview-name-fin"></p>
              <p class="price" id="preview-price-fin"></p>
              <p class="installment" id="preview-installment-fin" style="display:none;"></p>
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>الدفعة الأولى <span class="required">*</span></label>
              <div class="calc-ranges" id="fin-down">
                <button type="button" class="calc-range-btn" data-value="5%">5%</button>
                <button type="button" class="calc-range-btn active" data-value="10%">10%</button>
                <button type="button" class="calc-range-btn" data-value="15%">15%</button>
                <button type="button" class="calc-range-btn" data-value="20%">20%</button>
                <button type="button" class="calc-range-btn" data-value="25%+">25%+</button>
              </div>
              <input type="hidden" name="down_payment" id="fin-down-val" value="10" />
            </div>
            <div class="calc-group">
              <label>مدة التمويل <span class="required">*</span></label>
              <div class="calc-ranges" id="fin-period">
                <button type="button" class="calc-range-btn" data-value="12">سنة</button>
                <button type="button" class="calc-range-btn active" data-value="36">3 سنوات</button>
                <button type="button" class="calc-range-btn" data-value="60">5 سنوات</button>
              </div>
              <input type="hidden" name="financing_period" id="fin-period-val" value="36" />
            </div>
          </div>

          <div class="calc-group" style="margin-bottom:1rem;">
            <label>ملاحظات إضافية</label>
            <textarea name="notes" placeholder="أخبرنا عن احتياجاتك أو استفسارك...">{{ old('notes') }}</textarea>
          </div>

          <div class="upload-zone" onclick="document.getElementById('file-input-fin').click()">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>إضغط أو أسحب الملفات هنا<br/><small>صور الهوية، رخصة القيادة، كشف الراتب (JPG, PNG, PDF)</small></p>
          </div>
          <input type="file" name="documents[]" id="file-input-fin" accept=".jpg,.jpeg,.png,.pdf" multiple style="display:none" />
          <div class="upload-preview" id="upload-preview-fin"></div>

          <button type="submit" class="calc-submit">
            <i class="fas fa-paper-plane"></i>
            إرسال الطلب
          </button>
        </form>
      </div>

    </div>{{-- /calc-card --}}
  </div>
</section>

@endsection

@push('scripts')
<script>
  // =============================================
  //  Data Layer
  // =============================================
  var bookingTab = 'individuals';
  var bookingFormStarted = {};

  function pushBEvent(event, data) {
    if (window.dataLayer) {
      window.dataLayer.push(Object.assign({ event: event }, data || {}));
    }
  }

  pushBEvent('page_view', { page_type: 'booking', tab: bookingTab, page_url: window.location.href });
  pushBEvent('view_content', { content_type: 'booking_form', tab: bookingTab });
  pushBEvent('form_view', { form_type: 'booking_individuals' });

  // =============================================
  //  Tab Switching
  // =============================================
  document.querySelectorAll('#booking-tabs .calc-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
      var t = this.dataset.tab;
      if (t === bookingTab) return;

      document.querySelectorAll('#booking-tabs .calc-tab').forEach(function(el) {
        el.classList.toggle('active', el.dataset.tab === t);
      });
      document.querySelectorAll('.calc-form').forEach(function(el) {
        el.classList.toggle('active', el.id === 'form-' + t);
      });

      bookingTab = t;

      var url = new URL(window.location);
      url.searchParams.set('tab', t);
      window.history.replaceState({ tab: t }, '', url);

      pushBEvent('page_view', { page_type: 'booking', tab: t, page_url: url.toString() });
      pushBEvent('form_view', { form_type: 'booking_' + t });
    });
  });

  // =============================================
  //  Range Buttons
  // =============================================
  document.querySelectorAll('.calc-ranges').forEach(function(group) {
    group.querySelectorAll('.calc-range-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        group.querySelectorAll('.calc-range-btn').forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');
      });
    });
  });

  // Sync hidden inputs
  document.querySelectorAll('#ind-salary .calc-range-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('ind-salary-val').value = this.dataset.value;
    });
  });
  document.querySelectorAll('#ind-obligations .calc-range-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('ind-obligations-val').value = this.dataset.value;
    });
  });
  document.querySelectorAll('#fin-down .calc-range-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('fin-down-val').value = this.dataset.value.replace('%', '');
    });
  });
  document.querySelectorAll('#fin-period .calc-range-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('fin-period-val').value = this.dataset.value;
    });
  });

  // =============================================
  //  Form Started
  // =============================================
  document.querySelectorAll('.calc-form input, .calc-form select, .calc-form textarea').forEach(function(el) {
    el.addEventListener('focus', function() {
      var formId = this.closest('.calc-form').id;
      if (!bookingFormStarted[formId]) {
        bookingFormStarted[formId] = true;
        pushBEvent('form_started', { form_type: 'booking_' + formId.replace('form-', '') });
      }
    });
  });

  // =============================================
  //  Car Preview
  // =============================================
  function updatePreview() {
    var sel = document.querySelector('select[name="car_id"]:focus');
    if (!sel) {
      // get selected from any visible form
      document.querySelectorAll('select[name="car_id"]').forEach(function(s) {
        if (s.closest('.calc-form') && s.closest('.calc-form').classList.contains('active')) {
          sel = s;
        }
      });
    }
    if (!sel) return;

    var opt = sel.options[sel.selectedIndex];
    var form = sel.closest('.calc-form');
    var prefix = form.id === 'form-financing' ? '-fin' : '';

    document.getElementById('car-preview' + prefix).style.display = opt && opt.value ? '' : 'none';
    if (!opt || !opt.value) return;

    document.getElementById('preview-thumb' + prefix).src = opt.dataset.thumb || '';
    document.getElementById('preview-name' + prefix).textContent = opt.dataset.name || '';
    document.getElementById('preview-price' + prefix).textContent = opt.dataset.price ? opt.dataset.price + ' ريال' : '';
    if (opt.dataset.installment) {
      document.getElementById('preview-installment' + prefix).textContent = 'من ' + opt.dataset.installment + ' ريال / شهرياً';
      document.getElementById('preview-installment' + prefix).style.display = '';
    } else {
      document.getElementById('preview-installment' + prefix).style.display = 'none';
    }
  }

  document.querySelectorAll('select[name="car_id"]').forEach(function(sel) {
    sel.addEventListener('change', function() {
      var opt = this.options[this.selectedIndex];
      var form = this.closest('.calc-form');
      var prefix = form && form.id === 'form-financing' ? '-fin' : '';

      document.getElementById('car-preview' + prefix).style.display = opt && opt.value ? '' : 'none';
      if (!opt || !opt.value) return;

      document.getElementById('preview-thumb' + prefix).src = opt.dataset.thumb || '';
      document.getElementById('preview-name' + prefix).textContent = opt.dataset.name || '';
      document.getElementById('preview-price' + prefix).textContent = opt.dataset.price ? opt.dataset.price + ' ريال' : '';
      if (opt.dataset.installment) {
        document.getElementById('preview-installment' + prefix).textContent = 'من ' + opt.dataset.installment + ' ريال / شهرياً';
        document.getElementById('preview-installment' + prefix).style.display = '';
      } else {
        document.getElementById('preview-installment' + prefix).style.display = 'none';
      }
    });
  });

  // Show initial car preview if pre-selected
  setTimeout(function() {
    document.querySelectorAll('select[name="car_id"]').forEach(function(sel) {
      if (sel.value) {
        var evt = new Event('change');
        sel.dispatchEvent(evt);
      }
    });
  }, 100);

  // =============================================
  //  File Upload Preview
  // =============================================
  document.getElementById('file-input').addEventListener('change', function() {
    showFiles(this, 'upload-preview');
  });
  document.getElementById('file-input-companies').addEventListener('change', function() {
    showFiles(this, 'upload-preview-companies');
  });
  document.getElementById('file-input-fin').addEventListener('change', function() {
    showFiles(this, 'upload-preview-fin');
  });

  function showFiles(input, previewId) {
    var preview = document.getElementById(previewId);
    preview.innerHTML = '';
    Array.from(input.files).forEach(function(file) {
      var div = document.createElement('div');
      div.className = 'file';
      div.innerHTML = '<i class="fas fa-file"></i> ' + file.name;
      preview.appendChild(div);
    });
  }

  // =============================================
  //  Form Validation
  // =============================================
  function validateForm(tab) {
    var valid = true;
    var form = document.querySelector('#form-' + tab + ' form');

    if (form) {
      form.querySelectorAll('[required]').forEach(function(el) {
        if (!el.value.trim()) {
          valid = false;
          el.style.borderColor = '#e53e3e';
        } else {
          el.style.borderColor = '';
        }
      });
    }

    if (!valid) {
      alert('يرجى ملء جميع الحقول المطلوبة.');
      return false;
    }

    pushBEvent('lead', {
      lead_type: 'booking',
      tab: tab,
      phone: (form ? form.querySelector('[name="client_phone"]')?.value || '' : ''),
      car_model: '',
      city: (form ? form.querySelector('[name="city"]')?.value || '' : ''),
      salary: '',
      car_price: '',
      installment: ''
    });

    return true;
  }

  // Restore tab from URL on load
  (function() {
    var params = new URLSearchParams(window.location.search);
    var tab = params.get('tab');
    if (tab && ['individuals', 'companies', 'financing'].includes(tab)) {
      var btn = document.querySelector('#booking-tabs .calc-tab[data-tab="' + tab + '"]');
      if (btn) btn.click();
    }
  })();
</script>
@endpush
