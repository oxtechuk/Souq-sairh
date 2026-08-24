@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - حاسبة التمويل')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/all-cars-hero/all-cars-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/calculator/calculator.css') }}" />
@endpush

@section('content')

{{-- 1. Hero --}}
<section class="all-cars-hero" dir="rtl">
  <div class="all-cars-hero-bg"></div>
  <div class="all-cars-hero-content">
    <h1 class="all-cars-hero-title">حاسبة <span>التمويل</span></h1>
    <p class="all-cars-hero-subtitle">اختر نوع العميل المناسب واملأ البيانات لتحصل على عرض تمويل تقريبي فوري</p>
  </div>
</section>

{{-- 2. Calculator Form --}}
<section class="calc-section" dir="rtl">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Tabs --}}
    <div class="calc-tabs" id="calc-tabs">
      <button type="button" class="calc-tab {{ $tab === 'individuals' ? 'active' : '' }}" data-tab="individuals">
        <i class="fas fa-user"></i>
        أفراد
      </button>
      <button type="button" class="calc-tab {{ $tab === 'companies' ? 'active' : '' }}" data-tab="companies">
        <i class="fas fa-building"></i>
        شركات
      </button>
      <button type="button" class="calc-tab {{ $tab === 'financing' ? 'active' : '' }}" data-tab="financing">
        <i class="fas fa-credit-card"></i>
        تمويل
      </button>
    </div>

    {{-- Card --}}
    <div class="calc-card">

      {{-- ===================== INDIVIDUALS ===================== --}}
      <div class="calc-form {{ $tab === 'individuals' ? 'active' : '' }}" id="form-individuals">
        <form onsubmit="return submitCalculatorForm('individuals')">
          <div class="calc-grid">
            <div class="calc-group">
              <label>الاسم الكامل <span class="required">*</span></label>
              <input type="text" id="ind-name" required placeholder="أدخل اسمك الكامل" />
            </div>
            <div class="calc-group">
              <label>رقم الجوال <span class="required">*</span></label>
              <input type="tel" id="ind-phone" required placeholder="05xxxxxxxx" dir="ltr" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>موديل السيارة المطلوب</label>
              <select id="ind-car_id">
                <option value="">اختر سيارة...</option>
                @foreach($cars as $c)
                  <option value="{{ $c->id }}" data-price="{{ $c->cash_price }}">{{ $c->name }} {{ $c->model }}</option>
                @endforeach
              </select>
            </div>
            <div class="calc-group">
              <label>المدينة</label>
              <select id="ind-city">
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

          <div class="calc-grid">
            <div class="calc-group full">
              <label>ملاحظات إضافية</label>
              <textarea id="ind-notes" placeholder="أي تفاصيل أو طلبات خاصة..."></textarea>
            </div>
          </div>

          <input type="hidden" id="ind-salary-val" value="3,500-4,000 ﷼" />
          <input type="hidden" id="ind-obligations-val" value="أقل من 1,000 ﷼" />

          <button type="submit" class="calc-submit">
            <i class="fas fa-calculator"></i>
            احسب تمويلك
          </button>
        </form>
      </div>

      {{-- ===================== COMPANIES ===================== --}}
      <div class="calc-form {{ $tab === 'companies' ? 'active' : '' }}" id="form-companies">
        <form onsubmit="return submitCalculatorForm('companies')">
          <div class="calc-grid">
            <div class="calc-group">
              <label>اسم الشركة <span class="required">*</span></label>
              <input type="text" id="comp-company" required placeholder="اسم الشركة" />
            </div>
            <div class="calc-group">
              <label>اسم المسؤول <span class="required">*</span></label>
              <input type="text" id="comp-contact" required placeholder="اسم الشخص المسؤول" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>رقم الجوال <span class="required">*</span></label>
              <input type="tel" id="comp-phone" required placeholder="05xxxxxxxx" dir="ltr" />
            </div>
            <div class="calc-group">
              <label>البريد الإلكتروني</label>
              <input type="email" id="comp-email" placeholder="company@example.com" dir="ltr" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>عدد السيارات المطلوبة <span class="required">*</span></label>
              <input type="number" id="comp-num_cars" min="1" max="50" value="1" />
            </div>
            <div class="calc-group">
              <label>المدينة</label>
              <select id="comp-city">
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

          <div class="calc-grid">
            <div class="calc-group full">
              <label>ملاحظات إضافية</label>
              <textarea id="comp-notes" placeholder="أي تفاصيل أو طلبات خاصة..."></textarea>
            </div>
          </div>

          <button type="submit" class="calc-submit">
            <i class="fas fa-paper-plane"></i>
            تقديم الطلب
          </button>
        </form>
      </div>

      {{-- ===================== FINANCING ===================== --}}
      <div class="calc-form {{ $tab === 'financing' ? 'active' : '' }}" id="form-financing">
        <form onsubmit="return submitCalculatorForm('financing')">
          <div class="calc-grid">
            <div class="calc-group">
              <label>الاسم الكامل <span class="required">*</span></label>
              <input type="text" id="fin-name" required placeholder="أدخل اسمك الكامل" />
            </div>
            <div class="calc-group">
              <label>رقم الجوال <span class="required">*</span></label>
              <input type="tel" id="fin-phone" required placeholder="05xxxxxxxx" dir="ltr" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>موديل السيارة المطلوب</label>
              <select id="fin-car_id">
                <option value="">اختر سيارة...</option>
                @foreach($cars as $c)
                  <option value="{{ $c->id }}" data-price="{{ $c->cash_price }}">{{ $c->name }} {{ $c->model }}</option>
                @endforeach
              </select>
            </div>
            <div class="calc-group">
              <label>البريد الإلكتروني</label>
              <input type="email" id="fin-email" placeholder="email@example.com" dir="ltr" />
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>الدفعة الأولى المتوقعة <span class="required">*</span></label>
              <div class="calc-ranges" id="fin-down">
                <button type="button" class="calc-range-btn" data-value="0%">0%</button>
                <button type="button" class="calc-range-btn" data-value="5%">5%</button>
                <button type="button" class="calc-range-btn active" data-value="10%">10%</button>
                <button type="button" class="calc-range-btn" data-value="15%">15%</button>
                <button type="button" class="calc-range-btn" data-value="20%">20%</button>
                <button type="button" class="calc-range-btn" data-value="25%+">25%+</button>
              </div>
            </div>
            <div class="calc-group">
              <label>هل لديك سيارة للاستبدال؟</label>
              <div class="calc-toggle" id="fin-trade">
                <button type="button" class="calc-toggle-btn active" data-value="no">لا</button>
                <button type="button" class="calc-toggle-btn" data-value="yes">نعم</button>
              </div>
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group">
              <label>قيمة التمويل المطلوبة <span class="required">*</span></label>
              <div class="calc-ranges" id="fin-amount">
                <button type="button" class="calc-range-btn active" data-value="أقل من 50,000 ﷼">أقل من 50,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="50,000-100,000 ﷼">50,000-100,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="100,000-150,000 ﷼">100,000-150,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="150,000-200,000 ﷼">150,000-200,000 ﷼</button>
                <button type="button" class="calc-range-btn" data-value="أكثر من 200,000 ﷼">أكثر من 200,000 ﷼</button>
              </div>
            </div>
          </div>

          <div class="calc-grid">
            <div class="calc-group full">
              <label>ملاحظات إضافية</label>
              <textarea id="fin-notes" placeholder="أي تفاصيل أو طلبات خاصة..."></textarea>
            </div>
          </div>

          <input type="hidden" id="fin-down-val" value="10%" />
          <input type="hidden" id="fin-trade-val" value="no" />
          <input type="hidden" id="fin-amount-val" value="أقل من 50,000 ﷼" />

          <button type="submit" class="calc-submit">
            <i class="fas fa-calculator"></i>
            احسب تمويلك
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
  var currentTab = '{{ $tab }}';
  var formStarted = {};

  function pushEvent(event, data) {
    if (window.dataLayer) {
      window.dataLayer.push(Object.assign({ event: event }, data || {}));
    }
  }

  // Page View
  pushEvent('page_view', {
    page_type: 'calculator',
    tab: currentTab,
    page_url: window.location.href
  });

  // View Content
  pushEvent('view_content', {
    content_type: 'calculator_form',
    tab: currentTab
  });

  // Form View
  pushEvent('form_view', {
    form_type: 'calculator_' + currentTab
  });

  // =============================================
  //  Tab Switching
  // =============================================
  document.querySelectorAll('.calc-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
      var t = this.dataset.tab;
      switchTab(t);
    });
  });

  function switchTab(tab) {
    if (tab === currentTab) return;

    document.querySelectorAll('.calc-tab').forEach(function(el) {
      el.classList.toggle('active', el.dataset.tab === tab);
    });

    document.querySelectorAll('.calc-form').forEach(function(el) {
      el.classList.toggle('active', el.id === 'form-' + tab);
    });

    currentTab = tab;

    var url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.replaceState({ tab: tab }, '', url);

    pushEvent('page_view', {
      page_type: 'calculator',
      tab: tab,
      page_url: url.toString()
    });

    pushEvent('form_view', {
      form_type: 'calculator_' + tab
    });
  }

  // =============================================
  //  Range Buttons (single-select within group)
  // =============================================
  document.querySelectorAll('.calc-ranges').forEach(function(group) {
    group.querySelectorAll('.calc-range-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        group.querySelectorAll('.calc-range-btn').forEach(function(b) {
          b.classList.remove('active');
        });
        this.classList.add('active');
      });
    });
  });

  // Toggle buttons
  document.querySelectorAll('.calc-toggle').forEach(function(group) {
    group.querySelectorAll('.calc-toggle-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        group.querySelectorAll('.calc-toggle-btn').forEach(function(b) {
          b.classList.remove('active');
        });
        this.classList.add('active');
      });
    });
  });

  // Update hidden inputs on range click
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
      document.getElementById('fin-down-val').value = this.dataset.value;
    });
  });
  document.querySelectorAll('#fin-trade .calc-toggle-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('fin-trade-val').value = this.dataset.value;
    });
  });
  document.querySelectorAll('#fin-amount .calc-range-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('fin-amount-val').value = this.dataset.value;
    });
  });

  // =============================================
  //  Form Started (first focus on any input)
  // =============================================
  document.querySelectorAll('.calc-form input, .calc-form select, .calc-form textarea').forEach(function(el) {
    el.addEventListener('focus', function() {
      var formId = this.closest('.calc-form').id;
      if (!formStarted[formId]) {
        formStarted[formId] = true;
        pushEvent('form_started', {
          form_type: 'calculator_' + formId.replace('form-', '')
        });
      }
    });
  });

  // =============================================
  //  Form Submission
  // =============================================
  function submitCalculatorForm(tab) {
    var data = { tab: tab };
    var btn = document.querySelector('#form-' + tab + ' .calc-submit');

    if (tab === 'individuals') {
      var name = document.getElementById('ind-name').value;
      var phone = document.getElementById('ind-phone').value;
      var carId = document.getElementById('ind-car_id').value;
      var city = document.getElementById('ind-city').value;
      var salary = document.getElementById('ind-salary-val').value;
      var obligations = document.getElementById('ind-obligations-val').value;
      var notes = document.getElementById('ind-notes').value;

      if (!name || !phone) {
        alert('يرجى إدخال الاسم ورقم الجوال.');
        return false;
      }

      Object.assign(data, {
        name: name, phone: phone, car_id: carId,
        city: city, salary_range: salary,
        obligations_range: obligations, notes: notes
      });
    }
    else if (tab === 'companies') {
      var company = document.getElementById('comp-company').value;
      var contact = document.getElementById('comp-contact').value;
      var phone = document.getElementById('comp-phone').value;
      var email = document.getElementById('comp-email').value;
      var numCars = document.getElementById('comp-num_cars').value;
      var city = document.getElementById('comp-city').value;
      var notes = document.getElementById('comp-notes').value;

      if (!company || !contact || !phone) {
        alert('يرجى إدخال اسم الشركة واسم المسؤول ورقم الجوال.');
        return false;
      }

      Object.assign(data, {
        company_name: company, contact_name: contact,
        phone: phone, email: email,
        num_cars: numCars, city: city, notes: notes
      });
    }
    else if (tab === 'financing') {
      var name = document.getElementById('fin-name').value;
      var phone = document.getElementById('fin-phone').value;
      var carId = document.getElementById('fin-car_id').value;
      var email = document.getElementById('fin-email').value;
      var downPayment = document.getElementById('fin-down-val').value;
      var tradeIn = document.getElementById('fin-trade-val').value;
      var finAmount = document.getElementById('fin-amount-val').value;
      var notes = document.getElementById('fin-notes').value;

      if (!name || !phone) {
        alert('يرجى إدخال الاسم ورقم الجوال.');
        return false;
      }

      Object.assign(data, {
        name: name, phone: phone, car_id: carId,
        email: email, down_payment: downPayment,
        trade_in: tradeIn, financing_amount: finAmount,
        notes: notes
      });
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';

    fetch('{{ route("new.calculator.lead") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(data)
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      if (response.success) {
        pushEvent('lead', {
          lead_type: 'calculator',
          tab: tab,
          phone: data.phone || '',
          car_model: data.car_id || '',
          city: data.city || '',
          salary: data.salary_range || data.financing_amount || '',
          car_price: '',
          installment: ''
        });
        window.location.href = '{{ route("new.calculator.result") }}';
      }
    })
    .catch(function() {
      alert('حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.');
    })
    .finally(function() {
      btn.disabled = false;
      btn.innerHTML = tab === 'companies'
        ? '<i class="fas fa-paper-plane"></i> تقديم الطلب'
        : '<i class="fas fa-calculator"></i> احسب تمويلك';
    });

    return false;
  }
</script>
@endpush
