@extends('partials.Layouts.crm-master')
@section('title', __('تفاصيل الطلب') . ' #' . $booking->id . ' | Souq Siarh')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <a href="{{ route('crm.bookings.index') }}">{{ __('الطلبات') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('تفاصيل الطلب') }} #{{ $booking->id }}</span>
    </nav>

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">{{ __('تفاصيل الطلب') }} <span style="color:var(--crm-red);">#{{ $booking->id }}</span></h5>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('crm.bookings.index') }}" class="btn-crm-light">
                <i class="bi bi-arrow-right"></i>
                <span class="d-none d-md-inline">{{ __('العودة للطلبات') }}</span>
            </a>
            @can('bookings.view')
            <button onclick="window.print()" class="btn-crm-light">
                <i class="bi bi-printer"></i>
                <span class="d-none d-md-inline">{{ __('طباعة تفاصيل الطلب') }}</span>
            </button>
            @endcan
        </div>
    </div>

    {{-- Row 1: تفاصيل الطلب + تفاصيل الدفع --}}
    <div class="row g-3 mb-3">

        {{-- تفاصيل الطلب --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('تفاصيل الطلب') }}</h6>
                </div>
                <div class="card-body px-4 py-3">
                    @php
                        $typeLabel = match(true) {
                            $booking->contact_type === 'calculator' || $booking->source === 'عميل حاسبة' => 'عميل حاسبة',
                            $booking->contact_type === 'car_request' || $booking->source === 'طلب سيارة' => 'طلب سيارة',
                            $booking->contact_type === 'financing' || (!empty($booking->monthly_installment) && $booking->monthly_installment > 0) => 'طلب تمويل',
                            $booking->contact_type === 'companies' => 'طلب شركات',
                            default => 'شراء كاش / أفراد',
                        };

                        $orderRows = [
                            __('رقم الطلب')       => '#' . $booking->id,
                            __('اسم العميل')      => $booking->client_name,
                            __('جوال العميل')     => $booking->client_phone,
                        ];

                        if (!empty($booking->client_email)) {
                            $orderRows[__('البريد الإلكتروني')] = $booking->client_email;
                        }
                        if (!empty($booking->city)) {
                            $orderRows[__('المدينة')] = $booking->city;
                        }
                        if (!empty($booking->company_name)) {
                            $orderRows[__('اسم الشركة')] = $booking->company_name;
                        }
                        if (!empty($booking->num_cars)) {
                            $orderRows[__('عدد السيارات المطلوبة')] = $booking->num_cars;
                        }
                        if (!empty($booking->salary_range)) {
                            $orderRows[__('الراتب الشهري')] = $booking->salary_range;
                        }
                        if (!empty($booking->obligations_range)) {
                            $orderRows[__('الالتزامات الشهرية')] = $booking->obligations_range;
                        }

                        $orderRows[__('نوع الطلب')] = $typeLabel;
                        $orderRows[__('مصدر الطلب')] = $booking->source_label;
                        $orderRows[__('تاريخ الطلب')] = $booking->created_at->format('d/m/Y • H:i') . ($booking->created_at->format('A') == 'AM' ? ' ص' : ' م');
                    @endphp
                    @foreach($orderRows as $label => $value)
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                        <span style="font-size:13px;color:var(--crm-text-muted);">{{ $label }}</span>
                        <span style="font-size:13px;font-weight:700;color:var(--crm-text);" dir="{{ in_array($label, [__('جوال العميل'), __('البريد الإلكتروني')]) ? 'ltr' : 'inherit' }}">
                            @if($label === __('نوع الطلب'))
                                @if($booking->contact_type === 'calculator' || $booking->source === 'عميل حاسبة')
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1" style="font-size:11px;font-weight:700;">
                                        <i class="bi bi-calculator me-1"></i>عميل حاسبة
                                    </span>
                                @elseif($booking->contact_type === 'car_request' || $booking->source === 'طلب سيارة')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:11px;font-weight:700;">
                                        <i class="bi bi-car-front me-1"></i>طلب سيارة
                                    </span>
                                @else
                                    {{ $value }}
                                @endif
                            @elseif($label === __('مصدر الطلب'))
                                @php $sm = $booking->source_meta; @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('crm.bookings.source', $booking) }}" method="POST" class="d-inline-flex align-items-center m-0">
                                        @csrf @method('PATCH')
                                        <select name="source" class="form-select form-select-sm"
                                                style="font-size:11px;font-weight:700;border-radius:20px;padding:3px 26px 3px 10px;background-color:{{ $sm['badge_bg'] }};color:{{ $sm['badge_text'] }};border:1px solid {{ $sm['badge_border'] }};cursor:pointer;"
                                                title="{{ __('انقر لتعديل مصدر الطلب يدوياً') }}"
                                                onchange="this.form.submit()">
                                            @foreach(\App\Models\Booking::SOURCES as $srcKey => $srcData)
                                                <option value="{{ $srcKey }}" {{ $booking->normalized_source === $srcKey ? 'selected' : '' }}>
                                                    {{ $srcData['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                    @if($booking->utm_campaign)
                                        <span class="badge bg-light text-muted border" style="font-size:10px;" title="الحملة: {{ $booking->utm_campaign }}">
                                            <i class="bi bi-tag-fill me-1"></i>{{ Str::limit($booking->utm_campaign, 15) }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                {{ $value }}
                            @endif
                        </span>
                    </div>
                    @endforeach

                    @if(!empty($booking->notes))
                    <div class="mt-3 p-3 rounded-3" style="background:#FFFBEB;border:1px solid #FDE68A;">
                        <div class="fw-bold mb-1" style="font-size:12px;color:#92400E;">
                            <i class="bi bi-chat-left-text me-1"></i> {{ __('ملاحظات العميل / تفاصيل الطلب') }}
                        </div>
                        <div style="font-size:13px;color:#78350F;white-space:pre-line;">{{ $booking->notes }}</div>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between py-2 align-items-center">
                        <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('حالة الطلب') }}</span>
                        @php
                            $dotClass = match($booking->status) {
                                'new','pending'      => 'planned',
                                'in_progress','contacted' => 'waiting',
                                'sold','done'        => 'done',
                                'rejected','closed'  => 'late',
                                'pending_closure'    => 'waiting',
                                default              => 'confirmed',
                            };
                        @endphp
                        <span class="status-dot {{ $dotClass }}">{{ $booking->status_label }}</span>
                    </div>

                    {{-- تعيين مسؤول المبيعات --}}
                    <div class="mt-3 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                        <label style="font-size:12px;font-weight:700;margin-bottom:8px;display:block;">{{ __('مسؤول المبيعات') }}</label>
                        @if($isAdmin ?? false)
                        <form action="{{ route('crm.bookings.assign', $booking) }}" method="POST" class="d-flex align-items-center gap-2 w-100">
                            @csrf @method('PATCH')
                            <select name="employee_id" class="form-select form-select-sm border-0 shadow-none" style="background:#fff;border-radius:8px;font-size:13px;font-weight:700;">
                                <option value="">{{ __('غير معين') }}</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $booking->assigned_to == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm fw-bold rounded-2 text-white flex-shrink-0" style="background:var(--crm-text);font-size:12px;white-space:nowrap;padding: 6px 12px;">
                                {{ __('تحويل') }}
                            </button>
                        </form>
                        @else
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:28px;height:28px;font-size:11px;font-weight:bold;background:#1a3163;">
                                {{ strtoupper(substr($booking->employee?->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $booking->employee?->name ?? __('غير معين') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- تفاصيل الدفع --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('تفاصيل الدفع والمالية') }}</h6>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                        <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('إجمالي سعر السيارة المبدئي') }}</span>
                        <span style="font-size:13px;font-weight:700;">{{ number_format($booking->total_price) }} {!! __('ريال') !!}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                        <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('الدفعة الأولى (المقدم)') }}</span>
                        <span style="font-size:13px;font-weight:700;">{{ number_format($booking->down_payment) }} {!! __('ريال') !!}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                        <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('القسط الشهري المتوقع') }}</span>
                        <span style="font-size:13px;font-weight:700;">{{ number_format($booking->monthly_installment) }} {!! __('ريال') !!}</span>
                    </div>

                    @if($booking->final_price)
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);background:#F0FDF4;padding:4px 8px;border-radius:6px;">
                        <span style="font-size:13px;font-weight:700;color:#166534;">{{ __('السعر النهائي') }}</span>
                        <span style="font-size:14px;font-weight:800;color:#166534;">{{ number_format($booking->final_price, 2) }} {!! __('ريال') !!}</span>
                    </div>
                    @endif
                    @if($booking->interest_rate)
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                        <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('سعر الفائدة') }}</span>
                        <span style="font-size:13px;font-weight:700;">{{ $booking->interest_rate }}%</span>
                    </div>
                    @endif
                    @if($booking->commission)
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                        <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('العمولة') }}</span>
                        <span style="font-size:13px;font-weight:700;color:#1e40af;">{{ number_format($booking->commission, 2) }} {!! __('ريال') !!}</span>
                    </div>
                    @endif

                    {{-- حالة الطلب + تغيير الحالة --}}
                    <div class="mt-3 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                        <label style="font-size:12px;font-weight:700;margin-bottom:8px;display:block;">{{ __('تحديث حالة الطلب') }}</label>
                        <form action="{{ route('crm.bookings.status', $booking) }}" method="POST" class="d-flex align-items-center gap-2 w-100" id="showStatusForm">
                            @csrf @method('PATCH')
                            <select name="status"
                                    data-current-val="{{ $booking->status }}"
                                    onchange="onBookingStatusSelectChange(this, '{{ $booking->id }}', '{{ route('crm.bookings.status', $booking) }}', '{{ $booking->final_price ?? $booking->total_price }}', '{{ $booking->interest_rate }}')"
                                    class="form-select form-select-sm border-0 shadow-none" style="background:#fff;border-radius:8px;font-size:13px;font-weight:700;">
                                @foreach($statuses as $key => $s)
                                <option value="{{ $key }}" {{ $booking->status === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                @endforeach
                            </select>
                        </form>

                        @if($booking->status === 'pending_closure' && (auth()->user()->hasRole('admin') || auth()->user()->role === 'admin'))
                        <div class="mt-2 d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-success flex-fill fw-bold" onclick="approveClosure('{{ $booking->id }}', '{{ route('crm.bookings.status', $booking) }}')">
                                قبول الغلق
                            </button>
                            <button type="button" class="btn btn-sm btn-warning flex-fill fw-bold text-dark" onclick="rejectClosure('{{ $booking->id }}', '{{ route('crm.bookings.status', $booking) }}')">
                                إرجاع للسيلز
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- تفاصيل مصدر الطلب والحملة التسويقية --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-broadcast me-1" style="color:var(--crm-red);"></i>
                {{ __('تفاصيل مصدر الطلب والحملة التسويقية') }}
            </h6>
            @php $showMeta = $booking->source_meta; @endphp
            <span class="badge rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1"
                  style="font-size:12px; font-weight:700; background:{{ $showMeta['badge_bg'] }}; color:{{ $showMeta['badge_text'] }}; border:1px solid {{ $showMeta['badge_border'] }};">
                <i class="{{ $showMeta['icon'] }}"></i> {{ $showMeta['label'] }}
            </span>
        </div>
        <div class="card-body px-4 py-3">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('المنصة / المصدر') }}</div>
                    <div class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $showMeta['label'] }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('اسم الحملة (Campaign)') }}</div>
                    <div class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $booking->utm_campaign ?: '—' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('الوسيط (Medium)') }}</div>
                    <div class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $booking->utm_medium ?: '—' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('معرّف النقر (Click ID)') }}</div>
                    <div class="fw-bold" style="font-size:12px;color:var(--crm-text);word-break:break-all;" dir="ltr">{{ $booking->click_id ?: '—' }}</div>
                </div>
                @if(!empty($booking->referrer_url))
                <div class="col-12 mt-2 pt-2 border-top">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:2px;">{{ __('الرابط المرجعي (Referrer)') }}</div>
                    <div style="font-size:12px;color:var(--crm-text);" dir="ltr">{{ $booking->referrer_url }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- تفاصيل السيارة --}}
    @if($booking->car)
    <div class="card border-0 shadow-sm rounded-4 mb-3" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0">{{ __('تفاصيل السيارة') }}</h6>
        </div>
        <div class="card-body px-4 py-3">
            <div class="row g-0">
                <div class="col-6 col-md-3 py-2" style="border-{{ app()->getLocale()=='ar'?'left':'right' }}:1px solid var(--crm-border);">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('كود السيارة') }}</div>
                    <div style="font-size:13px;font-weight:700;">#{{ $booking->car->id }}</div>
                </div>
                <div class="col-6 col-md-3 py-2 px-3" style="border-{{ app()->getLocale()=='ar'?'left':'right' }}:1px solid var(--crm-border);">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('نوع السيارة') }}</div>
                    <div style="font-size:13px;font-weight:700;">{{ $booking->car->brand->name ?? '' }} {{ $booking->car->name }}</div>
                </div>
                <div class="col-6 col-md-3 py-2 px-3" style="border-{{ app()->getLocale()=='ar'?'left':'right' }}:1px solid var(--crm-border);">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('السنة') }}</div>
                    <div style="font-size:13px;font-weight:700;">{{ $booking->car->year ?? '—' }}</div>
                </div>
                <div class="col-6 col-md-3 py-2 px-3">
                    <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('سعر المعرض') }}</div>
                    <div style="font-size:13px;font-weight:700;">{{ number_format($booking->car->cash_price) }} {!! __('ريال') !!}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- المستندات والتصاريح --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0">{{ __('المستندات والتصاريح') }}</h6>
        </div>
        <div class="card-body p-4">
            {{-- نموذج رفع مستند --}}
            <form action="{{ route('crm.bookings.documents.store', $booking) }}" method="POST" enctype="multipart/form-data" class="mb-4 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="fw-bold mb-1" style="font-size:12px;">{{ __('اسم المستند (اختياري)') }}</label>
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="{{ __('مثال: الهوية الوطنية، تصريح المرور...') }}" style="border-radius:8px;font-size:13px;padding:8px 12px;">
                    </div>
                    <div class="col-md-5">
                        <label class="fw-bold mb-1" style="font-size:12px;">{{ __('الملف') }}</label>
                        <input type="file" name="file" class="form-control form-control-sm" required style="border-radius:8px;font-size:13px;padding:8px 12px;">
                    </div>
                    <div class="col-md-2">
                        @can('bookings.edit')
                        <button type="submit" class="btn-crm-primary w-100" style="padding:8px 16px;">{{ __('رفع') }}</button>
                        @endcan
                    </div>
                </div>
            </form>

            @if($booking->documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted fw-bold" style="font-size:12px;">{{ __('اسم المستند') }}</th>
                            <th class="text-muted fw-bold" style="font-size:12px;">{{ __('الموظف') }}</th>
                            <th class="text-muted fw-bold" style="font-size:12px;">{{ __('التاريخ') }}</th>
                            <th class="text-muted fw-bold" style="font-size:12px;">{{ __('إجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->documents as $doc)
                        <tr>
                            <td class="fw-bold" style="font-size:13px;">
                                <i class="bi bi-file-earmark-text text-primary me-1"></i>
                                {{ $doc->title }}
                            </td>
                            <td style="font-size:12px;">{{ $doc->employee->name ?? '—' }}</td>
                            <td style="font-size:12px;color:var(--crm-text-muted);">{{ $doc->created_at->format('d/m/Y • H:i') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-light rounded-2" title="{{ __('عرض/تحميل') }}">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    @can('bookings.delete')
                                    <form action="{{ route('crm.bookings.documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('{{ __('هل تريد حذف المستند؟') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light rounded-2" style="color:var(--crm-red);" title="{{ __('حذف') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4 opacity-50">
                <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                <p class="mb-0 small">{{ __('لا توجد مستندات مرفوعة بعد') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- سجل المتابعة --}}
    <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0">{{ __('سجل المتابعة') }}</h6>
        </div>
        <div class="card-body p-4">
            {{-- إضافة ملاحظة --}}
            <form action="{{ route('crm.bookings.note', $booking) }}" method="POST" class="mb-4 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                @csrf
                <div class="d-flex gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label class="fw-bold mb-1" style="font-size:12px;">{{ __('إضافة تحديث جديد') }}</label>
                        <textarea name="note" rows="2" required placeholder="{{ __('اكتب ملاحظة...') }}"
                                  style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:10px 14px;font-size:13px;font-family:'Cairo',sans-serif;outline:none;resize:none;"></textarea>
                    </div>
                    <div>
                        <select name="type" style="border:1px solid var(--crm-border);border-radius:8px;padding:9px 12px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;margin-bottom:4px;display:block;">
                            <option value="note">📌 {{ __('ملاحظة') }}</option>
                            <option value="call">📞 {{ __('مكالمة') }}</option>
                        </select>
                        @can('bookings.edit')
                        <button type="submit" class="btn-crm-primary w-100" style="padding:9px 16px;">{{ __('إضافة') }}</button>
                        @endcan
                    </div>
                </div>
            </form>

            {{-- Timeline --}}
            <div style="position:relative;padding-{{ app()->getLocale()=='ar'?'right':'left' }}:20px;border-{{ app()->getLocale()=='ar'?'right':'left' }}:2px solid var(--crm-border);">
                @forelse($booking->notes_list as $note)
                <div class="d-flex gap-3 mb-4 position-relative">
                    <div class="position-absolute" style="{{ app()->getLocale()=='ar'?'right':'left' }}:-9px;top:4px;width:16px;height:16px;border-radius:50%;background:#fff;border:2px solid {{ $note->type === 'call' ? '#12B76A' : ($note->type === 'status_change' ? '#2E90FA' : 'var(--crm-red)') }};"></div>
                    <div class="flex-grow-1">
                        <div class="p-3 rounded-3 border" style="background:#fff;border-color:var(--crm-border)!important;">
                            <p class="mb-2" style="font-size:13px;font-weight:600;color:var(--crm-text);white-space:pre-line;">{{ $note->note }}</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-dark border" style="font-size:11px;font-weight:600;">{{ $note->employee->name ?? __('النظام') }}</span>
                                <span style="font-size:11px;color:var(--crm-text-muted);"><i class="bi bi-clock me-1"></i>{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 opacity-50">
                    <i class="bi bi-chat-left-dots fs-1 d-block mb-2"></i>
                    <p class="mb-0 small">{{ __('لا توجد ملاحظات بعد') }}</p>
                </div>
                @endforelse
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
@endsection

@section('scripts')
<script>
window.onbeforeprint = () => document.title = 'طلب #{{ $booking->id }} — Souq Siarh';

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
