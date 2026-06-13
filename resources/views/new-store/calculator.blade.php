@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - حاسبة التمويل')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/calculator/calculator.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/contact-form/contact-form.css') }}" />
<style>
    #calc-step-2 { display: none; }
    .calc-step.done .calc-step-circle { background: #d4a017; border-color: #d4a017; }
    .calc-step.done .calc-step-label { color: #1A3263; }
</style>
@endpush

@section('content')

<section class="calculator-section" dir="rtl">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Stepper --}}
    <div class="calc-stepper">
      <div class="calc-step" id="step-indicator-2">
        <div class="calc-step-circle">02</div>
        <div class="calc-step-label">إحسب تمويلك</div>
      </div>
      <div class="calc-step-line"></div>
      <div class="calc-step active" id="step-indicator-1">
        <div class="calc-step-circle">01</div>
        <div class="calc-step-label">أدخل بياناتك</div>
      </div>
    </div>

    {{-- Step 1: Personal Data --}}
    <div id="calc-step-1">
      <div class="calculator-header">
        <h2>إحسب تمويل سيارتك</h2>
        <p>أملأ البيانات التالية وانتقل لحساب تمويلك</p>
      </div>

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

      <form id="lead-form" onsubmit="event.preventDefault(); goToStep2();">

        <div class="contact-form-grid">
          <div class="contact-form-group">
            <label>الاسم الكامل <span>*</span></label>
            <div class="contact-input-wrapper">
              <i class="fas fa-user"></i>
              <input type="text" id="lead_name" required placeholder="أدخل الاسم الكامل" />
            </div>
          </div>
          <div class="contact-form-group">
            <label>رقم الجوال <span>*</span></label>
            <div class="contact-input-wrapper">
              <i class="fas fa-phone"></i>
              <input type="tel" id="lead_phone" required placeholder="05xxxxxxxx" dir="ltr" />
            </div>
          </div>
        </div>

        <div class="contact-form-grid">
          <div class="contact-form-group">
            <label>موديل السيارة المطلوب</label>
            <div class="contact-input-wrapper">
              <i class="fas fa-car"></i>
              <select id="lead_car_id">
                <option value="" disabled selected>اختر سيارة...</option>
                @foreach($cars as $c)
                  <option value="{{ $c->cash_price }}">{{ $c->name }} {{ $c->model }}</option>
                @endforeach
              </select>
              <i class="fas fa-chevron-down select-arrow"></i>
            </div>
          </div>
          <div class="contact-form-group">
            <label>المدينة</label>
            <div class="contact-input-wrapper">
              <i class="fas fa-map-marker-alt"></i>
              <select id="lead_city">
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

        {{-- Salary + Obligations --}}
        <div class="contact-ranges-row">
          <div class="contact-range-group">
            <label class="block text-right text-sm font-bold mb-2" style="color:#1A3263;">الراتب <span style="color:#e53e3e;">*</span></label>
            <div class="contact-range-grid" id="calc-salary-group">
              <button type="button" class="contact-range-btn active" data-group="calc-salary">أقل من 2,000 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-salary">2,000-2,500 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-salary">2,600-3,000 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-salary">3,000-3,500 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-salary">3,600-4,000 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-salary">أكثر من 4,000 ﷼</button>
            </div>
          </div>
          <div class="contact-range-group">
            <label class="block text-right text-sm font-bold mb-2" style="color:#1A3263;">الإلتزامات الشهرية <span style="color:#e53e3e;">*</span></label>
            <div class="contact-range-grid" id="calc-obligations-group">
              <button type="button" class="contact-range-btn active" data-group="calc-obligations">أقل من 1,000 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-obligations">700-1500 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-obligations">1,000 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-obligations">1,700-2000 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-obligations">1,800-2000 ﷼</button>
              <button type="button" class="contact-range-btn" data-group="calc-obligations">أكثر من 2,000 ﷼</button>
            </div>
          </div>
        </div>

        <input type="hidden" id="calc_salary_range" name="salary_range" />
        <input type="hidden" id="calc_obligations_range" name="obligations_range" />

        {{-- Notes + Next --}}
        <div class="contact-notes-row">
          <div class="contact-notes-group">
            <label>ملاحظات إضافية</label>
            <textarea id="lead_notes" placeholder="أي تفاصيل أو طلبات خاصة..."></textarea>
          </div>
          <div class="flex items-end">
            <button type="submit" class="contact-submit-btn">
              <i class="fas fa-calculator"></i>
              أنتقل الى الحاسبة
            </button>
          </div>
        </div>

      </form>
    </div>

    {{-- Step 2: Calculator --}}
    <div id="calc-step-2">
      <div class="calculator-header">
        <h2>إحسب تمويل سيارتك</h2>
        <p>إختر ما يناسبك لتمويلك</p>
      </div>

      <div class="calc2-grid">

        {{-- Right Column --}}
        <div class="calc2-col">
          <div class="contact-form-group mb-4">
            <label>موديل السيارة المطلوب <span>*</span></label>
            <div class="contact-input-wrapper">
              <i class="fas fa-car"></i>
              <select id="calc2-car" onchange="updateCarPrice()">
                <option value="0" disabled>اختر سيارة...</option>
                @foreach($cars as $c)
                  <option value="{{ $c->cash_price }}">{{ $c->name }} {{ $c->model }}</option>
                @endforeach
              </select>
              <i class="fas fa-chevron-down select-arrow"></i>
            </div>
          </div>

          <div class="calc2-price-box">
            <p class="calc2-price-label">سعر السيارة الأساسي</p>
            <p class="calc2-price-value" id="calc2-car-price">0 ريال</p>
          </div>

          <div class="contact-range-group mt-4">
            <label class="block text-right text-sm font-bold mb-2" style="color:#1A3263;">إختر البنك <span style="color:#e53e3e;">*</span></label>
            <div class="contact-range-grid" id="calc2-bank-group">
              @foreach($banks as $index => $bank)
                <button type="button" class="contact-range-btn {{ $index === 0 ? 'active' : '' }}" data-group="calc2-bank" data-rate="{{ $bank->interest_rate ?? 5 }}">
                  {{ $bank->name }}<br/><small>معدل {{ $bank->interest_rate ?? 5 }}%</small>
                </button>
              @endforeach
            </div>
          </div>

          <div class="calc2-summary-row mt-4">
            <div class="calc2-summary-box">
              <p class="calc2-summary-label">مبلغ التمويل</p>
              <p class="calc2-summary-value" id="calc2-total">0 ريال</p>
            </div>
            <div class="calc2-summary-box">
              <p class="calc2-summary-label">إجمالي المبلغ</p>
              <p class="calc2-summary-value" id="calc2-grand">0 ريال</p>
            </div>
          </div>
        </div>

        {{-- Left Column --}}
        <div class="calc2-col">
          <div class="contact-range-group mb-4">
            <label class="block text-right text-sm font-bold mb-2" style="color:#1A3263;">مدة التمويل <span style="color:#e53e3e;">*</span></label>
            <div class="contact-range-grid" id="calc2-period-group">
              <button type="button" class="contact-range-btn" data-group="calc2-period" data-months="12">سنة واحدة</button>
              <button type="button" class="contact-range-btn" data-group="calc2-period" data-months="24">سنتان</button>
              <button type="button" class="contact-range-btn active" data-group="calc2-period" data-months="36">3 سنوات</button>
              <button type="button" class="contact-range-btn" data-group="calc2-period" data-months="48">4 سنوات</button>
              <button type="button" class="contact-range-btn" data-group="calc2-period" data-months="60">5 سنوات</button>
            </div>
          </div>

          <div class="calc2-result-box" id="calc2-result-box">
            <p class="calc2-result-label">القسط الشهري التقريبي</p>
            <p class="calc2-result-amount" id="calc2-monthly">0 ريال</p>
          </div>
          <p class="calc2-disclaimer">هذه الحسبة تقديرية ومن الممكن أن تختلف لعدة عوامل</p>

          <button type="button" onclick="submitLead()" class="contact-submit-btn" style="background:#22c55e;">
            <i class="fas fa-check-circle"></i>
            تأكيد وإرسال الطلب
          </button>
        </div>

      </div>

      <button class="calc-back-btn" onclick="goToStep1()">
        <i class="fas fa-arrow-right"></i>
        العودة للبيانات
      </button>
    </div>

  </div>
