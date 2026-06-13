@php
    $globalSouqSettings = \App\Models\Setting::all()->pluck('value', 'key');
    $souqGaId = $globalSouqSettings['google_analytics_id'] ?? '';
    $souqPixelId = $globalSouqSettings['meta_pixel_id'] ?? '';
@endphp
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'سوق سيارة')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" />
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'" />

    @if($souqGaId)
    {{-- Google Analytics (GA4) --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $souqGaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $souqGaId }}');
    </script>
    @endif

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#1A3263",
                        "primary-dark": "#0f1f3d",
                        "primary-light": "#2a4a8f",
                        gold: "#d4a017",
                    },
                    fontFamily: {
                        tajawal: ["Tajawal", "sans-serif"],
                        cairo: ["Cairo", "sans-serif"],
                    },
                },
            },
        };
    </script>

    <!-- Global CSS -->
    <link rel="stylesheet" href="{{ asset('new-store/css/global.css') }}" />

    <!-- Component Styles -->
    <link rel="stylesheet" href="{{ asset('new-store/components/top-bar/top-bar.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/header/header.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/footer/footer.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/car-carousel/car-carousel.css') }}" />

    <!-- Page-specific styles -->
    @stack('styles')

    <!-- SEO Meta -->
    @yield('meta')
</head>
<body class="font-tajawal">

    @if($souqPixelId)
    {{-- Meta Pixel (Facebook) --}}
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $souqPixelId }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $souqPixelId }}&ev=PageView&noscript=1"
    /></noscript>
    @endif

    {{-- Top Bar --}}
    @include('new-store.partials.top-bar')

    {{-- Header --}}
    @include('new-store.partials.header')

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('new-store.partials.footer')

    {{-- CarCarousel JS (shared across pages) --}}
    <script src="{{ asset('new-store/scripts/carCarousel.js') }}" defer></script>

    {{-- Global Tracking Helpers --}}
    <script>
        @if($souqGaId)
        function trackGA(action, params) {
            if (typeof gtag === 'function') {
                gtag('event', action, params || {});
            }
        }
        @endif
        @if($souqPixelId)
        function trackPixel(action, params) {
            if (typeof fbq === 'function') {
                fbq('track', action, params || {});
            }
        }
        @endif
        function trackEvent(action, params) {
            @if($souqGaId)trackGA(action, params);@endif
            @if($souqPixelId)trackPixel(action, params);@endif
        }
    </script>

    {{-- Page-specific scripts --}}
    @stack('scripts')

</body>
</html>
