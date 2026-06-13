@extends('partials.Layouts.crm-master')
@section('title', __('إدارة السيارات') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold"> {{ __('إدارة أسطول السيارات') }}</h4>
                <p class="text-muted mb-0 small">{{ __('إجمالي') }} {{ $cars->total() }} {{ __('سيارة مسجلة') }}</p>
            </div>
            @can('cars.create')
                <a href="{{ route('crm.cars.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة سيارة جديدة') }}
                </a>
            @endcan
        </div>

        @if(session('success'))
            <div   >
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4 rounded-4">
            <div class="card-body p-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">{{ __('البحث عن سيارة') }}</label>
                        <input type="text" name="search" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('الاسم، الموديل...') }}"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">{{ __('الماركة') }}</label>
                        <select name="brand_id" class="form-select bg-light border-0 shadow-none">
                            <option value="">{{ __('كل الماركات') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>{{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">{{ __('التصنيف') }}</label>
                        <select name="category_id" class="form-select bg-light border-0 shadow-none">
                            <option value="">{{ __('كل التصنيفات') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3">{{ __('تصفية') }}</button>
                        <a href="{{ route('crm.cars.index') }}" class="btn btn-light rounded-3 px-3"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Grid --}}
        <div class="row g-4">
            @forelse($cars as $car)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative car-card-hover d-flex flex-column">
                        {{-- Badge --}}
                        <div class="position-absolute top-0 end-0 p-3 d-flex flex-column gap-2" style="z-index: 2;">
                            @if($car->is_featured)
                                <span class="badge bg-warning text-dark border-0 shadow-sm px-3 py-2 rounded-pill fw-bold">⭐ {{ __('مميز') }}</span>
                            @endif
                            @if(!$car->is_active)
                                <span class="badge bg-danger text-white border-0 shadow-sm px-3 py-2 rounded-pill fw-bold">{{ __('مخفي') }}</span>
                            @endif
                        </div>

                        {{-- Image --}}
                        <div class="position-relative overflow-hidden" style="height: 220px;">
                            @if($car->thumbnail)
                                <img src="{{ asset('storage/' . $car->thumbnail) }}" alt="{{ $car->name }}"
                                    class="w-100 h-100 object-fit-cover transition-all car-img">
                            @else
                                <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-car-front fs-1 text-muted opacity-25"></i>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 start-0 w-100 h-50" style="background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 100%); pointer-events: none;"></div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="mb-1 fw-bold text-dark text-truncate" title="{{ $car->name }}">{{ $car->name }}</h5>
                                    <p class="text-muted small mb-0 fw-medium">{{ $car->brand->name ?? '' }} • {{ $car->year }}</p>
                                </div>
                                @if($car->category)
                                    <span class="badge bg-light text-muted border-0 small px-2 py-1 rounded-pill">{{ $car->category->name }}</span>
                                @endif
                            </div>
                            
                            <div class="my-3 d-flex align-items-center gap-2 text-muted small">
                                <i class="bi bi-car-front text-primary"></i> <span>{{ $car->model }}</span>
                            </div>
                            
                            <div class="bg-light rounded-4 p-3 mb-4 border border-light position-relative mt-auto">
                                <div class="row g-2">
                                    <div class="col-6 border-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} border-light">
                                        <small class="text-muted d-block x-small fw-bold mb-1 text-uppercase"><i class="bi bi-cash me-1"></i>{{ __('سعر الكاش') }}</small>
                                        <div class="d-flex align-items-end gap-1">
                                            <strong class="text-dark fs-5 fw-black">{{ number_format($car->cash_price) }}</strong>
                                            <span class="text-success x-small mb-1 fw-bold">ر.س</span>
                                        </div>
                                    </div>
                                    <div class="col-6 ps-2">
                                        <small class="text-muted d-block x-small fw-bold mb-1 text-uppercase"><i class="bi bi-calendar-check me-1"></i>{{ __('أقل قسط') }}</small>
                                        <div class="d-flex align-items-end gap-1">
                                            <strong class="text-success fs-5 fw-black">{{ number_format($car->min_installment) }}</strong>
                                            <span class="text-success x-small mb-1 fw-bold">ر.س</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                @can('cars.edit')
                                <a href="{{ route('crm.cars.edit', $car) }}" class="btn btn-primary-subtle text-primary fw-bold flex-grow-1 rounded-3 py-2 transition-all hover-lift">
                                    <i class="bi bi-pencil-square me-1"></i> {{ __('تعديل') }}
                                </a>
                                @endcan
                                @can('cars.delete')
                                <form action="{{ route('crm.cars.destroy', $car) }}" method="POST"
                                    onsubmit="return confirm('{{ __("هل أنت متأكد من حذف هذه السيارة؟") }}')" class="d-inline-flex">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger-subtle text-danger rounded-3 px-3 py-2 transition-all hover-lift" title="{{ __('حذف') }}"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm opacity-50">
                        <i class="bi bi-car-front fs-1 d-block mb-3"></i>
                        <h6 class="fw-bold">{{ __('لا توجد سيارات مسجلة حالياً') }}</h6>
                        <p class="small">{{ __('ابدأ بإضافة أول سيارة لأسطولك المعروض') }}</p>
                        <a href="{{ route('crm.cars.create') }}" class="btn btn-primary btn-sm rounded-pill mt-3 px-4">{{ __('إضافة سيارة') }}</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">{{ $cars->links() }}</div>
    </div>

    <style>
        .btn-primary-subtle { background: #e3f2fd; border: none; }
        .btn-danger-subtle { background: #ffebee; border: none; }
        .x-small { font-size: 11px; }
        .fw-black { font-weight: 900; }
        .transition-all { transition: all 0.3s ease; }
        .car-card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .car-card-hover:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,.1)!important; }
        .car-card-hover:hover .car-img { transform: scale(1.08); }
        .hover-lift:hover { transform: translateY(-2px); opacity: 0.9; }
        .text-truncate { text-overflow: ellipsis; white-space: nowrap; overflow: hidden; }
    </style>
@endsection
