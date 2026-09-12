@extends('partials.Layouts.crm-master')
@section('title', __('إعدادات SEO') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="mb-2">
            <h4 class="mb-1 fw-bold">{{ __('إعدادات تحسين محركات البحث (SEO)') }}</h4>
            <p class="text-muted mb-0 small">{{ __('إدارة الكلمات المفتاحية والأوصاف لمحركات البحث وأدوات التتبع') }}</p>
        </div>

        @include('partials.settings-subnav')

        @if(session('success'))
            <div   >
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('crm.settings.update') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4 rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="card-title mb-0 fw-bold">{{ __('بيانات الميتا (Meta Tags)') }}</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">{{ __('العنوان الافتراضي للموقع (Meta Title)') }}</label>
                                <input type="text" name="meta_title" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['meta_title'] ?? '' }}">
                                <small class="text-muted">{{ __('يظهر في عناوين صفحات المتصفح ومحركات البحث') }}</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">{{ __('الوصف الافتراضي (Meta Description)') }}</label>
                                <textarea name="meta_description" class="form-control bg-light border-0 shadow-none" rows="4">{{ $settings['meta_description'] ?? '' }}</textarea>
                                <small class="text-muted">{{ __('وصف مختصر يظهر في نتائج البحث (يفضل ألا يتجاوز 160 حرفاً)') }}</small>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted">{{ __('الكلمات المفتاحية (Keywords)') }}</label>
                                <textarea name="meta_keywords" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="{{ __('سيارات، بيع سيارات، تقسيط...') }}">{{ $settings['meta_keywords'] ?? '' }}</textarea>
                                <small class="text-muted">{{ __('افصل بين الكلمات بفاصلة (,) ') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm mb-4 rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="card-title mb-0 fw-bold">{{ __('تحليلات وأدوات التتبع (Tracking Pixels)') }}</h5>
                            <small class="text-muted">{{ __('ربط وتتبع الحملات الإعلانية ومعدلات التحويل بدقة') }}</small>
                        </div>
                        <div class="card-body p-4 space-y-4">
                            @php
                                $gtmId = $settings['google_tag_manager_id'] ?? 'GTM-PLPF4RXN';
                                $pixelId = $settings['meta_pixel_id'] ?? '1391587686296113';
                                $tiktokId = $settings['tiktok_pixel_id'] ?? 'DAGKF8BC77UC8FLJU9TG';
                                $tiktokToken = $settings['tiktok_access_token'] ?? '334d37ec9ab16637528708e657d3b5ca63353fa5';
                                $snapId = $settings['snapchat_pixel_id'] ?? 'a29bd8a6-9047-45bc-b184-dd10d94233f7';
                                $snapToken = $settings['snapchat_api_token'] ?? '';
                                $gaId = $settings['google_analytics_id'] ?? '';
                            @endphp

                            {{-- 1. Google Tag Manager --}}
                            <div class="mb-3 pb-3 border-bottom">
                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-tags-fill text-primary" style="color: #4285F4 !important;"></i>
                                    {{ __('معرف Google Tag Manager (GTM)') }}
                                    @if($gtmId)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('مفعل') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-dash-circle me-1"></i>{{ __('غير مفعل') }}
                                        </span>
                                    @endif
                                </label>
                                <input type="text" name="google_tag_manager_id"
                                    class="form-control bg-light border-0 shadow-none py-2 font-monospace"
                                    placeholder="GTM-XXXXXXX"
                                    value="{{ $gtmId }}" dir="ltr">
                                <small class="text-muted">{{ __('مثال: GTM-PLPF4RXN') }}</small>
                            </div>

                            {{-- 2. Meta Pixel (Facebook) --}}
                            <div class="mb-3 pb-3 border-bottom">
                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-facebook text-primary" style="color:#1877F2 !important;"></i>
                                    {{ __('معرف Meta Pixel (Facebook)') }}
                                    @if($pixelId)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('مفعل') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-dash-circle me-1"></i>{{ __('غير مفعل') }}
                                        </span>
                                    @endif
                                </label>
                                <input type="text" name="meta_pixel_id"
                                    class="form-control bg-light border-0 shadow-none py-2 font-monospace"
                                    placeholder="1391587686296113"
                                    value="{{ $pixelId }}" dir="ltr">
                                <small class="text-muted">{{ __('مثال: 1391587686296113') }}</small>
                            </div>

                            {{-- 3. TikTok Pixel --}}
                            <div class="mb-3 pb-3 border-bottom">
                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-tiktok text-dark"></i>
                                    {{ __('معرف TikTok Pixel') }}
                                    @if($tiktokId)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('مفعل') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-dash-circle me-1"></i>{{ __('غير مفعل') }}
                                        </span>
                                    @endif
                                </label>
                                <input type="text" name="tiktok_pixel_id"
                                    class="form-control bg-light border-0 shadow-none py-2 font-monospace mb-2"
                                    placeholder="DAGKF8BC77UC8FLJU9TG"
                                    value="{{ $tiktokId }}" dir="ltr">

                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-1 mt-2">
                                    <i class="bi bi-key-fill text-warning"></i>
                                    {{ __('توكن ربط TikTok Events API (اختياري)') }}
                                </label>
                                <input type="password" name="tiktok_access_token"
                                    class="form-control bg-light border-0 shadow-none py-2 font-monospace"
                                    placeholder="334d37ec9ab16637..."
                                    value="{{ $tiktokToken }}" dir="ltr">
                            </div>

                            {{-- 4. Snapchat Pixel --}}
                            <div class="mb-3 pb-3 border-bottom">
                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-snapchat text-warning" style="color: #FFFC00 !important; -webkit-text-stroke: 1px #000;"></i>
                                    {{ __('معرف Snapchat Pixel') }}
                                    @if($snapId)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('مفعل') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-dash-circle me-1"></i>{{ __('غير مفعل') }}
                                        </span>
                                    @endif
                                </label>
                                <input type="text" name="snapchat_pixel_id"
                                    class="form-control bg-light border-0 shadow-none py-2 font-monospace mb-2"
                                    placeholder="a29bd8a6-9047-45bc-b184-dd10d94233f7"
                                    value="{{ $snapId }}" dir="ltr">

                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-1 mt-2">
                                    <i class="bi bi-shield-lock-fill text-info"></i>
                                    {{ __('توكن Snapchat CAPI Token (اختياري)') }}
                                </label>
                                <textarea name="snapchat_api_token"
                                    rows="2"
                                    class="form-control bg-light border-0 shadow-none py-2 font-monospace small"
                                    placeholder="eyJhbGciOiJIUzI1NiIs..."
                                    dir="ltr">{{ $snapToken }}</textarea>
                            </div>

                            {{-- 5. Google Analytics 4 (GA4) --}}
                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-google text-danger"></i>
                                    {{ __('معرف Google Analytics (GA4)') }}
                                    @if($gaId)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('مفعل') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-dash-circle me-1"></i>{{ __('غير مفعل') }}
                                        </span>
                                    @endif
                                </label>
                                <input type="text" name="google_analytics_id"
                                    class="form-control bg-light border-0 shadow-none py-2 font-monospace"
                                    placeholder="G-XXXXXXXXXX"
                                    value="{{ $gaId }}" dir="ltr">
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4">
                    @can('settings.manage')
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm">
                            <i class="bi bi-save me-1"></i> {{ __('حفظ الإعدادات') }}
                        </button>
                    @endcan
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
