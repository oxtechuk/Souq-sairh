@php
    $globalSouqSettings = \Illuminate\Support\Facades\Cache::remember('settings.all', 86400, function () {
        return \App\Models\Setting::all()->pluck('value', 'key');
    });
    $souqGtmId = $globalSouqSettings['google_tag_manager_id'] ?? 'GTM-PLPF4RXN';
    $souqPixelId = $globalSouqSettings['meta_pixel_id'] ?? '1391587686296113';
    $souqTiktokId = $globalSouqSettings['tiktok_pixel_id'] ?? 'DAGKF8BC77UC8FLJU9TG';
    $souqSnapId = $globalSouqSettings['snapchat_pixel_id'] ?? 'a29bd8a6-9047-45bc-b184-dd10d94233f7';
    $souqGaId = $globalSouqSettings['google_analytics_id'] ?? '';
@endphp
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    @if($souqGtmId)
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $souqGtmId }}');</script>
    <!-- End Google Tag Manager -->
    @endif

    <!-- Tracking Services Preconnect -->
    <link rel="preconnect" href="https://www.googletagmanager.com" />
    <link rel="preconnect" href="https://connect.facebook.net" />
    <link rel="preconnect" href="https://analytics.tiktok.com" />
    <link rel="preconnect" href="https://sc-static.net" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'سوق سيارة')</title>
    <meta name="description" content="@yield('meta_description', 'سوق سيارة — أفضل منصة لشراء السيارات في السعودية بتمويل مرن وأسعار تنافسية.')">
    <meta property="og:title" content="@yield('title', 'سوق سيارة')">
    <meta property="og:description" content="@yield('meta_description', 'سوق سيارة — أفضل منصة لشراء السيارات في السعودية.')">
    <meta property="og:type" content="website">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    @php
        $siteFavicon = $globalSouqSettings['site_favicon'] ?? null;
        $faviconUrl = $siteFavicon ? asset('storage/' . $siteFavicon) : asset('favicon.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

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

    <style>
        .lazy-section {
            content-visibility: auto;
            contain-intrinsic-size: 1px 700px;
        }
    </style>

    <!-- SEO Meta -->
    @yield('meta')

    @if($souqPixelId)
    <!-- Meta Pixel Code -->
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
    <!-- End Meta Pixel Code -->
    @endif

    @if($souqTiktokId)
    <!-- TikTok Pixel Code Start -->
    <script>
    !function (w, d, t) {
      w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
    var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
    ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

      ttq.load('{{ $souqTiktokId }}');
      ttq.page();
    }(window, document, 'ttq');
    </script>
    <!-- TikTok Pixel Code End -->
    @endif

    @if($souqSnapId)
    <!-- Snap Pixel Code -->
    <script type='text/javascript'>
    (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
    {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
    a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
    r.src=n;var u=t.getElementsByTagName(s)[0];
    u.parentNode.insertBefore(r,u);})(window,document,
    'https://sc-static.net/scevent.min.js');

    snaptr('init', '{{ $souqSnapId }}');
    snaptr('track', 'PAGE_VIEW');
    </script>
    <!-- End Snap Pixel Code -->
    @endif
</head>
<body class="font-tajawal">

    @if($souqGtmId)
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $souqGtmId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
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

    {{-- Floating WhatsApp Icon --}}
    @php
        $waRaw = $globalSouqSettings['contact_whatsapp'] ?? '';
        $waClean = preg_replace('/[^0-9]/', '', $waRaw);
        if (!$waClean) {
            $waRaw = $globalSouqSettings['contact_phone'] ?? '';
            $waClean = preg_replace('/[^0-9]/', '', $waRaw);
        }
    @endphp
    @if($waClean)
    <div class="fixed bottom-6 left-6 z-50">
        <a href="https://wa.me/{{ $waClean }}?text={{ urlencode('مرحباً، أود الاستفسار عن') }}" target="_blank"
           onclick="trackEvent('contact', { channel: 'whatsapp' })"
           class="w-14 h-14 bg-[#25D366] rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200 shadow-[0_4px_20px_rgba(37,211,102,0.4)]">
            <i class="fab fa-whatsapp text-white text-3xl"></i>
        </a>
    </div>
    @endif

    {{-- Global Tracking & Conversion Dispatcher --}}
    <script>
        window.dataLayer = window.dataLayer || [];

        function trackEvent(action, params) {
            params = params || {};

            // 1. Google Analytics (GA4)
            if (typeof gtag === 'function') {
                gtag('event', action, params);
            }

            // 2. Google Tag Manager
            if (window.dataLayer) {
                window.dataLayer.push({ event: action, ...params });
            }

            // 3. Meta (Facebook) Pixel
            if (typeof fbq === 'function') {
                var metaEvent = action;
                if (action === 'lead') metaEvent = 'Lead';
                else if (action === 'contact') metaEvent = 'Contact';
                else if (action === 'view_item') metaEvent = 'ViewContent';
                fbq('track', metaEvent, params);
            }

            // 4. TikTok Pixel
            if (typeof ttq === 'object' && typeof ttq.track === 'function') {
                var ttEvent = action;
                if (action === 'lead') ttEvent = 'SubmitForm';
                else if (action === 'contact') ttEvent = 'Contact';
                else if (action === 'view_item') ttEvent = 'ViewContent';
                ttq.track(ttEvent, params);
            }

            // 5. Snapchat Pixel
            if (typeof snaptr === 'function') {
                var snapEvent = action;
                if (action === 'lead') snapEvent = 'SIGN_UP';
                else if (action === 'contact') snapEvent = 'CUSTOM_EVENT_1';
                else if (action === 'view_item') snapEvent = 'VIEW_CONTENT';
                snaptr('track', snapEvent, params);
            }
        }

        // Global alias for compatibility
        window.trackConversion = trackEvent;
    </script>

    {{-- Page-specific scripts --}}
    @stack('scripts')

</body>
</html>
