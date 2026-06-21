@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - نتيجة حاسبة التمويل')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/calculator/calculator.css') }}" />
@endpush

@section('content')

{{-- Hero --}}
<section class="all-cars-hero" dir="rtl">
  <div class="all-cars-hero-bg"></div>
  <div class="all-cars-hero-content">
    <h1 class="all-cars-hero-title">نتيجة <span>التمويل</span></h1>
    <p class="all-cars-hero-subtitle">بناءً على بياناتك، إليك عرض التمويل التقريبي</p>
  </div>
</section>

{{-- Calculator Result --}}
<section class="calc-result-section" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="calc-result-card">

      {{-- Header --}}
      <div class="calc-result-header">
        <h2>حاسبة التمويل</h2>
        <p>مرحباً <strong>{{ $data['name'] }}</strong>، اختر السيارة والبنك ومدة التمويل لعرض القسط الشهري</p>
      </div>

      {{-- Summary --}}
      <div class="calc-result-summary">
        <div class="calc-result-item">
          <div class="label">نوع العميل</div>
          <div class="value">
            @if($data['tab'] === 'individuals') أفراد
            @elseif($data['tab'] === 'companies') شركات
            @else تمويل
            @endif
          </div>
        </div>
        <div class="calc-result-item">
          <div class="label">رقم الجوال</div>
          <div class="value" dir="ltr">{{ $data['phone'] }}</div>
        </div>
        @if($data['city'])
        <div class="calc-result-item">
          <div class="label">المدينة</div>
          <div class="value">{{ $data['city'] }}</div>
        </div>
        @endif
        @if($data['salary_range'])
        <div class="calc-result-item">
          <div class="label">الراتب</div>
          <div class="value">{{ $data['salary_range'] }}</div>
        </div>
        @endif
        @if($data['car_name'])
        <div class="calc-result-item">
          <div class="label">السيارة</div>
          <div class="value">{{ $data['car_name'] }}</div>
        </div>
        @endif
      </div>

      {{-- Calculator Grid --}}
      <div class="calc-result-grid">

        {{-- Right Column --}}
        <div class="calc-result-col">

          <div class="calc-group">
            <label>موديل السيارة المطلوب <span class="required">*</span></label>
            <select id="result-car" onchange="updateResultCar()">
              <option value="0" data-name="">اختر سيارة...</option>
              @foreach($cars as $c)
                <option value="{{ $c->cash_price }}" data-name="{{ $c->name }} {{ $c->model }}"
                  {{ $data['car_id'] == $c->id ? 'selected' : '' }}>
                  {{ $c->name }} {{ $c->model }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="calc-result-price-box">
            <p class="calc-result-price-label">سعر السيارة الأساسي</p>
            <p class="calc-result-price-value" id="result-car-price">
              {{ $data['car_price'] ? number_format($data['car_price']) . ' ريال' : '0 ريال' }}
            </p>
          </div>

          <div class="calc-group">
            <label>إختر البنك <span class="required">*</span></label>
            <div class="calc-ranges" id="result-bank-group">
              @foreach($banks as $index => $bank)
                <button type="button" class="calc-range-btn {{ $index === 0 ? 'active' : '' }}"
                  data-rate="{{ $bank->annual_rate ?? 5 }}">
                  {{ $bank->name }}<br/><small>معدل {{ $bank->annual_rate ?? 5 }}%</small>
                </button>
              @endforeach
            </div>
          </div>

          <div class="calc-result-summary-row">
            <div class="calc-result-summary-box">
              <p class="calc-result-summary-label">مبلغ التمويل</p>
              <p class="calc-result-summary-value" id="result-total">0 ريال</p>
            </div>
            <div class="calc-result-summary-box">
              <p class="calc-result-summary-label">إجمالي المبلغ</p>
              <p class="calc-result-summary-value" id="result-grand">0 ريال</p>
            </div>
          </div>
        </div>

        {{-- Left Column --}}
        <div class="calc-result-col">
          <div class="calc-group">
            <label>مدة التمويل <span class="required">*</span></label>
            <div class="calc-ranges" id="result-period-group">
              <button type="button" class="calc-range-btn" data-months="12">سنة واحدة</button>
              <button type="button" class="calc-range-btn" data-months="24">سنتان</button>
              <button type="button" class="calc-range-btn active" data-months="36">3 سنوات</button>
              <button type="button" class="calc-range-btn" data-months="48">4 سنوات</button>
              <button type="button" class="calc-range-btn" data-months="60">5 سنوات</button>
            </div>
          </div>

          <div class="calc-result-monthly">
            <p class="calc-result-monthly-label">القسط الشهري التقريبي</p>
            <p class="calc-result-monthly-amount" id="result-monthly">0 ريال</p>
          </div>
          <p class="calc-result-disclaimer">هذه الحسبة تقديرية ومن الممكن أن تختلف لعدة عوامل</p>

          <div class="calc-result-actions">
            <button type="button" onclick="submitResult()" class="calc-result-btn-primary">
              <i class="fas fa-check-circle"></i>
              تأكيد وإرسال الطلب
            </button>
            <a href="{{ route('new.calculator') }}" class="calc-result-btn-secondary">
              <i class="fas fa-arrow-right"></i>
              تعديل البيانات
            </a>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
  // =============================================
  //  Data Layer — Page View
  // =============================================
  if (window.dataLayer) {
    window.dataLayer.push({
      event: 'page_view',
      page_type: 'calculator_result',
      page_url: window.location.href
    });
    window.dataLayer.push({
      event: 'view_content',
      content_type: 'calculator_result',
      tab: '{{ $data['tab'] }}'
    });
  }

  // =============================================
  //  Range Buttons
  // =============================================
  document.querySelectorAll('.calc-ranges').forEach(function(group) {
    group.querySelectorAll('.calc-range-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        group.querySelectorAll('.calc-range-btn').forEach(function(b) {
          b.classList.remove('active');
        });
        this.classList.add('active');
        if (['result-period-group', 'result-bank-group'].includes(group.id)) {
          runResultCalc();
        }
      });
    });
  });

  // =============================================
  //  Car Select
  // =============================================
  function updateResultCar() {
    var sel = document.getElementById('result-car');
    var price = parseInt(sel.value) || 0;
    document.getElementById('result-car-price').textContent = price.toLocaleString('ar-SA') + ' ريال';
    runResultCalc();
  }

  // =============================================
  //  Calculator Logic
  // =============================================
  var resultMonthly = 0;

  function runResultCalc() {
    var price = parseInt(document.getElementById('result-car').value) || 0;
    var monthsBtn = document.querySelector('#result-period-group .calc-range-btn.active');
    var bankBtn = document.querySelector('#result-bank-group .calc-range-btn.active');

    var months = monthsBtn ? parseInt(monthsBtn.dataset.months) : 36;
    var rate = bankBtn ? parseFloat(bankBtn.dataset.rate) : 5.0;

    var monthlyRate = rate / 100 / 12;
    resultMonthly = monthlyRate === 0
      ? price / months
      : price * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);

    var totalPaid = resultMonthly * months;

    function fmt(n) { return Math.round(n).toLocaleString('ar-SA') + ' ريال'; }

    document.getElementById('result-monthly').textContent = fmt(resultMonthly);
    document.getElementById('result-total').textContent = fmt(price);
    document.getElementById('result-grand').textContent = fmt(totalPaid);
  }

  // Run on load
  updateResultCar();

  // =============================================
  //  Final Submission
  // =============================================
  function submitResult() {
    var price = parseInt(document.getElementById('result-car').value) || 0;
    var monthsBtn = document.querySelector('#result-period-group .calc-range-btn.active');
    var months = monthsBtn ? parseInt(monthsBtn.dataset.months) : 36;
    var carOption = document.querySelector('#result-car option:checked');
    var carName = carOption ? carOption.dataset.name : '';

    if (!price) {
      alert('يرجى اختيار سيارة.');
      return;
    }

    if (window.dataLayer) {
      window.dataLayer.push({
        event: 'lead',
        lead_type: 'calculator_final',
        tab: '{{ $data['tab'] }}',
        phone: '{{ $data['phone'] }}',
        car_model: carName,
        city: '{{ $data['city'] }}',
        salary: '{{ $data['salary_range'] }}',
        car_price: price,
        installment: Math.round(resultMonthly)
      });
    }

    alert('تم إرسال طلبك بنجاح. سنتواصل معك قريباً.');
    window.location.href = '{{ route("new.calculator") }}';
  }
</script>
@endpush
