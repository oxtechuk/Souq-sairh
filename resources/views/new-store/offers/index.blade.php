@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - العروض')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/offers-hero/offers-hero.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/offers-cards/offers-cards.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/compare-cta/compare-cta.css') }}" />
@endpush

@section('content')

{{-- 1. Hero Section --}}
<section class="offers-hero" dir="rtl">
  <div class="offers-hero-content">
    <h1 class="offers-hero-title">
      عروض استثنائية.. <span class="offers-hero-highlight">ضُممت لك</span>
    </h1>
    <p class="offers-hero-subtitle">
      اكتشف أقوى عروض التمويل والتوفير الحصرية<br />
      من "سوق سيارة"، وابدأ رحلتك اليوم.
    </p>
  </div>
</section>

{{-- 2. Offers Cards --}}
<section class="offers-cards-section" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="offers-cards-grid">

      @forelse($offers as $offer)
        <div class="offer-card">
          <div class="offer-card-image">
            <img src="{{ $offer->image ? asset('storage/'.$offer->image) : asset('new-store/images/offer-card-1.jpg') }}" alt="{{ $offer->title }}" loading="lazy" />
          </div>
          <div class="offer-card-content">
            <h3 class="offer-card-title">{{ $offer->title }}</h3>
            <p class="offer-card-subtitle">{{ $offer->description }}</p>



            <a href="{{ route('new.booking', ['offer_id' => $offer->id]) }}" class="offer-card-btn">
              احصل على العرض
            </a>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center py-12">
          <i class="fas fa-gift text-4xl text-gray-300 mb-4"></i>
          <h3 class="text-xl font-bold text-gray-500">لا توجد عروض متاحة حالياً</h3>
        </div>
      @endforelse

    </div>

  </div>
</section>

{{-- 3. Compare CTA - موترك بالتظارك، وحنا بالخدمة --}}
<section class="compare-cta-section py-12" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="compare-cta-banner">
      <div class="compare-cta-content">
        <h2 class="compare-cta-title">موترك بالتظارك، وحنا بالخدمة.</h2>
        <p class="compare-cta-subtitle">
          فريقنا جاهز للإجابة على استفساراتك وتسهيل إجراءات تملك سيارتك القادمة
        </p>

        <div class="compare-cta-buttons">
          <a href="{{ route('new.booking') }}" class="compare-cta-btn compare-cta-btn-primary">
            طلب تجربة قيادة
          </a>
          <a href="{{ route('new.contact') }}" class="compare-cta-btn compare-cta-btn-secondary">
            تواصل معنا لمساعدتك
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
