{{-- Header Partial --}}
@php
    $currentRoute = Route::currentRouteName() ?? '';
@endphp

{{-- Mobile Overlay --}}
<div class="mobile-drawer-overlay" id="drawer-overlay"></div>

{{-- Mobile Drawer --}}
<div class="mobile-drawer" id="mobile-drawer">
    <div class="mobile-drawer-header">
        <button class="mobile-drawer-close" id="drawer-close">
            <i class="fas fa-times"></i>
        </button>
        <a href="{{ route('new.home') }}">
            <img src="{{ $logoSrc }}" alt="سوق سيارة" style="height:48px;" loading="lazy" />
        </a>
    </div>
    <nav>
        <a href="{{ route('new.home') }}" class="nav-link-mobile {{ Str::startsWith($currentRoute, 'new.home') ? 'active' : '' }}">الرئيسية</a>
        <a href="{{ route('new.cars.index') }}" class="nav-link-mobile {{ Str::startsWith($currentRoute, 'new.cars') ? 'active' : '' }}">جميع السيارات</a>
        <a href="{{ route('new.offers.index') }}" class="nav-link-mobile {{ Str::startsWith($currentRoute, 'new.offers') ? 'active' : '' }}">العروض</a>
        <a href="{{ route('new.calculator') }}" class="nav-link-mobile {{ Str::startsWith($currentRoute, 'new.calculator') ? 'active' : '' }}">التمويل</a>
        <a href="{{ route('new.about') }}" class="nav-link-mobile {{ $currentRoute === 'new.about' ? 'active' : '' }}">من نحن</a>
        <a href="{{ route('new.blog.index') }}" class="nav-link-mobile {{ Str::startsWith($currentRoute, 'new.blog') ? 'active' : '' }}">المقالات</a>
        <a href="{{ route('new.contact') }}" class="nav-link-mobile {{ $currentRoute === 'new.contact' ? 'active' : '' }}">تواصل معنا</a>
    </nav>
    <a href="{{ route('new.booking') }}" class="mobile-drawer-btn">حجز سيارة</a>
</div>

{{-- Main Header --}}
<header class="bg-white shadow-md py-4 sm:py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-row-reverse justify-between items-center gap-4">

            {{-- CTA Button (Desktop, rightmost) --}}
            <a href="{{ route('new.booking') }}"
               class="hidden sm:block bg-primary hover:bg-primary-dark text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg transition-colors">
                حجز سيارة
            </a>

            {{-- Navigation (Desktop, center) --}}
            <nav class="hidden sm:flex flex-wrap justify-center gap-4 sm:gap-8 flex-1" id="main-nav">
                <a href="{{ route('new.home') }}"
                   class="nav-link font-medium text-sm sm:text-base transition-colors {{ Str::startsWith($currentRoute, 'new.home') || $currentRoute === '' ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                   الرئيسية
                </a>
                <a href="{{ route('new.cars.index') }}"
                   class="nav-link font-medium text-sm sm:text-base transition-colors {{ Str::startsWith($currentRoute, 'new.cars') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                   جميع السيارات
                </a>
                <a href="{{ route('new.offers.index') }}"
                   class="nav-link font-medium text-sm sm:text-base transition-colors {{ Str::startsWith($currentRoute, 'new.offers') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                   العروض
                </a>
                <a href="{{ route('new.calculator') }}"
                   class="nav-link font-medium text-sm sm:text-base transition-colors {{ Str::startsWith($currentRoute, 'new.calculator') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                   التمويل
                </a>
                <a href="{{ route('new.about') }}"
                   class="nav-link font-medium text-sm sm:text-base transition-colors {{ $currentRoute === 'new.about' ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                   من نحن
                </a>
                <a href="{{ route('new.blog.index') }}"
                   class="nav-link font-medium text-sm sm:text-base transition-colors {{ Str::startsWith($currentRoute, 'new.blog') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                   المقالات
                </a>
            </nav>

            {{-- Hamburger (Mobile) --}}
            <button id="mobile-menu-btn" class="sm:hidden text-primary text-2xl">
                <i class="fas fa-bars"></i>
            </button>

            {{-- Logo (leftmost) --}}
            <div>
                <a href="{{ route('new.home') }}">
                    <img src="{{ $logoSrc }}" alt="سوق سيارة" class="h-12 sm:h-16" loading="lazy" />
                </a>
            </div>

        </div>
    </div>
</header>

<script>
    (function () {
        const drawer  = document.getElementById('mobile-drawer');
        const overlay = document.getElementById('drawer-overlay');

        function openDrawer()  { drawer.classList.add('open');    overlay.classList.add('open');    document.body.style.overflow = 'hidden'; }
        function closeDrawer() { drawer.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow = ''; }

        document.getElementById('mobile-menu-btn').addEventListener('click', openDrawer);
        document.getElementById('drawer-close').addEventListener('click', closeDrawer);
        overlay.addEventListener('click', closeDrawer);
    })();
</script>
