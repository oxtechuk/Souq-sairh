@extends('partials.Layouts.crm-master')
@section('title', __('الإعدادات العامة') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="mb-2 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-1 fw-bold"> {{ __('الإعدادات العامة للموقع') }}</h4>
                <p class="text-muted mb-0 small">{{ __('إدارة معلومات الموقع الأساسية، بيانات التواصل، والمظهر العام') }}</p>
            </div>
        </div>

        @include('partials.settings-subnav')

        @if(session('success'))
            <div   >
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('crm.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-lg-9">
                    {{-- Navigation Tabs --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-body p-0">
                            <ul class="nav nav-pills nav-fill bg-light p-2" id="settingsTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold py-3 rounded-3" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                        <i class="bi bi-info-circle me-2"></i> {{ __('المعلومات الأساسية') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold py-3 rounded-3" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab">
                                        <i class="bi bi-palette me-2"></i> {{ __('المظهر والواجهة') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold py-3 rounded-3" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                                        <i class="bi bi-telephone me-2"></i> {{ __('بيانات التواصل') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold py-3 rounded-3" id="bento-tab" data-bs-toggle="tab" data-bs-target="#bento" type="button" role="tab">
                                        <i class="bi bi-grid-3x3-gap me-2"></i> {{ __('المعرض الرئيسي') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold py-3 rounded-3" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button" role="tab">
                                        <i class="bi bi-play-circle me-2"></i> {{ __('هيرو الرئيسية') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold py-3 rounded-3" id="twilio-tab" data-bs-toggle="tab" data-bs-target="#twilio" type="button" role="tab">
                                        <i class="bi bi-whatsapp me-2"></i> {{ __('واتساب Twilio') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold py-3 rounded-3" id="distribution-tab" data-bs-toggle="tab" data-bs-target="#distribution" type="button" role="tab">
                                        <i class="bi bi-diagram-3 me-2"></i> {{ __('التوزيع التلقائي') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Tab Content --}}
                    <div class="tab-content" id="settingsTabContent">
                        {{-- Tab 1: Basic Info --}}
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">{{ __('اسم الموقع (بالعربية)') }}</label>
                                            <input type="text" name="site_name[ar]" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['site_name']['ar'] ?? '' }}" placeholder="مثال: جي آر موتورز">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">{{ __('اسم الموقع (بالإنجليزية)') }}</label>
                                            <input type="text" name="site_name[en]" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['site_name']['en'] ?? '' }}" placeholder="e.g.: GR Motors">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold small text-muted">{{ __('نص التذييل (Footer Text)') }}</label>
                                            <textarea name="footer_text" class="form-control bg-light border-0 shadow-none" rows="4" placeholder="{{ __('اكتب النص الذي يظهر في أسفل جميع الصفحات...') }}">{{ $settings['footer_text'] ?? '' }}</textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold small text-muted">{{ __('سياسة الخصوصية') }}</label>
                                            <textarea name="privacy_policy" class="form-control bg-light border-0 shadow-none" rows="8" placeholder="{{ __('اكتب نص سياسة الخصوصية...') }}">{{ $settings['privacy_policy'] ?? '' }}</textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold small text-muted">{{ __('الشروط والأحكام') }}</label>
                                            <textarea name="terms_conditions" class="form-control bg-light border-0 shadow-none" rows="8" placeholder="{{ __('اكتب نص الشروط والأحكام...') }}">{{ $settings['terms_conditions'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 2: Appearance (including Logos and Breadcrumb BG) --}}
                        <div class="tab-pane fade" id="appearance" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                                            <h6 class="fw-bold mb-0 text-dark">{{ __('الشعار والأيقونة') }}</h6>
                                        </div>
                                        <div class="card-body p-4 pt-2">
                                            <div class="mb-4">
                                                <label class="form-label fw-bold small text-muted d-block mb-3">{{ __('شعار الموقع (Logo)') }}</label>
                                                <div class="p-3 bg-light rounded-4 mb-3 border border-dashed text-center">
                                                    @if(isset($settings['site_logo']))
                                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
                                                    @else
                                                        <i class="bi bi-image fs-2 opacity-25"></i>
                                                    @endif
                                                </div>
                                                <input type="file" name="site_logo" class="form-control bg-light border-0 shadow-none">
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-bold small text-muted d-block mb-3">{{ __('أيقونة الموقع (Favicon)') }}</label>
                                                <div class="p-3 bg-light rounded-4 mb-3 border border-dashed text-center">
                                                    @if(isset($settings['site_favicon']))
                                                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" width="32">
                                                    @else
                                                        <i class="bi bi-app-indicator fs-2 opacity-25"></i>
                                                    @endif
                                                </div>
                                                <input type="file" name="site_favicon" class="form-control bg-light border-0 shadow-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                                            <h6 class="fw-bold mb-0 text-dark">{{ __('خلفية عناوين الصفحات (Breadcrumb BG)') }}</h6>
                                        </div>
                                        <div class="card-body p-4 pt-2 text-center">
                                            <label class="form-label fw-bold small text-muted d-block mb-3 text-start">{{ __('صورة الخلفية الموحدة') }}</label>
                                            <div class="p-2 bg-dark rounded-4 mb-3 border border-dashed d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 200px; background: #222;">
                                                @if(isset($settings['breadcrumb_bg']))
                                                    <img src="{{ asset('storage/' . $settings['breadcrumb_bg']) }}" alt="Breadcrumb" class="img-fluid w-100 object-fit-cover rounded-3" style="max-height: 180px;">
                                                @else
                                                    <div class="text-white opacity-50">
                                                        <i class="bi bi-layout-text-window-reverse fs-1 d-block mb-2"></i>
                                                        <span class="small">{{ __('لم يتم اختيار صورة بعد') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" name="breadcrumb_bg" class="form-control bg-light border-0 shadow-none">
                                            <div class="mt-3 p-3 bg-info-subtle text-info rounded-3 text-start small">
                                                <i class="bi bi-info-circle-fill me-1"></i>
                                                {{ __('تظهر هذه الصورة كخلفية لاسم الصفحة في صفحات (من نحن، العروض، المدونة، إلخ).') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                                            <h6 class="fw-bold mb-0 text-dark">{{ __('صورة لماذا نحن (About Page)') }}</h6>
                                        </div>
                                        <div class="card-body p-4 pt-2 text-center">
                                            <label class="form-label fw-bold small text-muted d-block mb-3 text-start">{{ __('صورة قسم "لماذا نحن" في صفحة من نحن') }}</label>
                                            <div class="p-3 bg-light rounded-4 mb-3 border border-dashed d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 160px;">
                                                @if(isset($settings['about_why_choose_image']))
                                                    <img src="{{ asset('storage/' . $settings['about_why_choose_image']) }}" alt="Why Choose Us" class="img-fluid w-100 object-fit-cover rounded-3" style="max-height: 160px;">
                                                @else
                                                    <div class="text-muted opacity-50">
                                                        <i class="bi bi-image fs-1 d-block mb-2"></i>
                                                        <span class="small">{{ __('لم يتم اختيار صورة بعد') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" name="about_why_choose_image" class="form-control bg-light border-0 shadow-none" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 3: Contact Info --}}
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">{{ __('البريد الإلكتروني الرسمي') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="contact_email" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['contact_email'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">{{ __('رقم الهاتف الرئيسي') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="bi bi-telephone"></i></span>
                                                <input type="text" name="contact_phone" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['contact_phone'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">{{ __('رقم الواتساب') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="bi bi-whatsapp"></i></span>
                                                <input type="text" name="contact_whatsapp" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['contact_whatsapp'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">{{ __('العنوان بالتفصيل') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt"></i></span>
                                                <input type="text" name="contact_address" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['contact_address'] ?? '' }}">
                                            </div>
                                        </div>

                                        {{-- Social Media Links --}}
                                        <div class="col-12 mt-5">
                                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-share me-2"></i>{{ __('روابط التواصل الاجتماعي') }}</h6>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addSocialRow()">
                                                    <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة رابط جديد') }}
                                                </button>
                                            </div>
                                            
                                            <div id="social-container" class="d-flex flex-column gap-3">
                                                @foreach($socialMedia as $idx => $social)
                                                <div class="social-row d-flex align-items-center gap-2 p-3 bg-light rounded-4 border border-light-subtle" id="social-row-{{ $idx }}">
                                                    <div class="input-group w-auto">
                                                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-bootstrap"></i></span>
                                                        <input type="text" name="social_icon[]" class="form-control border-0 shadow-none" placeholder="{{ __('أيقونة (مثل: bi-facebook)') }}" value="{{ $social['icon'] ?? '' }}" required style="max-width: 180px;">
                                                    </div>
                                                    <div class="d-flex align-items-center bg-white rounded px-2">
                                                        <input type="color" name="social_color[]" class="form-control form-control-color border-0 p-0 shadow-none bg-transparent" value="{{ $social['color'] ?? '#333333' }}" title="{{ __('اختر لون الأيقونة') }}" style="width: 32px; height: 32px;">
                                                    </div>
                                                    <div class="input-group flex-grow-1">
                                                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-link-45deg"></i></span>
                                                        <input type="url" name="social_link[]" class="form-control border-0 shadow-none text-start" dir="ltr" placeholder="https://..." value="{{ $social['link'] ?? '' }}" required>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-2" onclick="removeSocialRow({{ $idx }})" title="{{ __('حذف') }}">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                            
                                            <div id="no-social-msg" class="text-center py-4 bg-light rounded-4 border border-dashed {{ count($socialMedia) > 0 ? 'd-none' : '' }}">
                                                <i class="bi bi-diagram-3 fs-2 text-muted opacity-50 mb-2 d-block"></i>
                                                <span class="text-muted small">{{ __('لم يتم إضافة حسابات تواصل اجتماعي بعد.') }}</span>
                                            </div>
                                            
                                            <div class="mt-3 p-3 bg-light rounded-3 text-muted small">
                                                <i class="bi bi-info-circle-fill me-1 text-primary"></i>
                                                {{ __('استخدم كلاسات Bootstrap Icons للأيقونات. مثال:') }} 
                                                <code class="ms-1 px-2 py-1 bg-white rounded">bi-facebook</code>, 
                                                <code class="ms-1 px-2 py-1 bg-white rounded">bi-twitter-x</code>, 
                                                <code class="ms-1 px-2 py-1 bg-white rounded">bi-instagram</code>, 
                                                <code class="ms-1 px-2 py-1 bg-white rounded">bi-snapchat</code>, 
                                                <code class="ms-1 px-2 py-1 bg-white rounded">bi-tiktok</code>.
                                                <a href="https://icons.getbootstrap.com/" target="_blank" class="ms-2 text-primary text-decoration-none"><i class="bi bi-box-arrow-up-right me-1"></i>{{ __('تصفح الأيقونات') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 4: Bento Gallery & Main Gallery --}}
                        <div class="tab-pane fade" id="bento" role="tabpanel">
                            <div class="row g-4">
                                {{-- Bento Cars --}}
                                <div class="col-md-12">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                        <div class="card-header bg-white border-0 pt-4 px-4">
                                            <h6 class="fw-bold mb-0 text-dark">{{ __('تخصيص معرض Bento في الرئيسية') }}</h6>
                                        </div>
                                        <div class="card-body p-4 pt-2">
                                            <label class="form-label fw-bold small text-muted mb-3">{{ __('اختر من 3 إلى 5 سيارات مميزة لعرضها') }}</label>
                                            <select name="bento_cars[]" class="form-select bg-light border-0 shadow-none" multiple="multiple" style="min-height: 250px;">
                                                @foreach($cars as $car)
                                                    <option value="{{ $car->id }}" {{ in_array($car->id, $bentoCars) ? 'selected' : '' }} class="p-2 border-bottom border-light">
                                                        {{ $car->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Main Gallery Upload --}}
                                <div class="col-md-12">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                                            <h6 class="fw-bold mb-0 text-dark">{{ __('صور المعرض الرئيسي (صفحة من نحن)') }}</h6>
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ count($settings['main_gallery'] ?? []) }} {{ __('صورة') }}</span>
                                        </div>
                                        <div class="card-body p-4 pt-2">
                                            <div class="mb-4">
                                                <label class="form-label fw-bold small text-muted mb-3">{{ __('إضافة صور جديدة') }}</label>
                                                <input type="file" name="main_gallery[]" class="form-control bg-light border-0 shadow-none" multiple accept="image/*">
                                                <div class="mt-2 small text-muted">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    {{ __('يمكنك اختيار صور متعددة في وقت واحد.') }}
                                                </div>
                                            </div>

                                            @if(isset($settings['main_gallery']) && !empty($settings['main_gallery']))
                                                <label class="form-label fw-bold small text-muted mb-3">{{ __('الصور الحالية') }}</label>
                                                <div class="row g-3">
                                                    @php
                                                        $gallery = is_array($settings['main_gallery']) ? $settings['main_gallery'] : (json_decode($settings['main_gallery'], true) ?: []);
                                                    @endphp
                                                    @foreach($gallery as $img)
                                                        <div class="col-6 col-md-4 col-lg-3">
                                                            <div class="position-relative group">
                                                                <div class="rounded-4 overflow-hidden border shadow-sm" style="height: 120px;">
                                                                    <img src="{{ asset('storage/' . $img) }}" class="w-100 h-100 object-fit-cover">
                                                                </div>
                                                @can('settings.manage')
                                                <button type="submit" name="delete_gallery_image" value="{{ $img }}" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 shadow-sm" onclick="return confirm('{{ __('هل أنت متأكد من حذف هذه الصورة؟') }}')" style="width: 28px; height: 28px; padding: 0;">
                                                    <i class="bi bi-x small"></i>
                                                </button>
                                                @endcan
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-5 bg-light rounded-4 border border-dashed">
                                                    <i class="bi bi-images fs-1 text-muted opacity-25 d-block mb-2"></i>
                                                    <span class="text-muted">{{ __('لا توجد صور في المعرض حالياً.') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 5: Hero Section --}}
                        <div class="tab-pane fade" id="hero" role="tabpanel">
                            <div class="row g-4">

                                {{-- Hero Title & Subtitle --}}
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-fonts text-primary me-2"></i>{{ __('نصوص الهيرو') }}</h6>
                                        </div>
                                        <div class="card-body p-4 pt-2">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted">{{ __('العنوان الرئيسي') }}</label>
                                                    <input type="text" name="store_home_hero[title]" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['store_home_hero']['title'] ?? 'تخيّر موتِرك..' }}" placeholder="تخيّر موتِرك..">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted">{{ __('العنوان الفرعي') }}</label>
                                                    <input type="text" name="store_home_hero[subtitle]" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['store_home_hero']['subtitle'] ?? 'وحنّا نيسّر لك التمويل' }}" placeholder="وحنّا نيسّر لك التمويل">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-3">
                                        <div class="col-12">
                                            <label class="form-label fw-bold small text-muted">{{ __('وصف الهيرو') }}</label>
                                            <textarea name="store_home_description" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="{{ __('لديك التمويلات بين يديك مع خطط تمويل مرنة تناسب ميزانيتك...') }}">{{ $settings['store_home_description'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hero Video --}}
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4">
                                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-play-circle-fill text-danger me-2"></i>{{ __('فيديو الهيرو') }}</h6>
                                            <p class="text-muted small mb-0 mt-1">{{ __('يظهر هذا الفيديو في الجزء الأيمن من الهيرو الرئيسي بدلاً من الصورة الثابتة') }}</p>
                                        </div>
                                        <div class="card-body p-4 pt-2">
                                            @if(isset($settings['hero_video']))
                                            <div class="mb-3 rounded-4 overflow-hidden border" style="max-height:200px;">
                                                <video src="{{ asset('storage/' . $settings['hero_video']) }}" class="w-100" style="max-height:200px;object-fit:cover;" muted controls></video>
                                            </div>
                                            @else
                                            <div class="mb-3 bg-light rounded-4 d-flex align-items-center justify-content-center border border-dashed" style="min-height:120px;" id="videoEmptyPlaceholder">
                                                <div class="text-muted text-center"><i class="bi bi-camera-video fs-2 d-block mb-1 opacity-50"></i><span class="small">{{ __('لم يُرفع فيديو بعد') }}</span></div>
                                            </div>
                                            @endif

                                            {{-- Custom Video Upload Zone --}}
                                            <div id="videoUploadZone" class="video-upload-zone" onclick="document.getElementById('heroVideoInput').click()">
                                                <div class="upload-zone-inner">
                                                    <div class="upload-icon-wrap">
                                                        <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                                                    </div>
                                                    <div class="upload-text-wrap">
                                                        <span class="upload-title">{{ __('اضغط لاختيار فيديو') }}</span>
                                                        <span class="upload-sub">MP4, WebM &mdash; {{ __('حجم أقصى 500MB') }}</span>
                                                    </div>
                                                    <button type="button" class="upload-btn">{{ __('اختر ملف') }}</button>
                                                </div>
                                            </div>

                                            {{-- Hidden real input --}}
                                            <input type="file" name="hero_video" id="heroVideoInput" class="d-none" accept="video/mp4,video/webm">

                                            {{-- File Info + Progress (hidden by default) --}}
                                            <div id="videoUploadInfo" class="video-upload-info d-none">
                                                <div class="file-meta">
                                                    <div class="file-icon"><i class="bi bi-film"></i></div>
                                                    <div class="file-details">
                                                        <div class="file-name" id="videoFileName">...</div>
                                                        <div class="file-size" id="videoFileSize">...</div>
                                                    </div>
                                                    <button type="button" class="file-remove" id="videoRemoveBtn" title="{{ __('إلغاء') }}">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </button>
                                                </div>
                                                <div class="progress-wrap mt-2">
                                                    <div class="progress-bar-bg">
                                                        <div class="progress-bar-fill" id="videoProgressBar"></div>
                                                    </div>
                                                    <div class="progress-labels">
                                                        <span id="videoProgressText" class="progress-pct">0%</span>
                                                        <span id="videoProgressStatus" class="progress-status">{{ __('جاهز للرفع') }}</span>
                                                    </div>
                                                </div>
                                                @can('settings.manage')
                                                <button type="submit" id="saveVideoBtn" class="btn btn-success w-100 mt-3 d-none fw-bold" style="border-radius: 12px; padding: 10px;">
                                                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>{{ __('حفظ الفيديو الآن') }}
                                                </button>
                                                @endcan
                                            </div>

                                            <div class="mt-2 small text-muted"><i class="bi bi-info-circle me-1"></i>{{ __('الصيغ المدعومة: MP4 أو WebM — يُفضل دقة 1280×720 أو أعلى') }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ad 1 --}}
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-megaphone-fill text-warning me-2"></i>{{ __('الإعلان الأول') }}</h6>
                                        </div>
                                        <div class="card-body p-4 pt-2">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">{{ __('صورة الإعلان') }}</label>
                                                @if(isset($settings['hero_ad_1_image']))
                                                <div class="mb-2 rounded-3 overflow-hidden border" style="height:130px;">
                                                    <img src="{{ asset('storage/' . $settings['hero_ad_1_image']) }}" class="w-100 h-100" style="object-fit:cover;">
                                                </div>
                                                @else
                                                <div class="mb-2 bg-light rounded-3 border border-dashed d-flex align-items-center justify-content-center" style="height:100px;">
                                                    <span class="text-muted small"><i class="bi bi-image me-1"></i>{{ __('لا توجد صورة') }}</span>
                                                </div>
                                                @endif
                                                <input type="file" name="hero_ad_1_image" class="form-control bg-light border-0 shadow-none" accept="image/*">
                                            </div>
                                            <div>
                                                <label class="form-label fw-bold small text-muted">{{ __('رابط الإعلان (URL)') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-link-45deg"></i></span>
                                                    <input type="url" name="hero_ad_1_link" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['hero_ad_1_link'] ?? '' }}" placeholder="https://...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ad 2 --}}
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-megaphone-fill text-info me-2"></i>{{ __('الإعلان الثاني') }}</h6>
                                        </div>
                                        <div class="card-body p-4 pt-2">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">{{ __('صورة الإعلان') }}</label>
                                                @if(isset($settings['hero_ad_2_image']))
                                                <div class="mb-2 rounded-3 overflow-hidden border" style="height:130px;">
                                                    <img src="{{ asset('storage/' . $settings['hero_ad_2_image']) }}" class="w-100 h-100" style="object-fit:cover;">
                                                </div>
                                                @else
                                                <div class="mb-2 bg-light rounded-3 border border-dashed d-flex align-items-center justify-content-center" style="height:100px;">
                                                    <span class="text-muted small"><i class="bi bi-image me-1"></i>{{ __('لا توجد صورة') }}</span>
                                                </div>
                                                @endif
                                                <input type="file" name="hero_ad_2_image" class="form-control bg-light border-0 shadow-none" accept="image/*">
                                            </div>
                                            <div>
                                                <label class="form-label fw-bold small text-muted">{{ __('رابط الإعلان (URL)') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-link-45deg"></i></span>
                                                    <input type="url" name="hero_ad_2_link" class="form-control bg-light border-0 shadow-none py-2" value="{{ $settings['hero_ad_2_link'] ?? '' }}" placeholder="https://...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Tab 6: Twilio WhatsApp Integration --}}
                        <div class="tab-pane fade" id="twilio" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-body p-4">

                                    {{-- Header --}}
                                    <div class="mb-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">
                                                <i class="bi bi-gear-wide-connected me-2 text-primary"></i>{{ __('إعدادات Twilio WhatsApp API') }}
                                            </h6>
                                            <p class="text-muted small mb-0">{{ __('إعدادات الربط التقني لإرسال الرسائل التلقائية عبر Twilio') }}</p>
                                        </div>
                                        <div>
                                            @if(!empty($settings['twilio_sid']) && !empty($settings['twilio_auth_token']))
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2" id="twilio-status-badge">
                                                    <i class="bi bi-check-circle-fill me-1"></i> {{ __('مفعل') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2" id="twilio-status-badge">
                                                    <i class="bi bi-dash-circle me-1"></i> {{ __('غير معدّ') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Info Alert --}}
                                    <div class="p-3 bg-primary-subtle text-primary rounded-4 small mb-4 border border-primary-subtle d-flex gap-3">
                                        <i class="bi bi-info-circle-fill fs-5 flex-shrink-0 mt-1"></i>
                                        <div>
                                            <strong>{{ __('تنبيه:') }}</strong> {{ __('هذه البيانات حساسة وتُستخدم لإرسال الإشعارات التلقائية (مثل إشعارات الحجز الجديد) للعملاء عبر الواتساب.') }}
                                            <a href="https://www.twilio.com/console" target="_blank" class="text-primary fw-bold text-decoration-none d-inline-block mt-1">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>{{ __('انتقل إلى لوحة تحكم Twilio') }}
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Credentials Card --}}
                                    <div class="row g-4">
                                        <div class="col-lg-7">
                                            <div class="p-4 bg-light rounded-4 border">
                                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-key me-2 text-primary"></i>{{ __('بيانات الاعتماد') }}</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small text-muted">{{ __('Account SID') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-0"><i class="bi bi-fingerprint text-muted"></i></span>
                                                            <input type="text" name="twilio_sid" class="form-control bg-white border-0 shadow-none py-2" value="{{ $settings['twilio_sid'] ?? '' }}" placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small text-muted">{{ __('Auth Token') }}</label>
                                                        <div class="input-group bg-white rounded-3 overflow-hidden border">
                                                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-shield-lock text-muted"></i></span>
                                                            <input type="password" name="twilio_auth_token" id="twilio_auth_token" class="form-control bg-transparent border-0 shadow-none py-2" value="{{ $settings['twilio_auth_token'] ?? '' }}" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                                                            <button class="btn border-0 text-muted px-3" type="button" onclick="togglePassword('twilio_auth_token')">
                                                                <i class="bi bi-eye" id="twilio_auth_token_icon"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold small text-muted">{{ __('رقم واتساب المرسل (From)') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-0"><i class="bi bi-whatsapp text-muted"></i></span>
                                                            <input type="text" name="twilio_from" class="form-control bg-white border-0 shadow-none py-2 text-start" dir="ltr" value="{{ $settings['twilio_from'] ?? '' }}" placeholder="whatsapp:+14155238886">
                                                        </div>
                                                        <div class="mt-2 text-muted small">
                                                            <i class="bi bi-question-circle me-1"></i>
                                                            {{ __('يجب أن يبدأ بـ whatsapp: متبوعاً بالرقم بالتنسيق الدولي.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Connection Test Card --}}
                                        <div class="col-lg-5">
                                            <div class="p-4 bg-light rounded-4 border h-100 d-flex flex-column">
                                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-activity me-2 text-primary"></i>{{ __('حالة الاتصال') }}</h6>
                                                <div class="flex-grow-1 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <div class="d-flex align-items-center gap-2 mb-3">
                                                            @if(!empty($settings['twilio_sid']) && !empty($settings['twilio_auth_token']))
                                                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                                    <i class="bi bi-check-circle-fill me-1"></i> {{ __('البيانات محفوظة') }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                                    <i class="bi bi-dash-circle me-1"></i> {{ __('غير مكتملة') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div id="twilio-status-msg" class="small text-muted mb-3">
                                                            {{ __('اضغط "اختبار الربط" للتحقق من صحة بيانات Twilio.') }}
                                                        </div>
                                                    </div>
                                                    <button type="button" id="test-twilio-btn" class="btn btn-outline-primary w-100 rounded-pill fw-bold py-2 border-2">
                                                        <i class="bi bi-send-check me-1"></i> {{ __('اختبار الربط') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Tab 6: التوزيع التلقائي --}}
                        <div class="tab-pane fade" id="distribution" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4"><i class="bi bi-diagram-3 text-primary me-2"></i>{{ __('التوزيع التلقائي للطلبات') }}</h5>
                                    <p class="text-muted small mb-4">{{ __('حدد طريقة التوزيع التلقائي للطلبات الجديدة على الموظفين.') }}</p>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">{{ __('طريقة التوزيع') }}</label>
                                            <select name="order_distribution_method" class="form-control bg-light border-0 shadow-none">
                                                <option value="disabled" {{ ($settings['order_distribution_method'] ?? 'disabled') === 'disabled' ? 'selected' : '' }}>{{ __('معطل') }}</option>
                                                <option value="round_robin" {{ ($settings['order_distribution_method'] ?? '') === 'round_robin' ? 'selected' : '' }}>{{ __('توزيع دوري (Round Robin)') }}</option>
                                                <option value="least_loaded" {{ ($settings['order_distribution_method'] ?? '') === 'least_loaded' ? 'selected' : '' }}>{{ __('الأقل عبئاً (Least Loaded)') }}</option>
                                            </select>
                                            <div class="mt-3 p-3 bg-info-subtle text-info rounded-3 text-start small">
                                                <i class="bi bi-info-circle-fill me-1"></i>
                                                <strong>{{ __('دوري:') }}</strong> {{ __('يتم توزيع الطلبات بالتساوي على الموظفين واحداً تلو الآخر.') }}<br>
                                                <strong>{{ __('الأقل عبئاً:') }}</strong> {{ __('يتم تعيين الطلب للموظف الذي لديه أقل عدد من الطلبات النشطة.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Sidebar Action --}}
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden shadow-lg sticky-top" style="top: 20px;">
                        <div class="card-body p-4 position-relative">
                            <i class="bi bi-save position-absolute opacity-10" style="font-size: 80px; right: -10px; bottom: -20px;"></i>
                            <h5 class="fw-bold mb-3">{{ __('حفظ التغييرات') }}</h5>
                            <p class="small opacity-75 mb-4">{{ __('تأكد من مراجعة كافة التبويبات قبل الحفظ.') }}</p>
                            @can('settings.manage')
                            <button type="submit" class="btn btn-white w-100 py-3 fw-black text-primary border-0 rounded-3 shadow-sm">
                                <i class="bi bi-check2-circle me-2"></i> {{ __('تحديث الإعدادات') }}
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('css')
<style>
    .nav-pills .nav-link { color: #64748b; }
    .nav-pills .nav-link.active { background-color: #fff !important; color: var(--crm-red) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .btn-white { background: #fff; }
    .fw-black { font-weight: 900; }
    .bg-info-subtle { background: #e0f2fe; }
    .bg-primary-subtle { background: #e7f1ff; }
    .object-fit-cover { object-fit: cover; }

    /* ===== Video Upload Zone ===== */
    .video-upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 16px;
        padding: 28px 20px;
        cursor: pointer;
        transition: border-color 0.25s, background 0.25s;
        background: #fafafa;
        margin-bottom: 12px;
    }
    .video-upload-zone:hover {
        border-color: #EE1E26;
        background: #fff5f5;
    }
    .upload-zone-inner {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .upload-icon-wrap {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #EE1E26, #a8151b);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .upload-icon { font-size: 24px; color: #fff; }
    .upload-text-wrap { flex: 1; }
    .upload-title { display: block; font-weight: 700; font-size: 15px; color: #1a1a1a; }
    .upload-sub   { display: block; font-size: 12px; color: #888; margin-top: 3px; }
    .upload-btn {
        background: #EE1E26; color: #fff; border: none;
        padding: 8px 20px; border-radius: 10px;
        font-weight: 700; font-size: 13px;
        white-space: nowrap; flex-shrink: 0;
        transition: opacity 0.2s;
    }
    .upload-btn:hover { opacity: 0.88; }

    /* ===== File Info Card ===== */
    .video-upload-info {
        background: #f8faff;
        border: 1px solid #e0e7ff;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 10px;
    }
    .file-meta {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .file-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 18px; flex-shrink: 0;
    }
    .file-details { flex: 1; overflow: hidden; }
    .file-name {
        font-weight: 700; font-size: 13px; color: #1a1a1a;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .file-size { font-size: 12px; color: #888; margin-top: 2px; }
    .file-remove {
        background: none; border: none; color: #ef4444;
        font-size: 20px; cursor: pointer; padding: 0; flex-shrink: 0;
        transition: transform 0.2s;
    }
    .file-remove:hover { transform: scale(1.15); }

    /* ===== Progress Bar ===== */
    .progress-wrap { margin-top: 10px; }
    .progress-bar-bg {
        height: 8px; background: #e2e8f0;
        border-radius: 100px; overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%; width: 0%;
        background: linear-gradient(90deg, #EE1E26, #ff6b6b);
        border-radius: 100px;
        transition: width 0.4s ease;
        position: relative;
    }
    .progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);
        animation: shimmer 1.5s infinite;
        border-radius: 100px;
    }
    @keyframes shimmer {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .progress-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 6px;
    }
    .progress-pct    { font-weight: 800; font-size: 12px; color: #EE1E26; }
    .progress-status { font-size: 12px; color: #64748b; }
</style>
@endsection

@section('scripts')
<script>
(function() {
    const input    = document.getElementById('heroVideoInput');
    const zone     = document.getElementById('videoUploadZone');
    const info     = document.getElementById('videoUploadInfo');
    const nameEl   = document.getElementById('videoFileName');
    const sizeEl   = document.getElementById('videoFileSize');
    const bar      = document.getElementById('videoProgressBar');
    const pctEl    = document.getElementById('videoProgressText');
    const statusEl = document.getElementById('videoProgressStatus');
    const removeBtn = document.getElementById('videoRemoveBtn');

    if (!input) return;

    function formatSize(bytes) {
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
        return (bytes / 1024).toFixed(0) + ' KB';
    }

    function simulateProgress(onDone) {
        let pct = 0;
        bar.style.width = '0%';
        pctEl.textContent = '0%';
        statusEl.textContent = '{{ __("يتم التحضير...") }}';

        const interval = setInterval(() => {
            // simulate fast until 85%, then slow
            const step = pct < 60 ? 6 : pct < 85 ? 2 : 0.4;
            pct = Math.min(pct + step, 95);
            bar.style.width = pct + '%';
            pctEl.textContent = Math.round(pct) + '%';
            if (pct > 30)  statusEl.textContent = '{{ __("تم التحليل...") }}';
            if (pct > 70)  statusEl.textContent = '{{ __("جاهز للحفظ...") }}';
            if (pct >= 95) {
                clearInterval(interval);
                bar.style.width = '100%';
                pctEl.textContent = '100%';
                statusEl.textContent = '{{ __("✓ جاهز — اضغط حفظ لرفع الفيديو") }}';
                bar.style.background = 'linear-gradient(90deg, #16a34a, #22c55e)';
                document.getElementById('saveVideoBtn').classList.remove('d-none');
                if (onDone) onDone();
            }
        }, 60);
    }

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        // Show info card, hide upload zone
        zone.style.display = 'none';
        info.classList.remove('d-none');
        nameEl.textContent = file.name;
        sizeEl.textContent = formatSize(file.size);
        bar.style.background = 'linear-gradient(90deg, #EE1E26, #ff6b6b)';  // reset

        simulateProgress();
    });

    removeBtn.addEventListener('click', function () {
        input.value = '';
        info.classList.add('d-none');
        zone.style.display = '';
        bar.style.width = '0%';
        pctEl.textContent = '0%';
        document.getElementById('saveVideoBtn').classList.add('d-none');
    });

    // Drag & Drop
    zone.addEventListener('dragover',  (e) => { e.preventDefault(); zone.style.borderColor = '#EE1E26'; });
    zone.addEventListener('dragleave', ()  => { zone.style.borderColor = ''; });
    zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.style.borderColor = '';
        const file = e.dataTransfer.files[0];
        if (file && (file.type === 'video/mp4' || file.type === 'video/webm')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        }
    });
})();

// Social Media Dynamic Rows
let socialCount = {{ count($socialMedia) }};
function addSocialRow() {
    const idx = socialCount++;
    document.getElementById('no-social-msg').classList.add('d-none');
    
    const container = document.getElementById('social-container');
    const div = document.createElement('div');
    div.className = 'social-row d-flex align-items-center gap-2 p-3 bg-light rounded-4 border border-light-subtle';
    div.id = 'social-row-' + idx;
    
    div.innerHTML = `
        <div class="input-group w-auto">
            <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-bootstrap"></i></span>
            <input type="text" name="social_icon[]" class="form-control border-0 shadow-none" placeholder="{{ __('أيقونة (مثل: bi-facebook)') }}" required style="max-width: 180px;">
        </div>
        <div class="d-flex align-items-center bg-white rounded px-2">
            <input type="color" name="social_color[]" class="form-control form-control-color border-0 p-0 shadow-none bg-transparent" value="#333333" title="{{ __('اختر لون الأيقونة') }}" style="width: 32px; height: 32px;">
        </div>
        <div class="input-group flex-grow-1">
            <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-link-45deg"></i></span>
            <input type="url" name="social_link[]" class="form-control border-0 shadow-none text-start" dir="ltr" placeholder="https://..." required>
        </div>
        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-2" onclick="removeSocialRow(${idx})" title="{{ __('حذف') }}">
            <i class="bi bi-trash-fill"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeSocialRow(idx) {
    document.getElementById('social-row-' + idx)?.remove();
    if (document.querySelectorAll('.social-row').length === 0) {
        document.getElementById('no-social-msg').classList.remove('d-none');
    }
}

// Toggle password visibility
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById(id + '_icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

// Twilio Test Connection
document.getElementById('test-twilio-btn')?.addEventListener('click', async function () {
    const btn = this;
    const statusBadge = document.getElementById('twilio-status-badge');
    const statusMsg = document.getElementById('twilio-status-msg');

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __("جارِ الاختبار...") }}';
    statusBadge.className = 'badge bg-warning-subtle text-warning rounded-pill px-3';
    statusBadge.innerHTML = '<i class="bi bi-hourglass me-1"></i> {{ __("جارِ الاختبار") }}';

    try {
        const resp = await fetch('{{ route("crm.settings.twilio.test") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        });
        const data = await resp.json();

        if (data.success) {
            statusBadge.className = 'badge bg-success-subtle text-success rounded-pill px-3';
            statusBadge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> {{ __("متصل") }}';
            statusMsg.className = 'text-success small mt-1';
            statusMsg.textContent = data.message;
        } else {
            statusBadge.className = 'badge bg-danger-subtle text-danger rounded-pill px-3';
            statusBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i> {{ __("غير متصل") }}';
            statusMsg.className = 'text-danger small mt-1';
            statusMsg.textContent = data.message;
        }
    } catch (e) {
        statusBadge.className = 'badge bg-danger-subtle text-danger rounded-pill px-3';
        statusBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i> {{ __("فشل الاتصال") }}';
        statusMsg.className = 'text-danger small mt-1';
        statusMsg.textContent = '{{ __("تعذر الوصول إلى الخادم") }}';
    }

    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-send-check me-1"></i> {{ __("اختبار الربط") }}';
});
</script>
@endsection
