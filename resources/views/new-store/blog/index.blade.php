@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - المقالات')

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/articles-featured/articles-featured.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/articles-list/articles-list.css') }}" />
@endpush

@section('content')

{{-- 1. Featured Articles --}}
@if($featured && $featured->isNotEmpty())
<section class="articles-featured-section" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-[32px] font-extrabold mb-3">
        <span class="text-primary">مقالاتنا</span>
        <span class="text-gold"> المميزة</span>
      </h2>
      <p class="text-[15px]" style="color: #1A3263;">اطلع على أفضل المقالات لدينا</p>
    </div>

    <div class="articles-grid">
      @foreach($featured as $post)
        <div class="article-card">
          <div class="article-card-image">
            <img src="{{ $post->thumbnail ? asset('storage/'.$post->thumbnail) : asset('new-store/images/offer-card-1.jpg') }}" alt="{{ $post->title }}" loading="lazy" />
          </div>
          <div class="article-card-content">
            <h3 class="article-card-title">{{ $post->title }}</h3>
            <p class="article-card-excerpt">{{ Str::limit(strip_tags($post->content), 100) }}</p>
            <a href="{{ route('new.blog.show', $post->slug) }}" class="article-card-btn">اقرأ المقالة</a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 2. Latest Articles --}}
<section class="articles-list-section" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-[32px] font-extrabold mb-3">
        <span class="text-primary">احدث</span>
        <span class="text-gold"> المقالات</span>
      </h2>
      <p class="text-[15px]" style="color: #1A3263;">اطلع على أحدث المقالات لدينا</p>
    </div>

    <div class="articles-grid">
      @forelse($posts as $post)
        <div class="article-card">
          <div class="article-card-image">
            <img src="{{ $post->thumbnail ? asset('storage/'.$post->thumbnail) : asset('new-store/images/offer-card-1.jpg') }}" alt="{{ $post->title }}" loading="lazy" />
          </div>
          <div class="article-card-content">
            <h3 class="article-card-title">{{ $post->title }}</h3>
            <p class="article-card-excerpt">{{ Str::limit(strip_tags($post->content), 100) }}</p>
            <a href="{{ route('new.blog.show', $post->slug) }}" class="article-card-btn">اقرأ المقالة</a>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center py-12">
          <i class="fas fa-newspaper text-4xl text-gray-300 mb-4"></i>
          <h3 class="text-xl font-bold text-gray-500">لا توجد مقالات متاحة حالياً</h3>
        </div>
      @endforelse
    </div>

    @if($posts->hasPages())
    <div class="articles-pagination">
      @if ($posts->onFirstPage())
        <span class="articles-pag-btn" style="opacity:0.5;pointer-events:none"><i class="fas fa-arrow-right"></i></span>
      @else
        <a href="{{ $posts->previousPageUrl() }}" class="articles-pag-btn"><i class="fas fa-arrow-right"></i></a>
      @endif

      <div class="articles-dots">
        @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
          <a href="{{ $url }}" class="articles-dot {{ $page == $posts->currentPage() ? 'active' : '' }}"></a>
        @endforeach
      </div>

      @if ($posts->hasMorePages())
        <a href="{{ $posts->nextPageUrl() }}" class="articles-pag-btn"><i class="fas fa-arrow-left"></i></a>
      @else
        <span class="articles-pag-btn" style="opacity:0.5;pointer-events:none"><i class="fas fa-arrow-left"></i></span>
      @endif
    </div>
    @endif

  </div>
</section>

@endsection
