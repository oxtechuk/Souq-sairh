@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - ' . $post->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('new-store/components/article-content/article-content.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/articles-list/articles-list.css') }}" />
<link rel="stylesheet" href="{{ asset('new-store/components/articles-related/articles-related.css') }}" />
@endpush

@section('content')

{{-- 1. Article Content --}}
<section class="article-content-section" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="article-content-title" id="article-title">{{ $post->title }}</h1>

    <div class="article-content-body" id="article-content-body">
      <div class="article-content-col">
        {!! $post->content !!}
      </div>
    </div>
  </div>
</section>

{{-- 2. Related Articles --}}
@if($related && $related->isNotEmpty())
<section class="articles-related-section" dir="rtl">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-[32px] font-extrabold mb-3">
        <span class="text-primary">مقالات</span>
        <span class="text-gold"> مشابهة</span>
      </h2>
      <p class="text-[15px]" style="color: #1A3263;">اطلع على المقالات المشابهة</p>
    </div>

    <div class="articles-grid" id="articles-related-grid">
      @foreach($related as $rPost)
        <div class="article-card">
          <div class="article-card-image">
            <img src="{{ $rPost->thumbnail ? asset('storage/'.$rPost->thumbnail) : asset('new-store/images/offer-card-1.jpg') }}" alt="{{ $rPost->title }}" loading="lazy" />
          </div>
          <div class="article-card-content">
            <h3 class="article-card-title">{{ $rPost->title }}</h3>
            <p class="article-card-excerpt">{{ Str::limit(strip_tags($rPost->content), 100) }}</p>
            <a href="{{ route('new.blog.show', $rPost->slug) }}" class="article-card-btn">اقرأ المقالة</a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
