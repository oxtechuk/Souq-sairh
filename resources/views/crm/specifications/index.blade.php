@extends('partials.Layouts.crm-master')
@section('title', __('إدارة المواصفات التقنية') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1">{{ __('إدارة المواصفات التقنية') }}</h4>
                <p class="text-muted mb-0">{{ __('إجمالي') }} {{ $specifications->total() }} {{ __('مواصفة') }}</p>
            </div>
            @can('specifications.create')
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addSpecModal">
                <i class="bi bi-plus-circle me-1"></i> {{ __('إضافة مواصفة جديدة') }}
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
                        @forelse ($specifications as $spec)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:40px;height:40px;">
                                        @if($spec->icon)
                                            <i class="bi {{ $spec->icon }} fs-5 text-primary"></i>
                                        @else
                                            <i class="bi bi-gear-wide-connected fs-5 text-primary"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 fw-bold">{{ $spec->name }}</td>
                                <td class="px-4 py-3 text-muted" dir="ltr"><small>{{ $spec->icon ?? __('افتراضي') }}</small></td>
                                <td class="px-4 py-3 text-end">
                                    @can('specifications.edit')
                                    <button class="btn btn-sm btn-light rounded-3 me-2" data-bs-toggle="modal"
                                        data-bs-target="#editSpec{{ $spec->id }}" title="{{ __('تعديل') }}"><i class="bi bi-pencil-square"></i></button>
                                    @endcan
                                    @can('specifications.delete')
                                    <form action="{{ route('crm.specifications.destroy', $spec) }}" method="POST"
                                        class="d-inline-block" onsubmit="return confirm('{{ __("هل أنت متأكد من حذف هذه المواصفة؟") }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-3" title="{{ __('حذف') }}"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>

                            {{-- Modal التعديل --}}
                            <div class="modal fade" id="editSpec{{ $spec->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">{{ __('تعديل المواصفة') }}</h5>
                                            <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('crm.specifications.update', $spec) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body text-start">
                                                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-3" role="tablist">
                                                    <li class="nav-item">
                                                        <button class="nav-link active rounded-pill py-1" data-bs-toggle="tab" data-bs-target="#edit-ar-spec-{{ $spec->id }}" type="button">{{ __('العربية') }}</button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button class="nav-link rounded-pill py-1" data-bs-toggle="tab" data-bs-target="#edit-en-spec-{{ $spec->id }}" type="button">{{ __('الإنجليزية') }}</button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content mb-3">
                                                    <div class="tab-pane fade show active" id="edit-ar-spec-{{ $spec->id }}">
                                                        <label class="form-label fw-semibold">{{ __('اسم المواصفة (بالعربية)') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[ar]" class="form-control rounded-3"
                                                            value="{{ $spec->getTranslation('name', 'ar', false) ?? '' }}" required>
                                                    </div>
                                                    <div class="tab-pane fade" id="edit-en-spec-{{ $spec->id }}">
                                                        <label class="form-label fw-semibold">{{ __('اسم المواصفة (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[en]" class="form-control rounded-3"
                                                            value="{{ $spec->getTranslation('name', 'en', false) ?? '' }}" required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">{{ __('أيقونة المواصفة (Bootstrap Icons)') }}</label>
                                                    <input type="text" name="icon" class="form-control rounded-3" placeholder="{{ __('مثال: bi-speedometer') }}"
                                                        value="{{ $spec->icon }}">
                                                    <small class="text-muted">{{ __('استخدم أكواد Bootstrap Icons (مثل: bi-fuel-pump)') }}</small>
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
                                        <i class="bi bi-gear fs-1 d-block mb-3 text-light"></i>
                                        <p class="mb-0">{{ __('لا توجد مواصفات تقنية مسجلة حالياً') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">{{ $specifications->links() }}</div>

        {{-- Modal الإضافة --}}
        <div class="modal fade" id="addSpecModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">{{ __('إضافة مواصفة جديدة') }}</h5>
                        <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('crm.specifications.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-3" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active rounded-pill py-1" data-bs-toggle="tab" data-bs-target="#add-ar-spec" type="button">{{ __('العربية') }}</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link rounded-pill py-1" data-bs-toggle="tab" data-bs-target="#add-en-spec" type="button">{{ __('الإنجليزية') }}</button>
                                </li>
                            </ul>

                            <div class="tab-content mb-3">
                                <div class="tab-pane fade show active" id="add-ar-spec">
                                    <label class="form-label fw-semibold">{{ __('اسم المواصفة (بالعربية)') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name[ar]" class="form-control rounded-3" placeholder="{{ __('مثال: فتحة سقف') }}" required>
                                </div>
                                <div class="tab-pane fade" id="add-en-spec">
                                    <label class="form-label fw-semibold">{{ __('اسم المواصفة (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name[en]" class="form-control rounded-3" placeholder="{{ __('e.g., Sunroof') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('أيقونة المواصفة (Bootstrap Icons)') }}</label>
                                <input type="text" name="icon" class="form-control rounded-3" placeholder="{{ __('مثال: bi-speedometer') }}">
                                <small class="text-muted">{{ __('استخدم أكواد Bootstrap Icons (مثل: bi-fuel-pump)') }}</small>
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
