@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - ' . $title)

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/all-cars-hero/all-cars-hero.css') }}" />
<style>
  .page-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px 60px;
    line-height: 2;
    font-size: 16px;
    color: #333;
  }
  .page-content h2 {
    color: #1A3263;
    font-size: 22px;
    margin-top: 30px;
    margin-bottom: 15px;
  }
  .page-content p {
    margin-bottom: 15px;
  }
</style>
@endpush

@section('content')

@php $breadcrumbBg = $settings['breadcrumb_bg'] ?? null; @endphp
<section class="all-cars-hero" dir="rtl" style="{{ $breadcrumbBg ? 'background-image: url(' . asset('storage/' . $breadcrumbBg) . ');' : '' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
        <div class="text-right">
            <h1 class="text-white text-4xl sm:text-5xl font-extrabold">{{ $title }}</h1>
            <nav class="breadcrumb-nav mt-2">
                <a href="{{ route('new.home') }}" class="text-white/70 hover:text-white transition-colors">الرئيسية</a>
                <span class="text-white/50 mx-2">/</span>
                <span class="text-white">{{ $title }}</span>
            </nav>
        </div>
    </div>
</section>

<section class="bg-white" dir="rtl">
    <div class="page-content">
        {!! nl2br(e($content)) !!}
    </div>
</section>

@endsection
