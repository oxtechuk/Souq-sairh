@extends('partials.Layouts.crm-master')
@section('title', __('رسائل التواصل') . ' | GR Motors')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('رسائل التواصل') }}</span>
    </nav>

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold mb-0">{{ __('رسائل التواصل') }}</h5>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <span class="stat-badge green">+3%</span>
                <div class="stat-icon blue"><i class="bi bi-envelope"></i></div>
                <div class="stat-lbl">{{ __('الرسائل الجديدة') }}</div>
                <div class="stat-val">{{ number_format($messages->where('status', 'new')->count()) }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <span class="stat-badge orange">65%</span>
                <div class="stat-icon purple"><i class="bi bi-chat-dots"></i></div>
                <div class="stat-lbl">{{ __('إجمالي الرسائل') }}</div>
                <div class="stat-val">{{ number_format($messages->total()) }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Tabs + Search --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div class="crm-filter-tabs mb-0">
            <a href="{{ route('crm.contact-messages.index') }}"
               class="crm-filter-tab {{ !request('status') ? 'active' : '' }}">{{ __('الكل') }}</a>
            @foreach($statuses as $key => $s)
            <a href="{{ route('crm.contact-messages.index', ['status' => $key]) }}"
               class="crm-filter-tab {{ request('status') === $key ? 'active' : '' }}">{{ $s['label'] }}</a>
            @endforeach
        </div>
        <form method="GET" class="d-flex gap-2 align-items-center">
            <div style="position:relative;">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('بحث بالاسم أو الرسالة') }}"
                    style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;width:220px;">
                <i class="bi bi-search" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
            </div>
            <button type="submit" class="btn-crm-primary" style="padding:8px 16px;">{{ __('بحث') }}</button>
        </form>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0">{{ __('الرسائل') }}</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;">#</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('اسم المرسل') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الهاتف') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('البريد') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الرسالة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('التاريخ') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الحالة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($messages as $msg)
                    <tr>
                        <td class="px-4 fw-bold" style="font-size:13px;">#{{ $msg->id }}</td>
                        <td>
                            <div class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $msg->client_name }}</div>
                        </td>
                        <td style="font-size:13px;" dir="ltr">{{ $msg->client_phone ?? '—' }}</td>
                        <td style="font-size:12px;">{{ $msg->client_email ?? '—' }}</td>
                        <td style="font-size:12px;color:var(--crm-text-muted);max-width:250px;">
                            <div class="text-truncate" style="max-width:250px;" title="{{ $msg->message }}">
                                {{ $msg->message ?? '—' }}
                            </div>
                        </td>
                        <td style="font-size:12px;color:var(--crm-text-muted);">
                            {{ $msg->created_at->format('d/m/Y h:i A') }}
                        </td>
                        <td>
                            @php
                                $dotClass = match($msg->status) {
                                    'new'         => 'confirmed',
                                    'contacted'   => 'planned',
                                    'interested'  => 'waiting',
                                    'converted'   => 'done',
                                    'lost'        => 'late',
                                    default       => 'cancelled',
                                };
                            @endphp
                            <span class="status-dot {{ $dotClass }}">{{ $msg->status_label }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-sm btn-light rounded-2" title="{{ __('عرض الرسالة') }}"
                                        data-bs-toggle="modal" data-bs-target="#messageModal{{ $msg->id }}">
                                    <i class="bi bi-eye" style="font-size:14px;"></i>
                                </button>
                                @can('contacts.delete')
                                <form action="{{ route('crm.contact-messages.destroy', $msg) }}" method="POST"
                                      onsubmit="return confirm('{{ __('هل أنت متأكد؟') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light rounded-2" title="{{ __('حذف') }}"
                                            style="color:var(--crm-red);">
                                        <i class="bi bi-trash" style="font-size:14px;"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    {{-- Message Detail Modal --}}
                    <div class="modal fade" id="messageModal{{ $msg->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
                                <div class="modal-header border-0 pb-0 px-4 pt-4">
                                    <h5 class="modal-title fw-bold">{{ __('تفاصيل الرسالة') }}</h5>
                                    <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body px-4 py-4">
                                    <div class="mb-3">
                                        <small class="text-muted fw-bold">{{ __('الاسم') }}</small>
                                        <p class="fw-bold mb-0">{{ $msg->client_name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted fw-bold">{{ __('الهاتف') }}</small>
                                        <p class="mb-0" dir="ltr">{{ $msg->client_phone ?? '—' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted fw-bold">{{ __('البريد الإلكتروني') }}</small>
                                        <p class="mb-0">{{ $msg->client_email ?? '—' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted fw-bold">{{ __('الرسالة') }}</small>
                                        <p class="mb-0" style="white-space:pre-wrap;background:#F8F9FC;padding:12px;border-radius:8px;">{{ $msg->message ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <small class="text-muted fw-bold">{{ __('تاريخ الإرسال') }}</small>
                                        <p class="mb-0">{{ $msg->created_at->format('d/m/Y h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __('إغلاق') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            {{ __('لا توجد رسائل حالياً') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
        <div class="card-footer bg-white border-0 py-3" style="border-top:1px solid var(--crm-border)!important;">
            {{ $messages->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
