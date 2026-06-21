@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - تواصل معنا')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/all-cars-hero/all-cars-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/contact-form/contact-form.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/contact-location/contact-location.css') }}" />
@endpush

@section('content')

{{-- 1. Hero Section --}}
@php
    $breadcrumbBg = $settings['breadcrumb_bg'] ?? null;
    $cleanPhone = preg_replace('/[^0-9]/', '', $settings['contact_phone'] ?? '');
    $cleanWa = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? $settings['contact_phone'] ?? '');
@endphp
<section class="all-cars-hero" dir="rtl" style="{{ $breadcrumbBg ? 'background-image: url(' . asset('storage/' . $breadcrumbBg) . ');' : '' }}">
  <div class="all-cars-hero-bg"></div>
  <div class="all-cars-hero-content">
    <h1 class="all-cars-hero-title">تواصل <span>معنا</span></h1>
    <p class="all-cars-hero-subtitle">نحن هنا للإجابة على جميع استفساراتك ومساعدتك في العثور على السيارة المناسبة وأفضل عروض التمويل.</p>
  </div>
</section>

{{-- 2. Contact Form Section --}}
<section class="contact-form-section py-16 bg-gray-50" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
      
      {{-- Contact Info (Left) --}}
      <div class="bg-primary text-white p-10 sm:p-12 flex flex-col justify-between">
        <div>
          <h2 class="text-3xl font-bold mb-8 text-gold">معلومات التواصل</h2>
          
          <div class="space-y-6">
            <a href="https://maps.google.com/?q={{ urlencode($settings['contact_address'] ?? 'الرياض، المملكة العربية السعودية') }}"
               target="_blank"
               class="flex items-start gap-4 group">
              <div class="w-12 h-12 bg-white/10 group-hover:bg-red-600 rounded-full flex items-center justify-center text-xl shrink-0 transition-colors">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-1">العنوان</h3>
                <p class="text-gray-300 leading-relaxed">{{ $settings['contact_address'] ?? 'الرياض، المملكة العربية السعودية' }}</p>
              </div>
            </a>

            <a href="tel:{{ $cleanPhone }}"
               class="flex items-start gap-4 group">
              <div class="w-12 h-12 bg-white/10 group-hover:bg-green-600 rounded-full flex items-center justify-center text-xl shrink-0 transition-colors">
                <i class="fas fa-phone-alt"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-1">رقم الهاتف</h3>
                <p class="text-gray-300" dir="ltr">{{ $settings['contact_phone'] ?? '056 9567 947' }}</p>
              </div>
            </a>

            <a href="https://wa.me/{{ $cleanWa }}"
               target="_blank"
               class="flex items-start gap-4 group">
              <div class="w-12 h-12 bg-white/10 group-hover:bg-[#25D366] rounded-full flex items-center justify-center text-xl shrink-0 transition-colors">
                <i class="fab fa-whatsapp"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-1">واتساب</h3>
                <p class="text-gray-300" dir="ltr">{{ $settings['contact_whatsapp'] ?? $settings['contact_phone'] ?? '056 9567 947' }}</p>
              </div>
            </a>

            <a href="mailto:{{ $settings['contact_email'] ?? 'info@souqsayara.com' }}"
               class="flex items-start gap-4 group">
              <div class="w-12 h-12 bg-white/10 group-hover:bg-blue-600 rounded-full flex items-center justify-center text-xl shrink-0 transition-colors">
                <i class="fas fa-envelope"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-1">البريد الإلكتروني</h3>
                <p class="text-gray-300">{{ $settings['contact_email'] ?? 'info@souqsayara.com' }}</p>
              </div>
            </a>
          </div>
        </div>

        <div class="mt-12">
          <h3 class="text-lg font-bold mb-4">تابعنا على شبكات التواصل</h3>
          <div class="flex gap-4">
            <a href="{{ $settings['social_twitter'] ?? '#' }}" class="w-10 h-10 bg-white/10 hover:bg-gold transition-colors rounded-full flex items-center justify-center"><i class="fab fa-twitter"></i></a>
            <a href="{{ $settings['social_facebook'] ?? '#' }}" class="w-10 h-10 bg-white/10 hover:bg-gold transition-colors rounded-full flex items-center justify-center"><i class="fab fa-facebook-f"></i></a>
            <a href="{{ $settings['social_instagram'] ?? '#' }}" class="w-10 h-10 bg-white/10 hover:bg-gold transition-colors rounded-full flex items-center justify-center"><i class="fab fa-instagram"></i></a>
            <a href="{{ $settings['social_snapchat'] ?? '#' }}" class="w-10 h-10 bg-white/10 hover:bg-gold transition-colors rounded-full flex items-center justify-center"><i class="fab fa-snapchat-ghost"></i></a>
          </div>
        </div>
      </div>

      {{-- Contact Form (Right) --}}
      <div class="p-10 sm:p-12">
        <h2 class="text-3xl font-bold text-primary mb-8">أرسل لنا رسالة</h2>
        
        <form id="contact-form" action="{{ route('new.contact.store') }}" method="POST" class="space-y-6">
          @csrf
          
          <div>
            <label class="block text-sm font-bold text-primary mb-2">الاسم الكامل <span class="text-red-500">*</span></label>
            <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-gray-50 hover:bg-white" placeholder="أدخل اسمك الكامل">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-bold text-primary mb-2">رقم الجوال <span class="text-red-500">*</span></label>
              <input type="tel" name="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-gray-50 hover:bg-white" placeholder="05xxxxxxxx" dir="ltr" style="text-align: right;">
            </div>
            <div>
              <label class="block text-sm font-bold text-primary mb-2">البريد الإلكتروني</label>
              <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-gray-50 hover:bg-white" placeholder="example@email.com" dir="ltr" style="text-align: right;">
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-primary mb-2">رسالتك <span class="text-red-500">*</span></label>
            <textarea name="message" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-gray-50 hover:bg-white" placeholder="اكتب تفاصيل استفسارك هنا..."></textarea>
          </div>

          <button type="submit" id="contact-submit-btn" class="w-full bg-gold hover:bg-yellow-600 text-white font-bold text-lg py-4 rounded-lg transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            إرسال الرسالة
          </button>
        </form>
      </div>

    </div>
  </div>
