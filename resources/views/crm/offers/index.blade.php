@extends('partials.Layouts.crm-master')
@section('title', __('إدارة العروض') . ' | AutoCRM')

@section('css')
<style>
.car-picker-item {
    cursor: pointer;
    border-radius: 10px;
    border: 2px solid transparent;
    background: #fff;
    transition: all .15s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
}
.car-picker-item:hover {
    border-color: #dee2e6;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
}
.car-picker-item.selected {
    border-color: #0d6efd;
    background: #f0f6ff;
    box-shadow: 0 0 0 1px #0d6efd;
}
.car-picker-thumb {
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #f8f9fa;
}
.car-picker-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.car-picker-thumb i {
    font-size: 28px;
    color: #adb5bd;
}
.car-picker-info {
    padding: 8px 10px 10px;
    line-height: 1.3;
}
.car-picker-brand {
    display: block;
    font-size: 11px;
    margin-bottom: 1px;
}
.car-picker-name {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #212529;
}
.car-picker-check {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #0d6efd;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    opacity: 0;
    transform: scale(.5);
    transition: all .2s cubic-bezier(.4,0,.2,1);
    pointer-events: none;
}
.car-picker-item.selected .car-picker-check {
    opacity: 1;
    transform: scale(1);
}
.car-picker-grid::-webkit-scrollbar { width: 5px; }
.car-picker-grid::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 10px;
}
.car-picker-grid { scrollbar-width: thin; }
[dir="rtl"] .car-picker-check { right: auto; left: 6px; }
</style>
@endsection

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold"> {{ __('إدارة العروض الترويجية') }}</h4>
            <p class="text-muted mb-0 small">{{ __('إجمالي') }} {{ $offers->total() }} {{ __('عرض متاح') }}</p>
        </div>
        @can('offers.create')
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addOfferModal">
            <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة عرض جديد') }}
        </button>
        @endcan
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small fw-bold">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        @forelse($offers as $offer)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative">
                {{-- مؤشر الحالة --}}
                @php
                    $isActive = $offer->is_active && (!$offer->ends_at || $offer->ends_at > now());
                @endphp
                <div class="position-absolute top-0 bottom-0 start-0 bg-{{ $isActive ? 'success' : 'danger' }}" style="width: 5px;"></div>
                
                <div class="card-body p-4 ps-5">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">{{ $offer->title }}</h5>
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    @php $carThumbs = $offer->cars->take(3); @endphp
                                    @foreach($carThumbs as $car)
                                        <div class="position-relative rounded-2 overflow-hidden border" style="width:56px;height:42px;flex-shrink:0;" title="{{ $car->brand->name ?? '' }} {{ $car->name }}">
                                            @if($car->thumbnail)
                                                <img src="{{ asset('storage/'.$car->thumbnail) }}" alt="{{ $car->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                                                <div class="position-absolute bottom-0 start-0 end-0 text-white px-1" style="font-size:8px;line-height:1.2;background:linear-gradient(transparent,rgba(0,0,0,.75));padding:2px 2px 3px;">{{ $car->name }}</div>
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="width:100%;height:100%;font-size:16px;">
                                                    <i class="bi bi-car-front"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($offer->cars->count() > 3)
                                        <span class="badge bg-light text-muted border px-2 py-1" style="font-size:10px;cursor:default;" title="{{ $offer->cars->skip(3)->map(fn($c) => ($c->brand->name ?? '') . ' ' . $c->name)->implode(' · ') }}">
                                            +{{ $offer->cars->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                                @if(!$offer->is_active)
                                    <span class="badge bg-secondary-subtle text-secondary small">{{ __('معطل') }}</span>
                                @elseif($offer->ends_at && $offer->ends_at < now())
                                    <span class="badge bg-danger-subtle text-danger small">{{ __('منتهي') }}</span>
                                @endif
                            </div>
                        </div>
                        @if($offer->discount_percent)
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-black shadow-sm" style="width: 55px; height: 55px; font-size: 16px;">
                                {{ $offer->discount_percent }}%
                            </div>
                        @elseif($offer->discount_value)
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-black shadow-sm" style="width: 55px; height: 55px; font-size: 13px; line-height:1.2; flex-direction:column;">
                                <span style="font-size:9px;">{{ __('خصم') }}</span>
                                <span>{{ number_format($offer->discount_value) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <p class="text-muted small mb-4" style="line-height: 1.6;">{{ Str::limit($offer->description, 100) }}</p>
                    
                    @if($offer->special_installment)
                    <div class="bg-light rounded-4 p-3 mb-4 border border-light shadow-xs">
                        <div class="text-center">
                            <small class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('قسط يبدأ من') }}</small>
                            <span class="fw-black text-primary fs-5">{{ number_format($offer->special_installment) }} <small class="fw-normal fs-12">/ شهر</small></span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="d-flex align-items-center justify-content-between text-muted x-small fw-bold">
                        <span><i class="bi bi-calendar3 me-1"></i> {{ __('يبدأ') }}: {{ $offer->starts_at ? $offer->starts_at->format('Y/m/d') : __('الآن') }}</span>
                        @if($offer->ends_at)
                            <span class="{{ $offer->ends_at < now() ? 'text-danger' : 'text-warning' }}">
                                <i class="bi bi-clock-history me-1"></i> {{ __('ينتهي') }}: {{ $offer->ends_at->format('Y/m/d') }}
                            </span>
                        @else
                            <span class="text-info"><i class="bi bi-infinity me-1"></i> {{ __('عرض مستمر') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer bg-light border-0 d-flex gap-2 p-3 ps-5">
                    @can('offers.edit')
                    <button class="btn btn-sm btn-white border shadow-xs flex-grow-1 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#editOfferModal{{ $offer->id }}">
                        <i class="bi bi-pencil-square me-1"></i> {{ __('تعديل') }}
                    </button>
                    @endcan
                    @can('offers.delete')
                    <form action="{{ route('crm.offers.destroy', $offer) }}" method="POST" onsubmit="return confirm('{{ __("هل أنت متأكد من حذف هذا العرض؟") }}')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger-subtle text-danger rounded-3 px-3 shadow-xs"><i class="bi bi-trash"></i></button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Modal التعديل --}}
        <div class="modal fade" id="editOfferModal{{ $offer->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">{{ __('تعديل العرض') }}</h5>
                        <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('crm.offers.update', $offer) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold d-flex align-items-center gap-2 mb-2">
                                        {{ __('السيارات المرتبطة') }} <span class="text-danger">*</span>
                                        <span class="badge bg-primary-subtle text-primary car-counter-badge px-2 py-1 small fw-normal">{{ $offer->cars->count() }} {{ __('محدد') }}</span>
                                    </label>
                                    @php $editSelected = $offer->cars->pluck('id')->toArray(); @endphp
                                    <div class="car-picker">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="position-relative flex-grow-1">
                                                <i class="bi bi-search position-absolute top-50 {{ app()->getLocale() == 'ar' ? 'end-0 me-3' : 'start-0 ms-3' }} translate-middle-y text-muted"></i>
                                                <input type="text" class="form-control form-control-sm bg-light border-0 car-picker-search rounded-3 {{ app()->getLocale() == 'ar' ? 'pe-5' : 'ps-5' }}" placeholder="{{ __('ابحث عن سيارة...') }}">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary car-picker-select-all rounded-3 px-3"><i class="bi bi-check-all me-1"></i>{{ __('تحديد') }}</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary car-picker-deselect-all rounded-3 px-3"><i class="bi bi-x me-1"></i>{{ __('إلغاء') }}</button>
                                        </div>
                                        <div class="car-picker-grid row g-2" style="max-height:300px;overflow-y:auto;">
                                            @foreach($cars as $car)
                                            @php $isSel = in_array($car->id, $editSelected); @endphp
                                            <div class="col-6 col-md-4 col-lg-4 car-picker-col">
                                                <div class="car-picker-item {{ $isSel ? 'selected' : '' }}" data-search="{{ mb_strtolower(($car->brand->name ?? '') . ' ' . $car->name) }}">
                                                    <input type="checkbox" name="car_ids[]" value="{{ $car->id }}" {{ $isSel ? 'checked' : '' }} hidden>
                                                    <div class="car-picker-thumb">
                                                        @if($car->thumbnail)
                                                        <img src="{{ asset('storage/'.$car->thumbnail) }}" alt="" loading="lazy">
                                                        @else
                                                        <i class="bi bi-car-front"></i>
                                                        @endif
                                                    </div>
                                                    <div class="car-picker-info">
                                                        <small class="car-picker-brand text-muted">{{ $car->brand->name ?? '' }}</small>
                                                        <span class="car-picker-name">{{ $car->name }}</span>
                                                    </div>
                                                    <div class="car-picker-check"><i class="bi bi-check-lg"></i></div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2 d-flex align-items-center justify-content-between">
                                            <span class="text-muted small car-picker-count"></span>
                                            <span class="text-muted small">{{ $cars->count() }} {{ __('سيارة متاحة') }}</span>
                                        </div>
                                        <div class="invalid-feedback car-picker-feedback">{{ __('يجب اختيار سيارة واحدة على الأقل') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('عنوان العرض (عربي)') }}</label>
                                    <input type="text" name="title[ar]" class="form-control bg-light border-0" value="{{ $offer->getTranslation('title', 'ar', false) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('عنوان العرض (EN)') }}</label>
                                    <input type="text" name="title[en]" class="form-control bg-light border-0" value="{{ $offer->getTranslation('title', 'en', false) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('الوصف (عربي)') }}</label>
                                    <textarea name="description[ar]" class="form-control bg-light border-0" rows="2">{{ $offer->getTranslation('description', 'ar', false) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('الوصف (EN)') }}</label>
                                    <textarea name="description[en]" class="form-control bg-light border-0" rows="2">{{ $offer->getTranslation('description', 'en', false) }}</textarea>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('نوع الخصم') }}</label>
                                    <div class="btn-group btn-group-sm w-100 mb-2" role="group">
                                        <input type="radio" class="btn-check discount-radio" name="discount_type_edit{{ $offer->id }}" id="dt_edit_pct_{{ $offer->id }}" value="percent" autocomplete="off" {{ $offer->discount_percent || !$offer->discount_value ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary rounded-start-3" for="dt_edit_pct_{{ $offer->id }}">{{ __('نسبة %') }}</label>
                                        <input type="radio" class="btn-check discount-radio" name="discount_type_edit{{ $offer->id }}" id="dt_edit_val_{{ $offer->id }}" value="value" autocomplete="off" {{ $offer->discount_value ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary rounded-end-3" for="dt_edit_val_{{ $offer->id }}">{{ __('قيمة ﷼') }}</label>
                                    </div>
                                    <input type="number" name="discount_percent" class="form-control bg-light border-0 discount-input" data-type="percent" placeholder="10" value="{{ $offer->discount_percent }}">
                                    <input type="number" name="discount_value" class="form-control bg-light border-0 discount-input" data-type="value" placeholder="5000" value="{{ $offer->discount_value }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('قسط شهري يبدأ من') }}</label>
                                    <input type="number" name="special_installment" class="form-control bg-light border-0" value="{{ $offer->special_installment }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('تاريخ البدء') }}</label>
                                    <input type="date" name="starts_at" class="form-control bg-light border-0" value="{{ $offer->starts_at ? $offer->starts_at->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('تاريخ الانتهاء') }}</label>
                                    <input type="date" name="ends_at" class="form-control bg-light border-0" value="{{ $offer->ends_at ? $offer->ends_at->format('Y-m-d') : '' }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold">{{ __('صورة العرض') }}</label>
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        @if($offer->image)
                                            <div class="rounded-3 border overflow-hidden" style="width: 100px; height: 60px;">
                                                <img src="{{ asset('storage/' . $offer->image) }}" class="w-100 h-100 object-fit-cover">
                                            </div>
                                        @endif
                                        <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                    <small class="text-muted">{{ __('اتركه فارغاً لاستخدام صورة السيارة الافتراضية') }}</small>
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                        <input class="form-check-input {{ app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : '' }}" type="checkbox" name="is_active" value="1" id="active{{ $offer->id }}" {{ $offer->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold ms-2" for="active{{ $offer->id }}">{{ __('تفعيل العرض فوراً') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('حفظ التغييرات') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 bg-white rounded-4 shadow-sm opacity-50">
                <i class="bi bi-tags fs-1 d-block mb-3"></i>
                <h6 class="fw-bold">{{ __('لا توجد عروض ترويجية حالياً') }}</h6>
                <p class="small">{{ __('يمكنك البدء بإضافة عرض جديد لسيارة محددة') }}</p>
            </div>
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">{{ $offers->links() }}</div>

</div>

{{-- Modal الإضافة --}}
<div class="modal fade" id="addOfferModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">{{ __('إضافة عرض ترويجي جديد') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.offers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold d-flex align-items-center gap-2 mb-2">
                                {{ __('السيارات المستهدفة') }} <span class="text-danger">*</span>
                                <span class="badge bg-primary-subtle text-primary car-counter-badge px-2 py-1 small fw-normal">0 {{ __('محدد') }}</span>
                            </label>
                            <div class="car-picker">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="position-relative flex-grow-1">
                                        <i class="bi bi-search position-absolute top-50 {{ app()->getLocale() == 'ar' ? 'end-0 me-3' : 'start-0 ms-3' }} translate-middle-y text-muted"></i>
                                        <input type="text" class="form-control form-control-sm bg-light border-0 car-picker-search rounded-3 {{ app()->getLocale() == 'ar' ? 'pe-5' : 'ps-5' }}" placeholder="{{ __('ابحث عن سيارة...') }}">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary car-picker-select-all rounded-3 px-3"><i class="bi bi-check-all me-1"></i>{{ __('تحديد') }}</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary car-picker-deselect-all rounded-3 px-3"><i class="bi bi-x me-1"></i>{{ __('إلغاء') }}</button>
                                </div>
                                <div class="car-picker-grid row g-2" style="max-height:300px;overflow-y:auto;">
                                    @foreach($cars as $car)
                                    <div class="col-6 col-md-4 col-lg-4 car-picker-col">
                                        <div class="car-picker-item" data-search="{{ mb_strtolower(($car->brand->name ?? '') . ' ' . $car->name) }}">
                                            <input type="checkbox" name="car_ids[]" value="{{ $car->id }}" hidden>
                                            <div class="car-picker-thumb">
                                                @if($car->thumbnail)
                                                <img src="{{ asset('storage/'.$car->thumbnail) }}" alt="" loading="lazy">
                                                @else
                                                <i class="bi bi-car-front"></i>
                                                @endif
                                            </div>
                                            <div class="car-picker-info">
                                                <small class="car-picker-brand text-muted">{{ $car->brand->name ?? '' }}</small>
                                                <span class="car-picker-name">{{ $car->name }}</span>
                                            </div>
                                            <div class="car-picker-check"><i class="bi bi-check-lg"></i></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-2 d-flex align-items-center justify-content-between">
                                    <span class="text-muted small car-picker-count"></span>
                                    <span class="text-muted small">{{ $cars->count() }} {{ __('سيارة متاحة') }}</span>
                                </div>
                                <div class="invalid-feedback car-picker-feedback">{{ __('يجب اختيار سيارة واحدة على الأقل') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('عنوان العرض (عربي)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title[ar]" class="form-control bg-light border-0" placeholder="مثال: عرض الصيف الهائل" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('عنوان العرض (EN)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title[en]" class="form-control bg-light border-0" placeholder="e.g.: Mega Summer Sale" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">{{ __('الوصف المختصر (عربي)') }}</label>
                            <textarea name="description[ar]" class="form-control bg-light border-0" rows="2" placeholder="اكتب تفاصيل العرض..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">{{ __('الوصف المختصر (EN)') }}</label>
                            <textarea name="description[en]" class="form-control bg-light border-0" rows="2" placeholder="English description..."></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('نوع الخصم') }}</label>
                            <div class="btn-group btn-group-sm w-100 mb-2" role="group">
                                <input type="radio" class="btn-check discount-radio" name="discount_type_add" id="dt_add_pct" value="percent" autocomplete="off" checked>
                                <label class="btn btn-outline-primary rounded-start-3" for="dt_add_pct">{{ __('نسبة %') }}</label>
                                <input type="radio" class="btn-check discount-radio" name="discount_type_add" id="dt_add_val" value="value" autocomplete="off">
                                <label class="btn btn-outline-primary rounded-end-3" for="dt_add_val">{{ __('قيمة ﷼') }}</label>
                            </div>
                            <input type="number" name="discount_percent" class="form-control bg-light border-0 discount-input" data-type="percent" placeholder="10">
                            <input type="number" name="discount_value" class="form-control bg-light border-0 discount-input d-none" data-type="value" placeholder="5000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('قسط شهري يبدأ من') }}</label>
                            <input type="number" name="special_installment" class="form-control bg-light border-0" placeholder="أقل قسط متاح">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('تاريخ البدء') }}</label>
                            <input type="date" name="starts_at" class="form-control bg-light border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('تاريخ الانتهاء') }}</label>
                            <input type="date" name="ends_at" class="form-control bg-light border-0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">{{ __('صورة العرض') }}</label>
                            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                            <small class="text-muted">{{ __('سيتم استخدام صورة السيارة تلقائياً إذا لم ترفع صورة هنا') }}</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('إضافة العرض الآن') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-white { background: #fff; }
    .btn-danger-subtle { background: #ffebee; }
    .fw-black { font-weight: 900; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .x-small { font-size: 11px; }
    .fs-12 { font-size: 12px; }
    .bg-primary-subtle { background: #e7f1ff; }
    .btn-group-sm .btn { font-size: 12px; padding: 4px 12px; }
    .btn-check:checked + .btn-outline-primary { background: #0d6efd; color: #fff; }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const pickers = document.querySelectorAll('.car-picker');

    pickers.forEach(function (container) {
        const items = container.querySelectorAll('.car-picker-item');
        const grid = container.querySelector('.car-picker-grid');
        const search = container.querySelector('.car-picker-search');
        const countEl = container.querySelector('.car-picker-count');
        const feedback = container.querySelector('.car-picker-feedback');
        const badge = container.closest('.modal')?.querySelector('.car-counter-badge');

        function updateCount() {
            const checked = container.querySelectorAll('input[name="car_ids[]"]:checked').length;
            const total = items.length;
            countEl.textContent = checked + ' {{ __("سيارة محددة") }}';
            if (feedback) {
                feedback.style.display = checked > 0 ? 'none' : 'block';
            }
            if (badge) badge.textContent = checked + ' {{ __("محدد") }}';
        }

        // Click to toggle
        items.forEach(function (item) {
            item.addEventListener('click', function () {
                this.classList.toggle('selected');
                const cb = this.querySelector('input[type="checkbox"]');
                cb.checked = this.classList.contains('selected');
                updateCount();
            });
        });

        // Search filter
        if (search) {
            search.addEventListener('input', function () {
                const q = this.value.toLowerCase();
                const cols = grid.querySelectorAll('.car-picker-col');
                cols.forEach(function (col) {
                    const item = col.querySelector('.car-picker-item');
                    const txt = item ? (item.dataset.search || '') : '';
                    col.style.display = txt.includes(q) ? '' : 'none';
                });
            });
        }

        // Select all (visible only)
        container.querySelector('.car-picker-select-all')?.addEventListener('click', function () {
            const cols = grid.querySelectorAll('.car-picker-col');
            cols.forEach(function (col) {
                if (col.style.display !== 'none') {
                    const item = col.querySelector('.car-picker-item');
                    if (item) {
                        item.classList.add('selected');
                        item.querySelector('input[type="checkbox"]').checked = true;
                    }
                }
            });
            updateCount();
        });

        // Deselect all
        container.querySelector('.car-picker-deselect-all')?.addEventListener('click', function () {
            items.forEach(function (item) {
                item.classList.remove('selected');
                item.querySelector('input[type="checkbox"]').checked = false;
            });
            updateCount();
        });

        // Form validation
        const form = container.closest('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const checked = container.querySelectorAll('input[name="car_ids[]"]:checked').length;
                if (checked === 0) {
                    e.preventDefault();
                    if (feedback) {
                        feedback.style.display = 'block';
                        container.querySelector('.car-picker-grid')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        }

        updateCount();
    });

    // Discount type toggle
    document.querySelectorAll('.discount-radio').forEach(function (radio) {
        function syncDiscountFields() {
            var container = radio.closest('.col-md-4');
            if (!container) return;
            container.querySelectorAll('.discount-input').forEach(function (inp) {
                var type = inp.getAttribute('data-type');
                if (type === radio.value) {
                    inp.classList.remove('d-none');
                    inp.removeAttribute('disabled');
                } else {
                    inp.classList.add('d-none');
                    inp.setAttribute('disabled', 'disabled');
                }
            });
        }
        radio.addEventListener('change', syncDiscountFields);
        if (radio.checked) syncDiscountFields();
    });

});
</script>
@endsection