</section>

@endsection

@push('scripts')
<script>
  document.querySelectorAll('.contact-type-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.contact-type-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });

  document.querySelectorAll('.contact-range-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.dataset.group;
      document.querySelectorAll(`.contact-range-btn[data-group="${group}"]`).forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      if (['calc2-period', 'calc2-bank'].includes(group)) runCalc2();
    });
  });

  function goToStep2() {
    const carIdSelect1 = document.getElementById('lead_car_id');
    const carIdSelect2 = document.getElementById('calc2-car');
    if (carIdSelect1.value) carIdSelect2.value = carIdSelect1.value;

    document.getElementById('calc-step-1').style.display = 'none';
    document.getElementById('calc-step-2').style.display = 'block';

    document.getElementById('step-indicator-1').classList.remove('active');
    document.getElementById('step-indicator-1').classList.add('done');
    document.getElementById('step-indicator-2').classList.add('active');

    updateCarPrice();
  }

  function goToStep1() {
    document.getElementById('calc-step-2').style.display = 'none';
    document.getElementById('calc-step-1').style.display = 'block';

    document.getElementById('step-indicator-2').classList.remove('active');
    document.getElementById('step-indicator-1').classList.remove('done');
    document.getElementById('step-indicator-1').classList.add('active');
  }

  function updateCarPrice() {
    const sel = document.getElementById('calc2-car');
    const price = parseInt(sel.value) || 0;
    document.getElementById('calc2-car-price').textContent = price.toLocaleString('ar-SA') + ' ريال';
    runCalc2();
  }

  let monthlyAmount = 0;

  function runCalc2() {
    const price = parseInt(document.getElementById('calc2-car').value) || 0;
    const monthsBtn = document.querySelector('.contact-range-btn.active[data-group="calc2-period"]');
    const bankBtn = document.querySelector('.contact-range-btn.active[data-group="calc2-bank"]');

    const months = monthsBtn ? parseInt(monthsBtn.dataset.months) : 36;
    const rate = bankBtn ? parseFloat(bankBtn.dataset.rate) : 5.0;

    const monthlyRate = rate / 100 / 12;
    monthlyAmount = monthlyRate === 0
      ? price / months
      : price * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);

    const totalPaid = monthlyAmount * months;

    function fmt(n) { return Math.round(n).toLocaleString('ar-SA') + ' ريال'; }

    document.getElementById('calc2-monthly').textContent = fmt(monthlyAmount);
    document.getElementById('calc2-total').textContent = fmt(price);
    document.getElementById('calc2-grand').textContent = fmt(totalPaid);
  }

  function submitLead() {
      const name = document.getElementById('lead_name').value;
      const phone = document.getElementById('lead_phone').value;
      const price = parseInt(document.getElementById('calc2-car').value) || 0;
      const monthsBtn = document.querySelector('.contact-range-btn.active[data-group="calc2-period"]');
      const months = monthsBtn ? parseInt(monthsBtn.dataset.months) : 36;
      const salaryBtn = document.querySelector('.contact-range-btn.active[data-group="calc-salary"]');
      const obligBtn = document.querySelector('.contact-range-btn.active[data-group="calc-obligations"]');

      if (!name || !phone) {
          alert('يرجى العودة والتحقق من إدخال الاسم ورقم الجوال.');
          return;
      }

      fetch('{{ route("new.calculator.lead") }}', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
              name: name,
              phone: phone,
              car_price: price,
              down_payment: 0,
              months: months,
              monthly: Math.round(monthlyAmount),
              salary_range: salaryBtn ? salaryBtn.textContent.trim() : '',
              obligations_range: obligBtn ? obligBtn.textContent.trim() : ''
          })
      }).then(res => res.json()).then(data => {
          if(data.success) {
              alert('تم إرسال طلبك بنجاح. سنتواصل معك قريباً.');
              window.location.reload();
          }
      }).catch(err => {
          alert('حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.');
      });
  }
</script>
@endpush