</section>

{{-- 3. Map Section --}}
<section class="contact-location-section" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="map-container" style="height: 400px; border-radius: 20px; overflow: hidden;">
      {!! $settings['map_iframe'] ?? '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115998.40698188185!2d46.738586!3d24.774265!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2efbdef7f20815%3A0x68afb1ea5a4f3b11!2sRiyadh%20Saudi%20Arabia!5e0!3m2!1sen!2sus!4v1715421523451!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>' !!}
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
  function showToast(message) {
    var toast = document.createElement('div');
    toast.id = 'success-toast';
    toast.innerHTML =
      '<div style="position:fixed;top:100px;left:50%;transform:translateX(-50%);z-index:9999;background:#1A3263;color:white;padding:16px 32px;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.18);display:flex;align-items:center;gap:12px;font-size:16px;font-weight:700;direction:rtl;max-width:90vw;" dir="rtl">' +
      '<i class="fas fa-check-circle" style="color:#d4a017;font-size:22px;"></i>' +
      '<span>' + message + '</span>' +
      '<button onclick="this.parentElement.remove()" style="background:rgba(255,255,255,0.15);border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;margin-right:8px;flex-shrink:0;">✕</button>' +
      '</div>';

    document.body.appendChild(toast);

    setTimeout(function() {
      var el = document.getElementById('success-toast');
      if (el) {
        el.style.transition = 'opacity 0.4s';
        el.style.opacity = '0';
        setTimeout(function() { el.remove(); }, 400);
      }
    }, 5000);
  }

  document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();

    var btn = document.getElementById('contact-submit-btn');
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';

    var form = this;
    var formData = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.success) {
        form.reset();
        showToast(data.message);
      } else {
        showToast(data.message || 'حدث خطأ أثناء الإرسال. حاول مرة أخرى.');
      }
    })
    .catch(function() {
      showToast('حدث خطأ أثناء الإرسال. حاول مرة أخرى.');
    })
    .finally(function() {
      btn.disabled = false;
      btn.innerHTML = origText;
    });
  });
</script>
@endpush
