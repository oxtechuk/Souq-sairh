@extends('partials.Layouts.crm-master')
@section('title', __('الطلبات') . ' | Souq Siarh')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('الطلبات') }}</span>
    </nav>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <span class="stat-badge orange"><i class="bi bi-clock-history"></i></span>
                <div class="stat-icon red"><i class="bi bi-clock"></i></div>
                <div class="stat-lbl">{{ __('بانتظار مراجعة الأدمن') }}</div>
                <div class="stat-val">{{ number_format($stats['pending_review'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <span class="stat-badge green"><i class="bi bi-calendar-check"></i></span>
                <div class="stat-icon blue"><i class="bi bi-people"></i></div>
                <div class="stat-lbl">{{ __('عدد طلبات اليوم') }}</div>
                <div class="stat-val">{{ number_format($stats['today_count'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <span class="stat-badge green"><i class="bi bi-bar-chart"></i></span>
                <div class="stat-icon purple"><i class="bi bi-person-lines-fill"></i></div>
                <div class="stat-val">{{ number_format($stats['total'] ?? $bookings->total()) }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('crm.bookings.index') }}">
        <div class="card border-0 shadow-sm rounded-3 mb-4" style="border:1px solid var(--crm-border)!important;">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    {{-- 1. Search by Name, Phone, or ID --}}
                    <div class="col-12 {{ ($isAdmin ?? false) ? 'col-lg-3' : 'col-lg-5' }} col-md-6">
                        <div style="position:relative;">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="{{ __('بحث بالاسم، رقم الجوال، أو رقم الطلب #...') }}"
                                   class="form-control"
                                   style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                            <i class="bi bi-search" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                        </div>
                    </div>

                    {{-- 2. Filter by Employee --}}
                    @if($isAdmin ?? false)
                    <div class="col-6 col-lg-2 col-md-3">
                        <select name="employee_id" class="form-select" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                            <option value="">{{ __('الموظف — الكل') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 3. Filter by Request Type (تمويل / شراء كاش / شركات) --}}
                    <div class="col-6 col-lg-2 col-md-3">
                        <select name="contact_type" class="form-select" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                            <option value="">{{ __('نوع الطلب — الكل') }}</option>
                            <option value="calculator" {{ request('contact_type') == 'calculator' ? 'selected' : '' }}>{{ __('عميل حاسبة') }}</option>
                            <option value="car_request" {{ request('contact_type') == 'car_request' ? 'selected' : '' }}>{{ __('طلب سيارة') }}</option>
                            <option value="financing" {{ request('contact_type') == 'financing' ? 'selected' : '' }}>{{ __('طلب تمويل') }}</option>
                            <option value="individuals" {{ request('contact_type') == 'individuals' ? 'selected' : '' }}>{{ __('شراء كاش / أفراد') }}</option>
                            <option value="companies" {{ request('contact_type') == 'companies' ? 'selected' : '' }}>{{ __('طلب شركات') }}</option>
                        </select>
                    </div>

                    {{-- 4. Filter by Status (الحالة كاملة) --}}
                    <div class="col-6 col-lg-2 col-md-4">
                        <select name="status" class="form-select" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                            <option value="">{{ __('الحالة — الكل') }}</option>
                            @foreach($statuses as $key => $s)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 5. Date --}}
                    <div class="col-6 col-lg-2 col-md-4">
                        <div style="position:relative;">
                            <input type="date" name="date" value="{{ request('date') }}"
                                   class="form-control"
                                   style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                            <i class="bi bi-calendar3" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:10px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);pointer-events:none;"></i>
                        </div>
                    </div>

                    {{-- 6. Action Buttons --}}
                    <div class="col-12 col-lg-1 col-md-4 d-flex gap-2 align-items-center">
                        <button type="submit" class="btn-crm-primary w-100" style="padding:8px 14px;font-size:13px;">{{ __('تصفية') }}</button>
                        @if(request()->hasAny(['search', 'employee_id', 'contact_type', 'type', 'status', 'date']))
                            <a href="{{ route('crm.bookings.index') }}" class="btn btn-light border text-danger" title="{{ __('إلغاء الفلاتر') }}" style="padding:7px 12px;border-radius:8px;">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0">{{ __('سجل الطلبات') }}</h6>
            <span style="font-size:12px;color:var(--crm-text-muted);">{{ __('إجمالي الطلبات') }}: <strong>{{ $bookings->total() }}</strong></span>
        </div>

        {{-- Desktop Table (hidden on mobile) --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;">{{ __('رقم الطلب') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('رقم العميل') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الراتب/القسط') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('نوع الطلب') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('السيارة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('سعر السيارة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('المسؤول') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('تاريخ الطلب') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الحالة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('إجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($bookings as $b)
                    <tr>
                        <td class="px-4 fw-bold" style="font-size:13px;">
                            <a href="{{ route('crm.bookings.show', $b) }}" class="text-decoration-none" style="color:var(--crm-text);">#{{ $b->id }}</a>
                        </td>
                        <td>
                            <div class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $b->client_name }}</div>
                            <small class="text-muted" dir="ltr">{{ $b->client_phone }}</small>
                        </td>
                        <td style="font-size:13px;">
                            @if(!empty($b->monthly_installment) && $b->monthly_installment > 0)
                                <div class="fw-bold">{{ number_format($b->monthly_installment) }} <small class="text-muted">{!! __('ريال') !!}</small></div>
                                <small class="text-muted" style="font-size:10px;">{{ __('قسط شهري') }}</small>
                            @elseif(!empty($b->salary_range))
                                <div class="fw-semibold" style="font-size:12px;color:var(--crm-text);">{{ $b->salary_range }}</div>
                                <small class="text-muted" style="font-size:10px;">{{ __('الراتب') }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($b->contact_type === 'calculator' || $b->source === 'عميل حاسبة')
                                <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning-subtle px-2 py-1" style="font-size:11px;font-weight:700;">
                                    <i class="bi bi-calculator me-1"></i>{{ __('عميل حاسبة') }}
                                </span>
                            @elseif($b->contact_type === 'car_request' || $b->source === 'طلب سيارة')
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:11px;font-weight:700;">
                                    <i class="bi bi-car-front me-1"></i>{{ __('طلب سيارة') }}
                                </span>
                            @elseif($b->contact_type === 'financing' || (!empty($b->monthly_installment) && $b->monthly_installment > 0))
                                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size:11px;">
                                    <i class="bi bi-credit-card me-1"></i>{{ __('طلب تمويل') }}
                                </span>
                            @elseif($b->contact_type === 'companies')
                                <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-2 py-1" style="font-size:11px;">
                                    <i class="bi bi-building me-1"></i>{{ __('طلب شركات') }}
                                </span>
                            @else
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1" style="font-size:11px;">
                                    <i class="bi bi-cash-stack me-1"></i>{{ __('شراء كاش / أفراد') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size:12px;color:var(--crm-text);">{{ $b->car?->name ?? '—' }}</div>
                            <small class="text-muted">{{ $b->car?->brand?->name }}</small>
                        </td>
                        <td style="font-size:13px;font-weight:700;">
                            {{ number_format($b->car?->cash_price ?? 0) }}
                            <small class="text-muted fw-normal">{!! __('ريال') !!}</small>
                        </td>
                        <td style="font-size:12px;">
                            @if($isAdmin ?? false)
                            <form action="{{ route('crm.bookings.assign', $b) }}" method="POST" class="m-0">
                                @csrf @method('PATCH')
                                <div class="d-flex align-items-center gap-1 bg-light rounded-pill p-1 pe-2 border" style="width: fit-content; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--crm-red)'" onmouseout="this.style.borderColor='var(--crm-border)'">
                                    @if($b->employee)
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:24px;height:24px;font-size:10px;font-weight:bold;background:#1a3163;">
                                            {{ strtoupper(substr($b->employee->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-muted flex-shrink-0 border shadow-sm" style="width:24px;height:24px;font-size:12px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif
                                    <select name="employee_id" class="form-select form-select-sm border-0 shadow-none bg-transparent fw-bold p-0 ps-1" style="font-size:12px;color:var(--crm-text);width:auto;cursor:pointer;background-image:none;outline:none;" onchange="this.form.submit()">
                                        <option value="">{{ __('غير معين') }}</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ $b->assigned_to == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="bi bi-chevron-down text-muted" style="font-size:10px; pointer-events: none;"></i>
                                </div>
                            </form>
                            @else
                                <div class="d-flex align-items-center gap-1 bg-light rounded-pill p-1 pe-2 border" style="width: fit-content;">
                                    @if($b->employee)
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:24px;height:24px;font-size:10px;font-weight:bold;background:#1a3163;">
                                            {{ strtoupper(substr($b->employee->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-bold px-1" style="font-size:12px;color:var(--crm-text);">{{ $b->employee->name }}</span>
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-muted flex-shrink-0 border shadow-sm" style="width:24px;height:24px;font-size:12px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <span class="text-muted px-1" style="font-size:12px;">{{ __('غير معين') }}</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--crm-text-muted);">{{ $b->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('crm.bookings.status', $b) }}" method="POST" class="m-0" id="statusForm_{{ $b->id }}">
                                @csrf @method('PATCH')
                                @php
                                    $dotClass = match($b->status) {
                                        'new','pending'      => 'planned',
                                        'in_progress','contacted' => 'waiting',
                                        'sold','done'        => 'done',
                                        'rejected','closed'  => 'late',
                                        'pending_closure'    => 'waiting',
                                        default              => 'confirmed',
                                    };
                                @endphp
                                <select name="status"
                                        data-current-val="{{ $b->status }}"
                                        class="form-select form-select-sm border-0 shadow-none status-dot {{ $dotClass }}"
                                        style="font-size:12px;font-weight:700;padding-top:4px;padding-bottom:4px;width:auto;display:inline-block;"
                                        onchange="onBookingStatusSelectChange(this, '{{ $b->id }}', '{{ route('crm.bookings.status', $b) }}', '{{ $b->total_price ?? ($b->car?->cash_price ?? 0) }}', '{{ $b->interest_rate ?? 0 }}')">
                                    @foreach($statuses as $key => $s)
                                    <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                    @endforeach
                                </select>
                            </form>

                            @if($b->status === 'pending_closure' && (auth()->user()->hasRole('admin') || auth()->user()->role === 'admin'))
                            <div class="mt-1 d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-success py-0 px-2 rounded-pill" style="font-size:11px;" onclick="approveClosure('{{ $b->id }}', '{{ route('crm.bookings.status', $b) }}')">
                                    قبول الغلق
                                </button>
                                <button type="button" class="btn btn-sm btn-warning py-0 px-2 rounded-pill text-dark" style="font-size:11px;" onclick="rejectClosure('{{ $b->id }}', '{{ route('crm.bookings.status', $b) }}')">
                                    إرجاع لسيلز
                                </button>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2" title="{{ __('عرض') }}">
                                    <i class="bi bi-eye" style="font-size:14px;"></i>
                                </a>
                                <a href="https://wa.me/{{ $b->client_phone }}" target="_blank" class="btn btn-sm btn-light rounded-2" title="{{ __('واتساب') }}" style="color:#25D366;">
                                    <i class="bi bi-whatsapp" style="font-size:14px;"></i>
                                </a>
                                @can('bookings.delete')
                                <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST"
                                      onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب؟') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light rounded-2" style="color:var(--crm-red);" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash" style="font-size:14px;"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            {{ __('لا توجد طلبات حالياً') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards (visible on mobile only) --}}
        <div class="d-md-none p-3">
            @forelse($bookings as $b)
            @php
                $dotClassM = match($b->status) {
                    'new','pending'      => 'planned',
                    'in_progress','contacted' => 'waiting',
                    'sold','done'        => 'done',
                    'rejected','closed'  => 'late',
                    'pending_closure'    => 'waiting',
                    default              => 'confirmed',
                };
            @endphp
            <div class="mb-3 p-3 rounded-3" style="border:1px solid var(--crm-border);background:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="{{ route('crm.bookings.show', $b) }}" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#{{ $b->id }}</a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);">{{ $b->client_name }}</div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr">{{ $b->client_phone }}</div>
                        <div class="mt-1">
                            @if($b->contact_type === 'calculator' || $b->source === 'عميل حاسبة')
                                <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning-subtle px-2 py-1" style="font-size:10px;font-weight:700;">
                                    <i class="bi bi-calculator me-1"></i>{{ __('عميل حاسبة') }}
                                </span>
                            @elseif($b->contact_type === 'car_request' || $b->source === 'طلب سيارة')
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:10px;font-weight:700;">
                                    <i class="bi bi-car-front me-1"></i>{{ __('طلب سيارة') }}
                                </span>
                            @elseif($b->contact_type === 'financing' || (!empty($b->monthly_installment) && $b->monthly_installment > 0))
                                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size:10px;">
                                    <i class="bi bi-credit-card me-1"></i>{{ __('طلب تمويل') }}
                                </span>
                            @elseif($b->contact_type === 'companies')
                                <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-2 py-1" style="font-size:10px;">
                                    <i class="bi bi-building me-1"></i>{{ __('طلب شركات') }}
                                </span>
                            @else
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1" style="font-size:10px;">
                                    <i class="bi bi-cash-stack me-1"></i>{{ __('شراء كاش / أفراد') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('crm.bookings.status', $b) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status"
                                    data-current-val="{{ $b->status }}"
                                    class="form-select form-select-sm border-0 shadow-none status-dot {{ $dotClassM }}"
                                    style="font-size:11px;font-weight:700;padding:3px 8px;width:auto;"
                                    onchange="onBookingStatusSelectChange(this, '{{ $b->id }}', '{{ route('crm.bookings.status', $b) }}', '{{ $b->total_price ?? ($b->car?->cash_price ?? 0) }}', '{{ $b->interest_rate ?? 0 }}')">
                                @foreach($statuses as $key => $s)
                                <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                @endforeach
                            </select>
                        </form>

                        @if($b->status === 'pending_closure' && (auth()->user()->hasRole('admin') || auth()->user()->role === 'admin'))
                        <div class="mt-1 d-flex gap-1">
                            <button type="button" class="btn btn-xs btn-success py-0 px-2 rounded-pill" style="font-size:10px;" onclick="approveClosure('{{ $b->id }}', '{{ route('crm.bookings.status', $b) }}')">
                                قبول الغلق
                            </button>
                            <button type="button" class="btn btn-xs btn-warning py-0 px-2 rounded-pill text-dark" style="font-size:10px;" onclick="rejectClosure('{{ $b->id }}', '{{ route('crm.bookings.status', $b) }}')">
                                إرجاع لسيلز
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between" style="font-size:12px;color:var(--crm-text-muted);border-top:1px solid var(--crm-border);padding-top:10px;margin-top:8px;">
                    <div>
                        <i class="bi bi-car-front me-1"></i>
                        {{ $b->car?->brand?->name }} {{ $b->car?->name ?? '—' }}
                    </div>
                    <div>
                        @if($isAdmin ?? false)
                        <form action="{{ route('crm.bookings.assign', $b) }}" method="POST" class="m-0 d-inline-block">
                            @csrf @method('PATCH')
                            <div class="d-flex align-items-center gap-1 bg-light rounded-pill px-2 py-1 border" style="width: fit-content;">
                                @if($b->employee)
                                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:20px;height:20px;font-size:9px;font-weight:bold;background:#1a3163;">{{ strtoupper(substr($b->employee->name,0,1)) }}</span>
                                @else
                                    <i class="bi bi-person-circle text-muted"></i>
                                @endif
                                <select name="employee_id" class="form-select form-select-sm border-0 shadow-none bg-transparent p-0 fw-bold" style="font-size:11px;color:var(--crm-text);width:auto;display:inline-block;background-image:none;" onchange="this.form.submit()">
                                    <option value="">{{ __('غير معين') }}</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ $b->assigned_to == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        @else
                            <div class="d-flex align-items-center gap-1 bg-light rounded-pill px-2 py-1 border" style="width: fit-content;">
                                @if($b->employee)
                                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:20px;height:20px;font-size:9px;font-weight:bold;background:#1a3163;">{{ strtoupper(substr($b->employee->name,0,1)) }}</span>
                                    <span class="fw-bold" style="font-size:11px;color:var(--crm-text);">{{ $b->employee->name }}</span>
                                @else
                                    <span class="text-muted" style="font-size:11px;">{{ __('غير معين') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;">
                        <i class="bi bi-eye"></i> {{ __('عرض') }}
                    </a>
                    <a href="https://wa.me/{{ $b->client_phone }}" target="_blank" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;color:#25D366;">
                        <i class="bi bi-whatsapp"></i> {{ __('واتساب') }}
                    </a>
                    @can('bookings.delete')
                    <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST" onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب؟') }}')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-light rounded-2" style="color:var(--crm-red);font-size:12px;"><i class="bi bi-trash"></i></button>
                    </form>
                    @endcan
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                {{ __('لا توجد طلبات حالياً') }}
            </div>
            @endforelse
        </div>

        @if($bookings->hasPages())
        <div class="card-footer bg-white border-0 py-3" style="border-top:1px solid var(--crm-border)!important;">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>

    {{-- Floating Action Button --}}
    @can('bookings.create')
    <button class="btn btn-crm-primary position-fixed shadow-lg d-flex align-items-center justify-content-center hover-lift"
            style="bottom: 30px; left: 30px; width: 60px; height: 60px; border-radius: 50%; z-index: 1050; border: none; background: #1a3163;"
            data-bs-toggle="modal" data-bs-target="#createBookingModal" title="{{ __('إضافة طلب جديد') }}">
        <i class="bi bi-plus" style="font-size: 2rem; color: #fff;"></i>
    </button>
    @endcan

    {{-- Modal: إضافة طلب جديد --}}
    <div class="modal fade" id="createBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #FAF9F6;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" style="color: var(--crm-text);">{{ __('إضافة عميل / طلب') }}</h5>
                    <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('crm.bookings.store') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-4">
                        {{-- Customer Info Section --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">{{ __('اسم العميل') }} <span class="text-danger">*</span></label>
                                <input type="text" name="client_name" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" required placeholder="{{ __('اسم العميل') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">{{ __('رقم الهاتف') }} <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                    <span class="input-group-text bg-white border-0 text-muted" dir="ltr" style="font-size: 14px;">+966 🇸🇦</span>
                                    <input type="text" name="client_phone" class="form-control border-0" style="font-size: 14px;" required placeholder="5X XXX XXXX" dir="ltr">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">{{ __('البريد الإلكتروني') }}</label>
                                <input type="email" name="client_email" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" placeholder="{{ __('البريد الإلكتروني (اختياري)') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">{{ __('نوع الطلب') }}</label>
                                <select name="type" class="form-select form-select-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;">
                                    <option value="booking">{{ __('حجز سيارة') }}</option>
                                    <option value="loan">{{ __('تمويل') }}</option>
                                    <option value="test">{{ __('تجربة قيادة') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Car Info Section --}}
                        <div class="p-4 mb-4" style="background: #F4EFF0; border-radius: 16px;">
                            <h6 class="fw-bold mb-3" style="color: var(--crm-text);">{{ __('تفاصيل السيارة') }}</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-muted small">{{ __('السيارة المطلوبة') }}</label>
                                    <select name="car_id" class="form-select form-select-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" id="bookingCarSelect">
                                        <option value="">{{ __('اختر سيارة (أو اتركها فارغة)') }}</option>
                                        @foreach($cars as $car)
                                            <option value="{{ $car->id }}" data-price="{{ $car->cash_price }}" data-installment="{{ $car->min_installment }}">{{ $car->brand->name ?? '' }} {{ $car->name }} ({{ $car->year }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">{{ __('سعر السيارة الإجمالي') }}</label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="total_price" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">{{ __('الدفعة الأولى (إن وجدت)') }}</label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="down_payment" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">{{ __('القسط الشهري المتوقع') }}</label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="monthly_installment" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">{{ __('مدة التمويل (سنوات)') }}</label>
                                    <input type="number" name="duration_years" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" value="5">
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold text-muted small">{{ __('ملاحظات إضافية') }}</label>
                            <textarea name="notes" class="form-control bg-white border-0 shadow-sm" rows="2" style="border-radius: 12px; font-size: 14px;" placeholder="{{ __('إضافة ملاحظة...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2 flex-nowrap">
                        @can('bookings.create')
                        <button type="submit" class="btn flex-fill fw-bold py-3 text-white" style="background: #1a3163; border-radius: 12px;">{{ __('حفظ بيانات العميل') }}</button>
                        @endcan
                        <button type="button" class="btn btn-outline-secondary flex-fill fw-bold py-3" data-bs-dismiss="modal" style="border-radius: 12px; background: white;">{{ __('إلغاء') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: غلق الطلب / ملاحظة الغلق --}}
    <div class="modal fade" id="closeBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
                        <i class="bi bi-x-circle-fill fs-4"></i>
                        <span>{{ __('غلق الطلب') }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="closeBookingForm" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" id="close_modal_status" value="pending_closure">
                    <div class="modal-body px-4 py-3">
                        <p class="text-muted small mb-3" id="close_modal_desc">
                            {{ __('يرجى تدوين سبب إغلاق الطلب. سيتم تحويل الطلب لمراجعة الأدمن ليتم اعتماده أو إرجاعه.') }}
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">{{ __('ملاحظة / سبب الغلق') }} <span class="text-danger">*</span></label>
                            <textarea name="note" class="form-control border-1 shadow-sm" rows="3" style="border-radius:10px;" placeholder="{{ __('اكتب سبب الغلق هنا...') }}" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="submit" class="btn btn-danger flex-fill fw-bold py-2" style="border-radius:10px;">{{ __('تأكيد وإرسال') }}</button>
                        <button type="button" class="btn btn-light flex-fill fw-bold py-2" data-bs-dismiss="modal" style="border-radius:10px;">{{ __('إلغاء') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: تم الاستلام / تفاصيل البيع --}}
    <div class="modal fade" id="soldBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-success d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                        <span>{{ __('تم الاستلام / تفاصيل الطلب النهائي') }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="soldBookingForm" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="sold">
                    <div class="modal-body px-4 py-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">{{ __('السعر النهائي') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="final_price" id="sold_final_price" class="form-control shadow-sm" style="border-radius: 8px 0 0 8px;" required>
                                    <span class="input-group-text bg-light text-muted" style="font-size:12px;">ر.س</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">{{ __('سعر الفائدة (%)') }}</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="interest_rate" id="sold_interest_rate" class="form-control shadow-sm" style="border-radius: 8px 0 0 8px;">
                                    <span class="input-group-text bg-light text-muted" style="font-size:12px;">%</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">{{ __('العمولة') }}</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="commission" id="sold_commission" class="form-control shadow-sm" style="border-radius: 8px 0 0 8px;" placeholder="0">
                                    <span class="input-group-text bg-light text-muted" style="font-size:12px;">ر.س</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">{{ __('ملاحظات التسليم') }}</label>
                                <textarea name="note" class="form-control border-1 shadow-sm" rows="3" style="border-radius:10px;" placeholder="{{ __('أضف أي ملاحظات إضافية حول عملية التسليم...') }}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="submit" class="btn btn-success flex-fill fw-bold py-2" style="border-radius:10px;">{{ __('حفظ وتأكيد تم الاستلام') }}</button>
                        <button type="button" class="btn btn-light flex-fill fw-bold py-2" data-bs-dismiss="modal" style="border-radius:10px;">{{ __('إلغاء') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-4px) scale(1.05); box-shadow: 0 1rem 3rem rgba(227, 6, 19, 0.4) !important; }
    .modal-backdrop.show { opacity: 0.6; backdrop-filter: blur(4px); }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carSelect = document.getElementById('bookingCarSelect');
        const priceInput = document.querySelector('input[name="total_price"]');
        const installmentInput = document.querySelector('input[name="monthly_installment"]');

        if(carSelect) {
            carSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if(selectedOption.value) {
                    const price = selectedOption.getAttribute('data-price');
                    const installment = selectedOption.getAttribute('data-installment');

                    if(priceInput) priceInput.value = price || '';
                    if(installmentInput) installmentInput.value = installment || '';
                } else {
                    if(priceInput) priceInput.value = '';
                    if(installmentInput) installmentInput.value = '';
                }
            });
        }
    });

    function onBookingStatusSelectChange(selectEl, bookingId, actionUrl, defaultPrice, defaultInterest) {
        const val = selectEl.value;
        const currentVal = selectEl.getAttribute('data-current-val') || val;

        if (val === 'pending_closure' || val === 'closed') {
            selectEl.value = currentVal;
            const form = document.getElementById('closeBookingForm');
            form.action = actionUrl;
            document.getElementById('close_modal_status').value = val;
            
            const closeModal = new bootstrap.Modal(document.getElementById('closeBookingModal'));
            closeModal.show();
        } else if (val === 'sold') {
            selectEl.value = currentVal;
            const form = document.getElementById('soldBookingForm');
            form.action = actionUrl;
            document.getElementById('sold_final_price').value = defaultPrice || '';
            document.getElementById('sold_interest_rate').value = defaultInterest || '0';
            document.getElementById('sold_commission').value = '';
            
            const soldModal = new bootstrap.Modal(document.getElementById('soldBookingModal'));
            soldModal.show();
        } else {
            selectEl.form.submit();
        }
    }

    function approveClosure(bookingId, actionUrl) {
        const form = document.getElementById('closeBookingForm');
        form.action = actionUrl;
        document.getElementById('close_modal_status').value = 'closed';
        document.getElementById('close_modal_desc').innerText = 'أنت تقوم الآن بقبول غلق الطلب كـ أدمن.';
        const closeModal = new bootstrap.Modal(document.getElementById('closeBookingModal'));
        closeModal.show();
    }

    function rejectClosure(bookingId, actionUrl) {
        if(confirm('هل تريد إرجاع الطلب إلى موظف المبيعات؟')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="status" value="contacted">
                <input type="hidden" name="note" value="تم رفض الغلق من قبل الأدمن وإرجاع الطلب للموظف لمتابعته.">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
