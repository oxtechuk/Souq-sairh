@extends('new-store.layouts.app')

@php
    $blogTitle = $post->meta_title ?: ($post->title . ' | مدونة سوق سيارة');
    $blogDesc = $post->meta_description ?: (\Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->content), 160));
    $blogImg = $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('new-store/images/hero-video-poster.png');
@endphp

@section('title', $blogTitle)
@section('meta_description', $blogDesc)

@section('meta')
<meta property="og:title" content="{{ $blogTitle }}">
<meta property="og:description" content="{{ $blogDesc }}">
<meta property="og:image" content="{{ $blogImg }}">
<meta property="og:url" content="{{ route('new.blog.show', $post->slug) }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $blogTitle }}">
<meta name="twitter:description" content="{{ $blogDesc }}">
<meta name="twitter:image" content="{{ $blogImg }}">

{{-- BlogPosting Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ addslashes($post->title) }}",
  "image": "{{ $blogImg }}",
  "description": "{{ addslashes($blogDesc) }}",
  "datePublished": "{{ $post->published_at ? $post->published_at->tz('UTC')->toAtomString() : $post->created_at->tz('UTC')->toAtomString() }}",
  "dateModified": "{{ $post->updated_at ? $post->updated_at->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString() }}",
  "author": {
    "@type": "Organization",
    "name": "سوق سيارة"
  },
  "publisher": {
    "@type": "Organization",
    "name": "سوق سيارة",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('new-store/images/logo.png') }}"
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ route('new.blog.show', $post->slug) }}"
  }
}
</script>
@endsection

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
