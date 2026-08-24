@php
    $logo = \App\Models\Setting::where('key', 'site_logo')->first()?->value;
    $siteName = \App\Models\Setting::where('key', 'site_name')->first()?->value;
    $siteNameText = is_array($siteName) ? ($siteName[app()->getLocale()] ?? ($siteName['ar'] ?? 'Souq Siarh')) : ($siteName ?? 'Souq Siarh');
    $currentUser = auth()->guard('employee')->user();
    $r = request()->route()?->getName() ?? '';

    // Helper: check if any route in a group is active
    $groupActive = function(array $prefixes) use ($r) {
        foreach ($prefixes as $prefix) {
            if (str_starts_with($r, $prefix)) return true;
        }
        return false;
    };
@endphp

<aside class="crm-sidebar">

    {{-- Close Button (Mobile Only) --}}
    <button class="crm-sidebar-close" id="crmSidebarClose" aria-label="Close menu">
        <i class="bi bi-x-lg"></i>
    </button>

    {{-- Logo --}}
    <div class="crm-sidebar-logo">
        @if($logo)
            <img src="{{ asset('storage/' . $logo) }}" alt="{{ $siteNameText }}">
        @else
            <div style="display:flex;align-items:center;justify-content:center;gap:5px;">
                <span style="font-size:20px;font-weight:900;color:var(--crm-red);">GR</span>
                <span style="font-size:12px;font-weight:800;color:var(--crm-text);">Motors</span>
            </div>
        @endif
    </div>

    {{-- Navigation --}}
    <nav class="crm-nav">

        {{-- ● الرئيسية --}}
        @can('dashboard.view')
        <div class="crm-nav-section">
            <a href="{{ route('crm.dashboard') }}"
               class="crm-nav-link {{ str_starts_with($r,'crm.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span>{{ __('الرئيسية') }}</span>
            </a>
        </div>
        @endcan

        {{-- ● الكتالوج --}}
        @php
            $catOpen = $groupActive(['crm.cars','crm.brands','crm.car-categories','crm.specifications','crm.features','crm.offers']);
            $canCatalog = $currentUser->hasAnyPermission(['cars.view','brands.view','categories.view','specifications.view','features.view','offers.view']);
        @endphp
        @if($canCatalog)
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle {{ $catOpen ? 'active' : '' }}"
                    onclick="toggleGroup('g-catalog')">
                <i class="bi bi-collection"></i>
                <span>{{ __('الكتالوج') }}</span>
                <i class="bi bi-chevron-{{ $catOpen ? 'up' : 'down' }} crm-chevron"></i>
            </button>
            <ul id="g-catalog" class="crm-sub-list {{ $catOpen ? 'open' : '' }}">
                @can('cars.view')
                <li>
                    <a href="{{ route('crm.cars.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.cars') ? 'active' : '' }}">
                        <i class="bi bi-car-front"></i> {{ __('السيارات') }}
                    </a>
                </li>
                @endcan
                @can('brands.view')
                <li>
                    <a href="{{ route('crm.brands.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.brands') ? 'active' : '' }}">
                        <i class="bi bi-bookmark-star"></i> {{ __('الماركات') }}
                    </a>
                </li>
                @endcan
                @can('categories.view')
                <li>
                    <a href="{{ route('crm.car-categories.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.car-categories') ? 'active' : '' }}">
                        <i class="bi bi-folder2-open"></i> {{ __('التصنيفات') }}
                    </a>
                </li>
                @endcan
                @can('specifications.view')
                <li>
                    <a href="{{ route('crm.specifications.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.specifications') ? 'active' : '' }}">
                        <i class="bi bi-gear-wide-connected"></i> {{ __('المواصفات') }}
                    </a>
                </li>
                @endcan
                @can('features.view')
                <li>
                    <a href="{{ route('crm.features.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.features') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i> {{ __('المميزات') }}
                    </a>
                </li>
                @endcan
                @can('offers.view')
                <li>
                    <a href="{{ route('crm.offers.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.offers') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> {{ __('العروض') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        @endif

        {{-- ● إدارة العملاء --}}
        @php
            $custOpen = $groupActive(['crm.leads','crm.contact-messages','crm.calculator-leads','crm.contact-sources']);
            $canCustomers = $currentUser->hasAnyPermission(['contacts.view','calculator-leads.view','contact-sources.view']);
        @endphp
        @if($canCustomers)
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle {{ $custOpen ? 'active' : '' }}"
                    onclick="toggleGroup('g-clients')">
                <i class="bi bi-people"></i>
                <span>{{ __('إدارة العملاء') }}</span>
                <i class="bi bi-chevron-{{ $custOpen ? 'up' : 'down' }} crm-chevron"></i>
            </button>
            <ul id="g-clients" class="crm-sub-list {{ $custOpen ? 'open' : '' }}">
                @can('contacts.view')
                <li>
                    <a href="{{ route('crm.leads.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.leads') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i> {{ __('العملاء') }}
                        <span class="nav-badge new-leads-badge ms-auto" style="display:none;">0</span>
                    </a>
                </li>
                @endcan
                @can('contacts.view')
                <li>
                    <a href="{{ route('crm.contact-messages.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.contact-messages') ? 'active' : '' }}">
                        <i class="bi bi-envelope"></i> {{ __('رسائل التواصل') }}
                    </a>
                </li>
                @endcan
                @can('calculator-leads.view')
                <li>
                    <a href="{{ route('crm.calculator-leads.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.calculator-leads') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> {{ __('عملاء الحاسبة') }}
                    </a>
                </li>
                @endcan
                @can('contact-sources.view')
                <li>
                    <a href="{{ route('crm.contact-sources.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.contact-sources') ? 'active' : '' }}">
                        <i class="bi bi-broadcast"></i> {{ __('مصادر التواصل') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        @endif

        {{-- ● المبيعات --}}
        @php
            $salesOpen = $groupActive(['crm.bookings','crm.tracking','crm.calculator.index']);
            $canSales = $currentUser->hasAnyPermission(['bookings.view','tracking.view','calculator.view']);
        @endphp
        @if($canSales)
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle {{ $salesOpen ? 'active' : '' }}"
                    onclick="toggleGroup('g-sales')">
                <i class="bi bi-bag"></i>
                <span>{{ __('المبيعات') }}</span>
                <i class="bi bi-chevron-{{ $salesOpen ? 'up' : 'down' }} crm-chevron"></i>
            </button>
            <ul id="g-sales" class="crm-sub-list {{ $salesOpen ? 'open' : '' }}">
                @can('bookings.view')
                <li>
                    <a href="{{ route('crm.bookings.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.bookings') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> {{ __('الطلبات') }}
                    </a>
                </li>
                @endcan
                @can('tracking.view')
                <li>
                    <a href="{{ route('crm.tracking.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.tracking') ? 'active' : '' }}">
                        <i class="bi bi-kanban"></i> {{ __('تتبع الحالات') }}
                    </a>
                </li>
                @endcan
                @can('calculator.view')
                <li>
                    <a href="{{ route('crm.calculator.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.calculator.index') ? 'active' : '' }}">
                        <i class="bi bi-calculator"></i> {{ __('إعدادات الحاسبة') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        @endif

        {{-- ● الفرق والمهام --}}
        @php
            $teamOpen = $groupActive(['crm.tasks','crm.employees','crm.roles']);
            $canTeam = $currentUser->hasAnyPermission(['tasks.view','users.view','roles.manage']);
        @endphp
        @if($canTeam)
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle {{ $teamOpen ? 'active' : '' }}"
                    onclick="toggleGroup('g-team')">
                <i class="bi bi-people-fill"></i>
                <span>{{ __('الفرق والمهام') }}</span>
                <i class="bi bi-chevron-{{ $teamOpen ? 'up' : 'down' }} crm-chevron"></i>
            </button>
            <ul id="g-team" class="crm-sub-list {{ $teamOpen ? 'open' : '' }}">
                @can('tasks.view')
                <li>
                    <a href="{{ route('crm.tasks.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.tasks') ? 'active' : '' }}">
                        <i class="bi bi-check2-square"></i> {{ __('المهام') }}
                    </a>
                </li>
                @endcan
                @can('users.view')
                <li>
                    <a href="{{ route('crm.employees.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.employees') ? 'active' : '' }}">
                        <i class="bi bi-person-workspace"></i> {{ __('الموظفين') }}
                    </a>
                </li>
                @endcan
                @can('roles.manage')
                <li>
                    <a href="{{ route('crm.roles.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.roles') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i> {{ __('الأدوار والصلاحيات') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        @endif

        {{-- ● التقارير --}}
        @can('reports.view')
        <div class="crm-nav-section">
            <a href="{{ route('crm.reports.bookings') }}"
               class="crm-nav-link {{ str_starts_with($r,'crm.reports') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>{{ __('التقارير') }}</span>
            </a>
        </div>
        @endcan

        {{-- ● المحتوى والإعدادات --}}
        @php
            $canContent = $currentUser->hasAnyPermission(['blog.view','partners.view','testimonials.view','translations.view']);
            $contentOpen = $canContent && $groupActive(['crm.blog','crm.settings.partners','crm.settings.testimonials','crm.translations']);
        @endphp
        @if($canContent)
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle {{ $contentOpen ? 'active' : '' }}"
                    onclick="toggleGroup('g-content')">
                <i class="bi bi-journal-richtext"></i>
                <span>{{ __('المحتوى') }}</span>
                <i class="bi bi-chevron-{{ $contentOpen ? 'up' : 'down' }} crm-chevron"></i>
            </button>
            <ul id="g-content" class="crm-sub-list {{ $contentOpen ? 'open' : '' }}">
                @can('blog.view')
                <li>
                    <a href="{{ route('crm.blog.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.blog') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i> {{ __('المدونة') }}
                    </a>
                </li>
                @endcan
                @can('partners.view')
                <li>
                    <a href="{{ route('crm.settings.partners.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.settings.partners') ? 'active' : '' }}">
                        <i class="bi bi-hand-thumbs-up"></i> {{ __('شركاء النجاح') }}
                    </a>
                </li>
                @endcan
                @can('testimonials.view')
                <li>
                    <a href="{{ route('crm.settings.testimonials.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.settings.testimonials') ? 'active' : '' }}">
                        <i class="bi bi-chat-quote"></i> {{ __('آراء العملاء') }}
                    </a>
                </li>
                @endcan
                @can('translations.view')
                <li>
                    <a href="{{ route('crm.translations.index') }}"
                       class="crm-sub-link {{ str_starts_with($r,'crm.translations') ? 'active' : '' }}">
                        <i class="bi bi-translate"></i> {{ __('إدارة الترجمة') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        @endif

    </nav>

    {{-- Footer --}}
    <div class="crm-sidebar-footer">
        @can('settings.manage')
        <a href="{{ route('crm.settings.general') }}"
           class="crm-nav-link {{ str_starts_with($r,'crm.settings.general') || str_starts_with($r,'crm.settings') && !($canContent ?? false) ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>{{ __('الإعدادات') }}</span>
        </a>
        @endcan
        <form action="{{ route('crm.logout') }}" method="POST">
            @csrf
            <button type="submit" class="crm-nav-link w-100" style="background:none;border:none;cursor:pointer;color:#1a3163;">
                <i class="bi bi-box-arrow-right"></i>
                <span>{{ __('تسجيل الخروج') }}</span>
            </button>
        </form>
    </div>

</aside>

<script>
function toggleGroup(id) {
    const list = document.getElementById(id);
    const btn  = list.previousElementSibling;
    const icon = btn.querySelector('.crm-chevron');
    const isOpen = list.classList.contains('open');
    // Close all others
    document.querySelectorAll('.crm-sub-list.open').forEach(el => {
        el.classList.remove('open');
        const b = el.previousElementSibling;
        if (b) { b.classList.remove('active'); const ic = b.querySelector('.crm-chevron'); if(ic) ic.className = 'bi bi-chevron-down crm-chevron'; }
    });
    if (!isOpen) {
        list.classList.add('open');
        btn.classList.add('active');
        if (icon) icon.className = 'bi bi-chevron-up crm-chevron';
    }
}
</script>
