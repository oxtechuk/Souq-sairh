@extends('new-store.layouts.app')

@section('title', __('طلبات كثيرة') . ' — Souq Siarh')

@section('content')

<div class="min-h-[70vh] flex flex-col items-center justify-center px-4 py-20 text-center">
  <div class="text-[clamp(100px,18vw,200px)] font-black leading-none text-primary" style="letter-spacing:-6px">
    <span class="inline-block align-top">4</span>
    <span class="inline-block align-top text-gold">2</span>
    <span class="inline-block align-top">9</span>
  </div>

  <div class="w-14 h-1 bg-gradient-to-l from-primary to-gold rounded-full my-6"></div>

  <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-3">
    {{ __('طلبات كثيرة جداً') }}
  </h1>

  <p class="text-gray-500 max-w-md mx-auto mb-8 text-sm sm:text-base leading-relaxed">
    {{ __('لقد أرسلت عدداً كبيراً من الطلبات. يرجى الانتظار قليلاً ثم المحاولة مرة أخرى.') }}
  </p>

  <a href="{{ route('new.home') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-bold transition-all hover:-translate-y-1 shadow-lg shadow-primary/20">
    <i class="fas fa-home"></i>
    {{ __('الصفحة الرئيسية') }}
  </a>
</div>

@endsection
