@extends('new-store.layouts.app')

@section('title', 'سوق سيارة - الرئيسية')

@push('styles')
    <link rel="stylesheet" href="{{ asset('new-store/components/hero/hero.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/search-filter/search-filter.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/featured-cars/featured-cars.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/car-list/car-list.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/offers-grid/offers-grid.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/brands-carousel/brands-carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/testimonials/testimonials.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/components/car-carousel/car-carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('new-store/css/home.css') }}" />
@endpush

@section('content')

    {{-- ==========================================
         1. HERO SECTION
    =========================================== --}}
    <section class="bg-white py-10 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-10 lg:gap-12">

                {{-- 1. Video & Hero Title Content --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">

                    {{-- Left Side - Video (First on mobile and desktop) --}}
                    <div class="relative flex justify-center lg:justify-start" dir="ltr">
                        <div id="hero-video-container" class="relative w-full max-w-[453px]" style="aspect-ratio: 453/614">
                            <div class="video-mask-wrapper w-full h-full">
                                @if($heroVideo)
                                    <video id="hero-video" class="w-full h-full object-cover" poster="{{ asset('new-store/images/hero-video-poster.png') }}" autoplay playsinline loop>
                                        <source src="{{ asset('storage/' . $heroVideo) }}" type="video/mp4" />
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <video id="hero-video" class="w-full h-full object-cover" poster="{{ asset('new-store/images/hero-video-poster.png') }}" autoplay playsinline loop>
                                        <source src="{{ asset('new-store/images/videos/hero-video.mp4') }}" type="video/mp4" />
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>

                            {{-- Play/Pause Button --}}
                            <button id="play-button" aria-label="تشغيل / إيقاف الفيديو" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 sm:w-20 sm:h-20 bg-white/90 hover:bg-white rounded-full flex items-center justify-center text-primary transition-all hover:scale-110 z-10 shadow-lg">
                                <i class="fas fa-pause text-2xl sm:text-3xl ml-1"></i>
                            </button>

                            {{-- Audio Mute/Unmute Toggle Button --}}
                            <button id="audio-toggle-button" aria-label="تشغيل / كتم الصوت" title="تشغيل / كتم الصوت" class="absolute top-6 left-6 w-14 h-14 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg border-2 border-gray-200 text-primary transition-all hover:scale-110 z-20 cursor-pointer">
                                <i id="audio-icon" class="fas fa-volume-high text-xl"></i>
                            </button>

                            {{-- Social Media Links --}}
                            <div class="absolute bottom-4 right-4 flex flex-col gap-3 z-20">
                                <a href="#" class="w-12 h-12 bg-black rounded-full flex items-center justify-center hover:scale-110 transition-transform text-white shadow-lg">
                                    <i class="fab fa-tiktok text-xl"></i>
                                </a>
                                <a href="#" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center hover:scale-110 transition-transform text-white shadow-lg">
                                    <i class="fab fa-facebook-f text-xl"></i>
                                </a>
                                <a href="#" class="w-12 h-12 bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 rounded-full flex items-center justify-center hover:scale-110 transition-transform text-white shadow-lg">
                                    <i class="fab fa-instagram text-xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side - Title, Subtitle, Description, Buttons (Under video on mobile) --}}
                    <div class="flex flex-col space-y-6 text-right" dir="rtl">
                        <div>
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-primary leading-tight mb-5 hero-title-primary">
                                {!! $hero['title'] ?? 'تخيّر موتِرك..' !!}
                            </h1>
                            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gold leading-tight mb-4 hero-title-secondary">
                                {!! $hero['subtitle'] ?? 'وحنّا نيسّر لك التمويل' !!}
                            </h2>
                        </div>

                        <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                            {!! $settings['store_home_description'] ?? 'لديك التمويلات بين يديك مع خطط تمويل مرنة تناسب ميزانيتك، أيًا بدأت الحين مع "سوق سيارة" وعيش الرفاهية' !!}
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 pt-2">
                            <a href="{{ route('new.cars.index') }}" class="bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-lg transition-all hover:-translate-y-1 text-base text-center font-bold shadow-md">
                                استعرض السيارات
                            </a>
                            <a href="{{ route('new.calculator') }}" class="bg-white text-primary border-2 border-primary hover:bg-primary hover:text-white px-8 py-4 rounded-lg transition-all text-base text-center font-bold">
                                اطلب تمويلك الآن
                            </a>
                        </div>
                    </div>

                </div>

                {{-- 2. Banners / Carousel (Placed directly after Title & Action Buttons) --}}
                <div class="w-full pt-4" dir="rtl">
                    <div class="relative" dir="ltr">
                        <div class="relative">
                            <div class="carousel-container rounded-3xl overflow-hidden shadow-2xl" id="hero-carousel-container">
                                <div class="carousel-track flex transition-transform duration-500 ease-in-out" id="hero-carousel-track">
                                    @if($heroAd1['image'])
                                        <div class="carousel-slide min-w-full">
                                            <img src="{{ asset('storage/' . $heroAd1['image']) }}" alt="Car Financing 1" class="w-full h-auto" loading="eager" />
                                        </div>
                                    @else
                                        <div class="carousel-slide min-w-full">
                                            <img src="{{ asset('new-store/images/car-slide-1.png') }}" alt="Car Financing 1" class="w-full h-auto" loading="eager" />
                                        </div>
                                    @endif

                                    @if($heroAd2['image'])
                                        <div class="carousel-slide min-w-full">
                                            <img src="{{ asset('storage/' . $heroAd2['image']) }}" alt="Car Financing 2" class="w-full h-auto" loading="lazy" />
                                        </div>
                                    @else
                                        <div class="carousel-slide min-w-full">
                                            <img src="{{ asset('new-store/images/car-slide-2.png') }}" alt="Car Financing 2" class="w-full h-auto" loading="lazy" />
                                        </div>
                                    @endif

                                    @if($heroAd3['image'])
                                        <div class="carousel-slide min-w-full">
                                            <img src="{{ asset('storage/' . $heroAd3['image']) }}" alt="Car Financing 3" class="w-full h-auto" loading="lazy" />
                                        </div>
                                    @else
                                        <div class="carousel-slide min-w-full">
                                            <img src="{{ asset('new-store/images/car-slide-3.png') }}" alt="Car Financing 3" class="w-full h-auto" loading="lazy" />
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <button id="hero-carousel-prev" class="carousel-prev absolute left-2 bottom-2 w-11 h-11 bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center transition-all hover:scale-110 z-20 shadow-lg">
                                <i class="fas fa-arrow-left text-lg"></i>
                            </button>
                            <button id="hero-carousel-next" class="carousel-next absolute right-2 bottom-2 w-11 h-11 bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center transition-all hover:scale-110 z-20 shadow-lg">
                                <i class="fas fa-arrow-right text-lg"></i>
                            </button>
                        </div>

                        <div class="carousel-dots flex justify-center gap-2 mt-5" id="hero-carousel-dots">
                            <span class="dot w-10 h-2 bg-primary rounded-full cursor-pointer transition-all" data-index="0"></span>
                            <span class="dot w-3 h-2 bg-gray-300 rounded-full cursor-pointer transition-all" data-index="1"></span>
                            <span class="dot w-3 h-2 bg-gray-300 rounded-full cursor-pointer transition-all" data-index="2"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================
         2. SEARCH FILTER SECTION
    =========================================== --}}
    <section class="bg-white py-8 sm:py-12 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('new.cars.index') }}" method="GET" class="space-y-5">

                {{-- Filter Dropdowns Grid --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 search-grid-2-4" dir="rtl">

                    {{-- Brand --}}
                    <div class="select-wrapper">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 text-right filter-label-text">العلامة التجارية</label>
                        <div class="relative">
                            <select name="brands[]" class="w-full h-12 px-3 pl-8 border border-gray-300 rounded-lg text-gray-600 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right appearance-none">
                                <option value="">جميع العلامات</option>
                                @foreach($filterBrands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>

                    {{-- Year --}}
                    <div class="select-wrapper">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 text-right filter-label-text">سنة الصنع</label>
                        <div class="relative">
                            <select name="year" class="w-full h-12 px-3 pl-8 border border-gray-300 rounded-lg text-gray-600 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right appearance-none">
                                <option value="">جميع الموديلات</option>
                                @foreach($filterYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>

                    {{-- Type --}}
                    <div class="select-wrapper">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 text-right filter-label-text">نوع السيارة</label>
                        <div class="relative">
                            <select name="type" class="w-full h-12 px-3 pl-8 border border-gray-300 rounded-lg text-gray-600 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right appearance-none">
                                <option value="">جميع الأنواع</option>
                                <option value="sedan">سيدان</option>
                                <option value="suv">SUV</option>
                                <option value="coupe">كوبيه</option>
                                <option value="hatchback">هاتشباك</option>
                                <option value="pickup">بيك أب</option>
                                <option value="van">فان</option>
                            </select>
                            <i class="fas fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="select-wrapper">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 text-right filter-label-text">الموديل</label>
                        <div class="relative">
                            <select class="w-full h-12 px-3 pl-8 border border-gray-300 rounded-lg text-gray-600 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right appearance-none">
                                <option value="">جميع الموديلات</option>
                                @foreach($filterCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>

                </div>

                {{-- Search Bar + Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3" dir="rtl">
                    <div class="flex-1">
                        <input type="text" name="search"
                               placeholder="ابحث عن سيارة بالاسم، العلامة التجارية، الموديل..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right text-sm sm:text-base" />
                    </div>
                    <button type="submit"
                            class="bg-primary hover:bg-primary-dark text-white px-6 sm:px-8 py-3 rounded-lg transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 whitespace-nowrap font-semibold text-sm sm:text-base">
                        <i class="fas fa-search"></i><span>عرض النتائج</span>
                    </button>
                    <button type="reset"
                            class="bg-white text-gray-700 border-2 border-gray-300 hover:border-primary hover:text-primary px-6 sm:px-8 py-3 rounded-lg transition-all whitespace-nowrap font-semibold text-sm sm:text-base">
                        إعادة تعيين
                    </button>
                </div>

            </form>
        </div>
    </section>

    {{-- ==========================================
         3. FEATURED CARS SECTION
    =========================================== --}}
    <section class="bg-white py-10 sm:py-14 overflow-hidden" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4" dir="rtl">
                <div class="flex-1">
                    <h2 class="text-2xl sm:text-[32px] font-extrabold mb-2">
                        <span class="text-primary">السيارات</span><span class="text-gold"> المميزة</span>
                    </h2>
                    <p class="text-gray-600 text-sm sm:text-[15px] leading-relaxed">استكشف مجموعتنا مميزة، بمواصفات خاصة، وبأفضل عروض التمويل.</p>
                </div>
                <a href="{{ route('new.cars.index') }}"
                   class="bg-primary text-center text-white px-6 sm:px-8 py-3 rounded-md font-bold text-sm sm:text-[15px] hover:bg-primary-dark transition-all whitespace-nowrap w-full sm:w-auto">
                    عرض جميع السيارات
                </a>
            </div>

            {{-- Carousel --}}
            <div id="featured-cars-carousel" class="car-carousel-wrapper">
                <div class="car-carousel-viewport overflow-hidden">
                    <div class="car-carousel-track flex gap-6 sm:gap-10 transition-transform duration-500 ease-in-out">
                        @foreach($featuredCars as $car)
                            <div class="car-card shrink-0 w-[230px] bg-white rounded-[20px] border border-[#B8BFCF] flex flex-col items-center p-0 pb-4 gap-2" dir="rtl" style="isolation: isolate;">

                                {{-- Image with Overlays --}}
                                <div class="relative w-full flex flex-col" style="isolation: isolate;">
                                    <div class="relative w-full overflow-hidden rounded-t-[20px]" style="height: 185px;">
                                        <img src="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : asset('new-store/images/car-1.png') }}"
                                             alt="{{ $car->name }}"
                                             class="w-full h-full object-cover rounded-t-[20px] transition-transform duration-500 hover:scale-105" loading="lazy"
                                             style="image-rendering: -webkit-optimize-contrast; image-rendering: high-quality;" />

                                        {{-- Year Badge --}}
                                        <span class="absolute flex items-center justify-center rounded-full"
                                              style="top: 12px; left: 16px; padding: 6px 12px; gap: 4px; width: 57px; height: 26px; background: #1A3263; border: 1px solid #1A3263; backdrop-filter: blur(7.5px); font-family: 'Tajawal'; font-weight: 700; font-size: 12px; line-height: 14px; color: white; z-index: 4;">
                    {{ $car->year }}
                  </span>

                                        {{-- Gradient Overlay --}}
                                        <div class="absolute bottom-0 w-full" style="height: 77px; background: linear-gradient(180deg, rgba(26,50,99,0) 0%, rgba(26,50,99,0.2) 100%); z-index: 2;"></div>

                                        {{-- Compare Button --}}
                                        <a href="{{ route('new.compare', ['cars' => $car->id]) }}"
                                           class="absolute flex flex-row items-center rounded-full"
                                           style="left: 50%; transform: translateX(-50%); bottom: 14px; padding: 6px 12px; gap: 4px; background: #FFF9E6; border: 1px solid #FFECB1; backdrop-filter: blur(7.5px); z-index: 3;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15" stroke="#1A3263" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5C15 6.10457 14.1046 7 13 7H11C9.89543 7 9 6.10457 9 5Z" stroke="#1A3263" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span style="font-family: 'Tajawal'; font-weight: 700; font-size: 12px; line-height: 14px; color: #1A3263;">أضف للمقارنة</span>
                                        </a>
                                    </div>

                                    {{-- Details --}}
                                    <div class="flex flex-col items-center gap-2 w-full px-0">

                                        {{-- Car Name --}}
                                        <div class="w-full flex items-center" style="padding: 0px 16px 0px 0px; height: 22px;">
                                            <h3 class="text-primary" style="font-family: 'Tajawal'; font-weight: 700; font-size: 18px; line-height: 22px; text-align: right;">
                                                {{ $car->name }} <span dir="ltr">{{ $car->model }}</span>
                                            </h3>
                                        </div>

                                        {{-- Prices --}}
                                        <div class="flex w-full" style="height: 78px;">
                                            {{-- Monthly Payment --}}
                                            <div class="flex flex-col items-end flex-1 border-l border-[#B8BFCF]" style="padding: 12px 16px; gap: 2px;">
                                                <div class="flex flex-col items-end gap-1 w-full">
                                                    <span class="text-primary" style="font-family: 'Tajawal'; font-weight: 400; font-size: 10px; line-height: 12px;">القسط الشهري</span>
                                                    <span class="text-right self-stretch" style="font-family: 'Tajawal'; font-weight: 700; font-size: 18px; line-height: 19px; color: #FEC303;">
                          {{ $car->min_installment ? number_format($car->min_installment) : '---' }}
                        </span>
                                                    <span class="text-primary text-right self-stretch" style="font-family: 'Tajawal'; font-weight: 400; font-size: 10px; line-height: 12px;">تقديري</span>
                                                </div>
                                            </div>

                                            {{-- Cash Price --}}
                                            <div class="flex flex-col items-end flex-1" style="padding: 12px 16px; gap: 2px;">
                                                <div class="flex flex-col items-end gap-1 w-full">
                                                    <span class="text-primary text-right self-stretch" style="font-family: 'Tajawal'; font-weight: 400; font-size: 10px; line-height: 12px;">سعر الكاش</span>
                                                    <span class="text-right self-stretch" style="font-family: 'Tajawal'; font-weight: 700; font-size: 18px; line-height: 19px; color: #FEC303;">
                          {{ number_format($car->cash_price) }}
                        </span>
                                                </div>
                                                {{-- Strikethrough original price --}}
                                                <div class="relative self-start">
                        <span style="font-family: 'Tajawal'; font-weight: 400; font-size: 12px; line-height: 13px; text-align: right; color: #1A3263;">
                          {{ number_format($car->cash_price) }}
                        </span>
                                                    <div class="absolute w-full" style="height: 0px; top: 6px; border-top: 1px solid #1A3263;"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- Specs Row --}}
                                <div class="flex items-center justify-between w-full px-4" style="height: 48px;">
                                    <div class="flex flex-col items-start gap-4">
                                        <div class="flex items-center gap-1">
                                            <span style="font-family: 'Cairo'; font-weight: 400; font-size: 12px; line-height: 16px; color: #1A3263;">{{ $car->specs['fuel_type'] ?? 'بنزين' }}</span>
                                            <i class="fas fa-gas-pump text-primary" style="font-size: 16px;"></i>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span style="font-family: 'Cairo'; font-weight: 400; font-size: 12px; line-height: 16px; color: #1A3263;">{{ $car->specs['engine_size'] ?? ($car->specs['engine_capacity'] ?? '5.0') }}</span>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#1A3263" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M19 10h-2V7c0-.55-.45-1-1-1H8c-.55 0-1 .45-1 1v3H5c-1.1 0-2 .9-2 2v5c0 .55.45 1 1 1h1.33c.47 0 .89-.28 1.05-.68.05-.13.12-.25.2-.35.33-.44.84-.72 1.42-.72s1.09.28 1.42.72c.08.1.15.22.2.35.16.4.58.68 1.05.68h1.66c.47 0 .89-.28 1.05-.68.05-.13.12-.25.2-.35.33-.44.84-.72 1.42-.72s1.09.28 1.42.72c.08.1.15.22.2.35.16.4.58.68 1.05.68H21c.55 0 1-.45 1-1v-5c0-1.1-.9-2-2-2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-4">
                                        <div class="flex items-center gap-1">
                                            <span style="font-family: 'Cairo'; font-weight: 400; font-size: 12px; line-height: 16px; color: #1A3263;">{{ $car->specs['transmission'] ?? 'أتوماتيك' }}</span>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="3" y="4" width="18" height="16" rx="2" stroke="#1A3263" stroke-width="1.333" />
                                                <rect x="5" y="8" width="14" height="8" rx="1" stroke="#1A3263" stroke-width="1.333" />
                                                <line x1="5" y1="12" x2="19" y2="12" stroke="#1A3263" stroke-width="1.333" />
                                            </svg>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @php
                                                $typeLabels = ['sedan' => 'سيدان', 'suv' => 'SUV', 'coupe' => 'كوبيه', 'hatchback' => 'هاتشباك', 'pickup' => 'بيك أب', 'van' => 'فان', 'other' => 'أخرى'];
                                            @endphp
                                            <span style="font-family: 'Cairo'; font-weight: 400; font-size: 12px; line-height: 16px; color: #1A3263;">{{ $typeLabels[$car->type] ?? 'SUV' }}</span>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 14L3 19M19 14L21 19M4.5 14H19.5L18 8H6L4.5 14Z" stroke="#1A3263" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <circle cx="7" cy="17" r="2" stroke="#1A3263" stroke-width="1.5"/>
                                                <circle cx="17" cy="17" r="2" stroke="#1A3263" stroke-width="1.5"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Button --}}
                                <div class="flex justify-center w-full">
                                    <a href="{{ route('new.cars.show', $car->slug) }}"
                                       class="flex items-center justify-center"
                                       style="width: 171px; height: 51px; border: 1px solid #1A3263; border-radius: 8px; font-family: 'Tajawal'; font-weight: 700; font-size: 16px; line-height: 19px; color: #1A3263; background: transparent;">
                                        عرض التفاصيل
                                    </a>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="flex items-center justify-center gap-12 mt-14">
                    <button type="button" class="car-carousel-prev w-[52px] h-[52px] bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center transition-all hover:scale-105">
                        <i class="fas fa-arrow-right text-[22px]"></i>
                    </button>

                    <div class="relative w-[112px] h-[8px] bg-gray-300 rounded-full overflow-hidden">
                        <span class="car-carousel-progress absolute top-0 right-0 h-full w-[42px] bg-primary rounded-full transition-all duration-300"></span>
                    </div>

                    <button type="button" class="car-carousel-next w-[52px] h-[52px] bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center transition-all hover:scale-105">
                        <i class="fas fa-arrow-left text-[22px]"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>

    {{-- ==========================================
         3.5 CARS LIST SECTION
    =========================================== --}}
    <section class="bg-white py-12 border-t border-gray-100" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4" dir="rtl">
                <div class="flex-1">
                    <h2 class="text-[32px] font-extrabold mb-3">
                        <span class="text-primary">قائمة</span>
                        <span class="text-gold"> السيارات</span>
                    </h2>
                    <p class="text-gray-600 text-[15px] leading-relaxed">
                        أكثر من مئة أفضل السيارات المتاحة
                    </p>
                </div>
                <a href="{{ route('new.cars.index') }}"
                   class="bg-primary text-center text-white px-8 py-3 rounded-md font-bold text-[15px] hover:bg-primary-dark transition-all whitespace-nowrap w-full sm:w-auto"
                >
                    عرض جميع السيارات
                </a>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Filter Sidebar -->
                <div class="lg:col-span-1 order-1 lg:order-1">
                    <div class="space-y-4" id="car-list-filters">
                        <!-- Used Cars Filter -->
                        <button data-filter="popular" class="car-filter-btn active w-full bg-primary text-white px-6 py-4 rounded-lg font-bold text-[16px] hover:bg-primary-dark transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-car text-[18px]"></i>
                            <span>سيارات شائعة</span>
                        </button>

                        <!-- Certified Pre-owned Filter -->
                        <button data-filter="test-drive" class="car-filter-btn w-full bg-white text-primary border-2 border-[#E5E7EB] px-6 py-4 rounded-lg font-bold text-[16px] hover:border-primary transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-certificate text-[18px]"></i>
                            <span>سيارات مع تجربة قيادة</span>
                        </button>

                        <!-- Best Offers Filter -->
                        <button data-filter="offers" class="car-filter-btn w-full bg-white text-primary border-2 border-[#E5E7EB] px-6 py-4 rounded-lg font-bold text-[16px] hover:border-primary transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-trophy text-[18px]"></i>
                            <span>أفضل العروض</span>
                        </button>

                        <!-- New Cars Filter -->
                        <button data-filter="new" class="car-filter-btn w-full bg-white text-primary border-2 border-[#E5E7EB] px-6 py-4 rounded-lg font-bold text-[16px] hover:border-primary transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-sparkles text-[18px]"></i>
                            <span>سيارات جديدة</span>
                        </button>

                        <!-- Discount Cars Filter -->
                        <button data-filter="discount" class="car-filter-btn w-full bg-white text-primary border-2 border-[#E5E7EB] px-6 py-4 rounded-lg font-bold text-[16px] hover:border-primary transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-tags text-[18px]"></i>
                            <span>سيارات مع خصم</span>
                        </button>
                    </div>
                </div>

                <!-- Cars Carousel -->
                <div class="lg:col-span-3 order-2 lg:order-2">
                    <div id="car-list-carousel" class="car-carousel-wrapper">
                        <div class="car-carousel-viewport overflow-hidden">
                            <div class="car-carousel-track flex gap-6 sm:gap-10 transition-transform duration-500 ease-in-out">
                                @foreach($featuredCars as $car)
                                    @include('new-store.partials.car-card', ['car' => $car])
                                @endforeach
                            </div>
                        </div>

                        <!-- Bottom Controls -->
                        <div class="flex items-center justify-center gap-12 mt-14">
                            <button type="button" class="car-carousel-prev w-[52px] h-[52px] bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center transition-all hover:scale-105">
                                <i class="fas fa-arrow-right text-[22px]"></i>
                            </button>

                            <div class="relative w-[112px] h-[8px] bg-gray-300 rounded-full overflow-hidden">
                                <span class="car-carousel-progress absolute top-0 right-0 h-full w-[42px] bg-primary rounded-full transition-all duration-300"></span>
                            </div>

                            <button type="button" class="car-carousel-next w-[52px] h-[52px] bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center transition-all hover:scale-105">
                                <i class="fas fa-arrow-left text-[22px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         4. OFFERS GRID SECTION
    =========================================== --}}
    <section class="bg-white py-12" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="flex items-start justify-between mb-8">
                <div class="flex-1">
                    <h2 class="text-[32px] font-extrabold mb-3 text-center">
                        <span class="text-primary">صور من</span>
                        <span class="text-gold"> معرضنا</span>
                    </h2>
                </div>
            </div>

            {{-- Offers Grid --}}
            <div class="offers-grid">

                {{-- Row 1: 3 cards (left tall, center short + logo, right tall) --}}
                <div class="offers-row-1">

                    <div class="offer-card offer-tall">
                        <img src="{{ asset('new-store/images/offers/offer-1.svg') }}" alt="عرض 1" loading="lazy" />
                        <div class="offer-overlay"></div>
                        <div class="offer-content">
                            <div class="offer-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </div>
                    </div>

                    <div class="offer-column-center">
                        <div class="offer-card offer-short">
                            <img src="{{ asset('new-store/images/offers/offer-2.svg') }}" alt="عرض 2" loading="lazy" />
                            <div class="offer-overlay"></div>
                            <div class="offer-content">
                                <div class="offer-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </div>
                        </div>

                        <div class="offer-card offer-logo-card">
                            <img src="{{ asset('new-store/images/Logo.svg') }}" alt="Logo" class="logo-only" loading="lazy" />
                        </div>
                    </div>

                    <div class="offer-card offer-tall">
                        <img src="{{ asset('new-store/images/offers/offer-3.svg') }}" alt="عرض 3" loading="lazy" />
                        <div class="offer-overlay"></div>
                        <div class="offer-content">
                            <div class="offer-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Row 2: 2 equal cards --}}
                <div class="offers-row-2">
                    <div class="offer-card offer-wide">
                        <img src="{{ asset('new-store/images/offers/offer-4.svg') }}" alt="عرض 4" loading="lazy" />
                        <div class="offer-overlay"></div>
                        <div class="offer-content">
                            <div class="offer-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </div>
                    </div>

                    <div class="offer-card offer-wide">
                        <img src="{{ asset('new-store/images/offers/offer-5.svg') }}" alt="عرض 5" loading="lazy" />
                        <div class="offer-overlay"></div>
                        <div class="offer-content">
                            <div class="offer-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- ==========================================
         5. BRANDS CAROUSEL SECTION
    =========================================== --}}
    <section class="bg-white py-10 sm:py-14" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4" dir="rtl">
                <div class="flex-1">
                    <h2 class="text-2xl sm:text-[32px] font-extrabold mb-2">
                        <span class="text-primary">تختار براندك.. خيارات</span><span class="text-gold"> لا محدودة</span>
                    </h2>
                </div>
            </div>

            {{-- Brands Carousel --}}
            <div id="brands-carousel" class="brands-carousel-wrapper">
                <div class="brands-carousel-viewport overflow-hidden">
                    <div class="brands-carousel-track" id="brands-carousel-track">
                        {{-- Page 1: First 10 brands --}}
                        <div class="brands-page-desktop">
                            @foreach($brands->take(10) as $brand)
                                <a href="{{ route('new.cars.index', ['brand_id' => $brand->id]) }}" class="brand-card">
                                    <div class="brand-logo-container">
                                        <img src="{{ $brand->logo ? asset('storage/'.$brand->logo) : asset('new-store/images/brands/brand-1.svg') }}"
                                             alt="{{ $brand->name }}" loading="lazy" />
                                    </div>
                                    <p class="brand-name">{{ $brand->name }}</p>
                                </a>
                            @endforeach
                        </div>

                        {{-- Page 2: Next 10 brands (if exist) --}}
                        @if($brands->count() > 10)
                            <div class="brands-page-desktop">
                                @foreach($brands->skip(10)->take(10) as $brand)
                                    <a href="{{ route('new.cars.index', ['brand_id' => $brand->id]) }}" class="brand-card">
                                        <div class="brand-logo-container">
                                            <img src="{{ $brand->logo ? asset('storage/'.$brand->logo) : asset('new-store/images/brands/brand-1.svg') }}"
                                                 alt="{{ $brand->name }}" loading="lazy" />
                                        </div>
                                        <p class="brand-name">{{ $brand->name }}</p>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="brands-nav-area">
                    <button type="button" class="brands-nav-btn" id="brands-prev">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <div class="brands-pagination-dots" id="brands-dots">
                        @for($i = 0; $i < ceil($brands->count() / 10); $i++)
                            <button class="brands-dot {{ $i === 0 ? 'active' : '' }}"></button>
                        @endfor
                    </div>
                    <button type="button" class="brands-nav-btn" id="brands-next">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>

    {{-- ==========================================
         6. TESTIMONIALS SECTION
    =========================================== --}}
    <section class="testimonials-section" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center mb-12">
                <h2 class="text-[36px] font-extrabold mb-4">
                    <span class="text-primary">تجارب</span>
                    <span class="text-gold"> نفخر بها</span>
                </h2>
                <p class="text-gray-600 text-[16px] leading-relaxed max-w-3xl mx-auto">
                    لأن رضاكم هو غايتنا، نشارككم آراء نخبة من عملائنا حول خدماتنا وحلولنا التمويلية
                </p>
            </div>

            {{-- Testimonials Carousel --}}
            <div class="testimonials-carousel-wrapper">
                <div class="testimonials-viewport">
                    <div class="testimonials-track" id="testimonials-track">

                        @php $chunks = $testimonials->chunk(3); @endphp
                        @foreach($chunks as $chunk)
                            <div class="testimonials-page">
                                @foreach($chunk as $t)
                                    <div class="testimonial-card">
                                        <div class="quote-icon">
                                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                                <path d="M12 28C12 24.6863 14.6863 22 18 22V18C12.4772 18 8 22.4772 8 28V36H20V28H12Z" fill="#d4a017"/>
                                                <path d="M32 28C32 24.6863 34.6863 22 38 22V18C32.4772 18 28 22.4772 28 28V36H40V28H32Z" fill="#d4a017"/>
                                            </svg>
                                        </div>
                                        <p class="testimonial-text">{{ $t->content }}</p>
                                        <div class="rating-stars">
                                            @for($i = 0; $i < $t->rating; $i++)
                                                <i class="fas fa-star text-gold"></i>
                                            @endfor
                                            @for($i = $t->rating; $i < 5; $i++)
                                                <i class="far fa-star text-gray-300"></i>
                                            @endfor
                                        </div>
                                        <div class="customer-info">
                                            <div class="customer-avatar">
                                                @if($t->image)
                                                    <img src="{{ asset('storage/' . $t->image) }}" alt="{{ $t->name }}" loading="lazy" />
                                                @else
                                                    <span>{{ mb_substr($t->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div class="customer-details">
                                                <h4 class="customer-name text-primary font-bold">{{ $t->name }}</h4>
                                                @if($t->title)
                                                    <p class="customer-title text-gray-500 text-sm">{{ $t->title }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- Navigation --}}
                @if($chunks->count() > 1)
                    <div class="flex items-center justify-center gap-8 mt-10">
                        <button type="button" class="testimonials-prev w-[48px] h-[48px] bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center transition-all" id="testimonials-prev">
                            <i class="fas fa-arrow-right text-lg"></i>
                        </button>
                        <div class="testimonials-dots flex gap-2" id="testimonials-dots">
                            @foreach($chunks as $i => $chunk)
                                <span class="testimonials-dot {{ $i === 0 ? 'active' : '' }} w-3 h-3 rounded-full bg-gray-300 transition-all"></span>
                            @endforeach
                        </div>
                        <button type="button" class="testimonials-next w-[48px] h-[48px] bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center transition-all" id="testimonials-next">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // ==========================================
        // 1. VIDEO PLAYER & AUDIO TOGGLE
        // ==========================================
        (function() {
            const video = document.getElementById("hero-video");
            const playButton = document.getElementById("play-button");
            const audioButton = document.getElementById("audio-toggle-button");
            const audioIcon = document.getElementById("audio-icon");

            if (video) {
                // Default sound ON: attempt unmuted playback
                video.muted = false;
                const playPromise = video.play();

                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        if (audioIcon) audioIcon.className = 'fas fa-volume-high text-xl';
                    }).catch(function(err) {
                        // Browser autoplay policy prevented audio, fallback to muted until user clicks
                        console.log("Audio autoplay prevented by browser. Falling back to muted autoplay.");
                        video.muted = true;
                        video.play();
                        if (audioIcon) audioIcon.className = 'fas fa-volume-xmark text-xl';
                    });
                }

                // Play / Pause Toggle
                if (playButton) {
                    playButton.addEventListener("click", () => {
                        if (video.paused) {
                            video.play();
                            playButton.innerHTML = '<i class="fas fa-pause text-2xl sm:text-3xl ml-1"></i>';
                        } else {
                            video.pause();
                            playButton.innerHTML = '<i class="fas fa-play text-2xl sm:text-3xl ml-1"></i>';
                        }
                    });
                }

                // Audio Mute / Unmute Toggle
                if (audioButton && audioIcon) {
                    audioButton.addEventListener("click", () => {
                        if (video.muted) {
                            video.muted = false;
                            video.volume = 1.0;
                            audioIcon.className = 'fas fa-volume-high text-xl';
                        } else {
                            video.muted = true;
                            audioIcon.className = 'fas fa-volume-xmark text-xl';
                        }
                    });
                }
            }
        })();

        // ==========================================
        // 2. HERO CAROUSEL (اليمين)
        // ==========================================
        (function() {
            const track = document.getElementById("hero-carousel-track");
            const slides = track ? track.querySelectorAll(".carousel-slide") : [];
            const dots = document.querySelectorAll("#hero-carousel-dots .dot");
            const prevBtn = document.getElementById("hero-carousel-prev");
            const nextBtn = document.getElementById("hero-carousel-next");
            let current = 0;
            const total = slides.length;

            function goTo(index) {
                if (total === 0) return;
                current = (index + total) % total;
                if (track) track.style.transform = `translateX(-${current * 100}%)`;
                dots.forEach((dot, i) => {
                    if (i === current) {
                        dot.classList.replace("w-3", "w-10");
                        dot.classList.replace("bg-gray-300", "bg-primary");
                    } else {
                        dot.classList.replace("w-10", "w-3");
                        dot.classList.replace("bg-primary", "bg-gray-300");
                    }
                });
            }

            if (prevBtn) prevBtn.addEventListener("click", () => goTo(current - 1));
            if (nextBtn) nextBtn.addEventListener("click", () => goTo(current + 1));
            dots.forEach((dot, i) => dot.addEventListener("click", () => goTo(i)));

            // Auto play
            if (total > 1) {
                setInterval(() => goTo(current + 1), 5000);
            }
        })();

        // ==========================================
        // 3. FEATURED CARS CAROUSEL
        // ==========================================
        setTimeout(() => {
            if (window.CarCarousel) {
                new window.CarCarousel('#featured-cars-carousel');
            } else {
                // Fallback if CarCarousel not loaded
                const wrapper = document.getElementById('featured-cars-carousel');
                if (!wrapper) return;

                const track = wrapper.querySelector('.car-carousel-track');
                const prevBtn = wrapper.querySelector('.car-carousel-prev');
                const nextBtn = wrapper.querySelector('.car-carousel-next');
                const progressBar = wrapper.querySelector('.car-carousel-progress');
                const cards = track ? track.querySelectorAll('.car-card') : [];

                let current = 0;
                const getVisible = () => window.innerWidth < 480 ? 1 : window.innerWidth < 768 ? 1 : window.innerWidth < 1024 ? 2 : 3;

                function getCardWidth() {
                    if (!cards[0]) return 280;
                    return cards[0].offsetWidth + parseInt(getComputedStyle(track).gap || 40);
                }

                function updateProgress() {
                    if (!progressBar || cards.length === 0) return;
                    const visible = getVisible();
                    const maxIndex = Math.max(0, cards.length - visible);
                    const pct = maxIndex > 0 ? (current / maxIndex) * 100 : 0;
                    progressBar.style.width = Math.max(30, 42 - pct * 0.2) + 'px';
                    progressBar.style.right = (pct * 0.7) + '%';
                }

                function goTo(index) {
                    const visible = getVisible();
                    const maxIndex = Math.max(0, cards.length - visible);
                    current = Math.max(0, Math.min(index, maxIndex));
                    const offset = current * getCardWidth();
                    if (track) track.style.transform = `translateX(${offset}px)`;
                    updateProgress();
                }

                if (prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
                if (nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));
                updateProgress();
            }
        }, 300);

        // ==========================================
        // 3.5 CARS LIST CAROUSEL
        // ==========================================
        setTimeout(() => {
            const wrapper = document.getElementById('car-list-carousel');
            if (!wrapper) return;

            const track = wrapper.querySelector('.car-carousel-track');
            const prevBtn = wrapper.querySelector('.car-carousel-prev');
            const nextBtn = wrapper.querySelector('.car-carousel-next');
            let cards = track ? Array.from(track.querySelectorAll('.car-card')) : [];

            let current = 0;
            const getVisible = () => window.innerWidth < 480 ? 1 : window.innerWidth < 768 ? 1 : window.innerWidth < 1024 ? 2 : 3;

            function getCardWidth() {
                if (!cards[0]) return 280;
                return cards[0].offsetWidth + parseInt(getComputedStyle(track).gap || 40);
            }

            function goTo(index) {
                const visible = getVisible();
                const maxIndex = Math.max(0, cards.length - visible);
                current = Math.max(0, Math.min(index, maxIndex));
                const offset = current * getCardWidth();
                if (track) track.style.transform = `translateX(${offset}px)`;
            }

            if (prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));

            // Backend Integration for Filters
            const filterBtns = document.querySelectorAll('.car-filter-btn');
            if (filterBtns.length > 0) {
                filterBtns.forEach(btn => {
                    btn.addEventListener('click', async function() {
                        // Update UI state
                        filterBtns.forEach(b => {
                            b.classList.remove('bg-primary', 'text-white', 'active');
                            b.classList.add('bg-white', 'text-primary');
                        });
                        this.classList.remove('bg-white', 'text-primary');
                        this.classList.add('bg-primary', 'text-white', 'active');

                        const filter = this.dataset.filter;
                        try {
                            // Render Skeleton Loaders
                            const homeSkeleton = `{!! view('new-store.partials.car-card-skeleton')->render() !!}`;
                            if (track) track.innerHTML = homeSkeleton.repeat(4);

                            const response = await fetch(`{{ route('new.cars.api.filter') }}?filter=${filter}`);
                            const data = await response.json();

                            if (data.html !== undefined && track) {
                                track.innerHTML = data.html;
                                // Re-initialize cards and sizes as a mutable array
                                cards = Array.from(track.querySelectorAll('.car-card'));

                                // Reset position
                                current = 0;
                                goTo(0);
                            }
                        } catch (error) {
                            console.error("Failed to fetch cars:", error);
                        }
                    });
                });
            }
        }, 300);

        // ==========================================
        // 4. BRANDS CAROUSEL
        // ==========================================
        (function() {
            const track = document.getElementById('brands-carousel-track');
            const prevBtn = document.getElementById('brands-prev');
            const nextBtn = document.getElementById('brands-next');
            const dotsContainer = document.getElementById('brands-dots');

            if (!track) return;

            const pages = track.querySelectorAll('.brands-page-desktop');
            const total = pages.length;
            let current = 0;

            if (total <= 1) {
                const navArea = track.closest('.brands-carousel-wrapper').querySelector('.brands-nav-area');
                if (navArea) navArea.style.display = 'none';
                return;
            }

            const dots = dotsContainer ? dotsContainer.querySelectorAll('.brands-dot') : [];

            function goTo(index) {
                current = (index + total) % total;
                const viewportWidth = track.parentElement.offsetWidth;
                track.style.transform = `translateX(${current * viewportWidth}px)`;

                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === current);
                });
            }

            if (prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));
            dots.forEach((dot, i) => dot.addEventListener('click', () => goTo(i)));

            window.addEventListener('resize', () => {
                const vw = track.parentElement.offsetWidth;
                track.style.transform = `translateX(${current * vw}px)`;
            });
        })();

        // ==========================================
        // 5. TESTIMONIALS CAROUSEL
        // ==========================================
        (function() {
            const track = document.getElementById('testimonials-track');
            const prevBtn = document.getElementById('testimonials-prev');
            const nextBtn = document.getElementById('testimonials-next');
            const dotsContainer = document.getElementById('testimonials-dots');

            if (!track) return;

            const pages = track.querySelectorAll('.testimonials-page');
            const total = pages.length;
            let current = 0;

            if (total <= 1) {
                const navArea = track.closest('.testimonials-carousel-wrapper')?.querySelector('.flex.items-center.justify-center.gap-8');
                if (navArea) navArea.style.display = 'none';
                return;
            }

            const dots = dotsContainer ? dotsContainer.querySelectorAll('.testimonials-dot') : [];

            function goTo(index) {
                current = (index + total) % total;
                const viewportWidth = track.parentElement.offsetWidth;
                track.style.transform = `translateX(${current * viewportWidth}px)`;

                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === current);
                });
            }

            if (prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));
            dots.forEach((dot, i) => dot.addEventListener('click', () => goTo(i)));

            window.addEventListener('resize', () => {
                const vw = track.parentElement.offsetWidth;
                track.style.transform = `translateX(${current * vw}px)`;
            });

            setInterval(() => goTo(current + 1), 6000);
        })();
    </script>
@endpush
