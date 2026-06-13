@extends('partials.Layouts.crm-master')
@section('title', __('إدارة المميزات والخصائص') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1">{{ __('إدارة المميزات والخصائص') }}</h4>
                <p class="text-muted mb-0">{{ __('إجمالي') }} {{ $features->total() }} {{ __('خاصية') }}</p>
            </div>
            @can('features.create')
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
                <i class="bi bi-plus-circle me-1"></i> {{ __('إضافة خاصية جديدة') }}
            </button>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3" style="width: 80px;">{{ __('الأيقونة') }}</th>
                            <th class="border-0 px-4 py-3">{{ __('الاسم') }}</th>
                            <th class="border-0 px-4 py-3">{{ __('الكود') }}</th>
                            <th class="border-0 px-4 py-3 text-end">{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($features as $feature)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="bg-success-subtle rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:40px;height:40px;">
                                        @if($feature->icon)
                                            <i class="bi {{ $feature->icon }} fs-5 text-success"></i>
                                        @else
                                            <i class="bi bi-check-all fs-5 text-success"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 fw-bold">{{ $feature->name }}</td>
                                <td class="px-4 py-3 text-muted" dir="ltr"><small>{{ $feature->icon ?? __('افتراضي') }}</small></td>
                                <td class="px-4 py-3 text-end">
                                    @can('features.edit')
                                    <button class="btn btn-sm btn-light rounded-3 me-2" data-bs-toggle="modal"
                                        data-bs-target="#editFeature{{ $feature->id }}" title="{{ __('تعديل') }}"><i class="bi bi-pencil-square"></i></button>
                                    @endcan
                                    @can('features.delete')
                                    <form action="{{ route('crm.features.destroy', $feature) }}" method="POST"
                                        class="d-inline-block" onsubmit="return confirm('{{ __("هل أنت متأكد من حذف هذه الخاصية؟") }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-3" title="{{ __('حذف') }}"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>

                            {{-- Modal التعديل --}}
                            <div class="modal fade" id="editFeature{{ $feature->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">{{ __('تعديل الخاصية') }}</h5>
                                            <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('crm.features.update', $feature) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body text-start">
                                                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-3" role="tablist">
                                                    <li class="nav-item">
                                                        <button class="nav-link active rounded-pill py-1" data-bs-toggle="tab" data-bs-target="#edit-ar-feat-{{ $feature->id }}" type="button">{{ __('العربية') }}</button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button class="nav-link rounded-pill py-1" data-bs-toggle="tab" data-bs-target="#edit-en-feat-{{ $feature->id }}" type="button">{{ __('الإنجليزية') }}</button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content mb-3">
                                                    <div class="tab-pane fade show active" id="edit-ar-feat-{{ $feature->id }}">
                                                        <label class="form-label fw-semibold">{{ __('اسم الخاصية (بالعربية)') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[ar]" class="form-control rounded-3"
                                                            value="{{ $feature->getTranslation('name', 'ar', false) ?? '' }}" required>
                                                    </div>
                                                    <div class="tab-pane fade" id="edit-en-feat-{{ $feature->id }}">
                                                        <label class="form-label fw-semibold">{{ __('اسم الخاصية (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[en]" class="form-control rounded-3"
                                                            value="{{ $feature->getTranslation('name', 'en', false) ?? '' }}" required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">{{ __('أيقونة الخاصية (Bootstrap Icons)') }}</label>
                                                    <input type="text" name="icon" class="form-control rounded-3" placeholder="{{ __('مثال: bi-shield-check') }}"
                                                        value="{{ $feature->icon }}">
                                                    <small class="text-muted">{{ __('استخدم أكواد Bootstrap Icons (مثل: bi-wifi)') }}</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('حفظ') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="text-center py-5 text-muted bg-white rounded-4">
                                        <i class="bi bi-list-check fs-1 d-block mb-3 text-light"></i>
                                        <p class="mb-0">{{ __('لا توجد مميزات مسجلة حالياً') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">{{ $features->links() }}</div>

        {{-- Modal الإضافة --}}
        <div class="modal fade" id="addFeatureModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">{{ __('إضافة خاصية جديدة') }}</h5>
                        <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('crm.features.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-3" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active rounded-pill py-1" data-bs-toggle="tab" data-bs-target="#add-ar-feat" type="button">{{ __('العربية') }}</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link rounded-pill py-1" data-bs-toggle="tab" data-bs-target="#add-en-feat" type="button">{{ __('الإنجليزية') }}</button>
                                </li>
                            </ul>

                            <div class="tab-content mb-3">
                                <div class="tab-pane fade show active" id="add-ar-feat">
                                    <label class="form-label fw-semibold">{{ __('اسم الخاصية (بالعربية)') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name[ar]" class="form-control rounded-3" placeholder="{{ __('مثال: بصمة') }}" required>
                                </div>
                                <div class="tab-pane fade" id="add-en-feat">
                                    <label class="form-label fw-semibold">{{ __('اسم الخاصية (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name[en]" class="form-control rounded-3" placeholder="{{ __('e.g., Keyless Entry') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('أيقونة الخاصية (Bootstrap Icons)') }}</label>
                                <input type="text" name="icon" class="form-control rounded-3" placeholder="{{ __('مثال: bi-shield-check') }}">
                                <small class="text-muted">{{ __('استخدم أكواد Bootstrap Icons (مثل: bi-wifi)') }}</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('إضافة') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-hover { transition: transform 0.2s ease-in-out; }
        .transition-hover:hover { transform: translateY(-5px); }
    </style>
@endsection
