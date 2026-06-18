@extends('partials.Layouts.crm-master')
@section('title', __('التقرير الشهري') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

        {{-- Header — hidden on print --}}
        <div class="d-flex align-items-center justify-content-between mb-4 d-print-none bg-white p-3 rounded-4 shadow-sm border">
            <div>
                <h4 class="mb-1 fw-bold">{{ __('التقرير الشهري للنشاط') }}</h4>
                <p class="text-muted mb-0 small">{{ __('آخر 12 شهر — مقارنة بين طلبات التقسيط والعملاء المحتملين') }}</p>
            </div>
            @can('reports.view')
            <div class="d-flex gap-2">
                <form action="{{ route('crm.reports.export-monthly') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg shadow px-4 d-flex align-items-center gap-2 fw-bold" style="border-radius: 12px; border: none;">
                        <i class="bi bi-file-earmark-excel fs-5"></i> {{ __('تصدير Excel') }}
                    </button>
                </form>
                <button class="btn btn-danger btn-lg shadow px-4 d-flex align-items-center gap-2 fw-bold" onclick="window.print()" style="border-radius: 12px; background: var(--crm-red); border: none;">
                    <i class="bi bi-printer-fill fs-5"></i> {{ __('طباعة') }}
                </button>
            </div>
            @endcan
        </div>

        {{-- Print-Only Header --}}
        <div class="d-none d-print-block mb-4 overflow-visible">
            <div class="d-flex justify-content-between align-items-end border-bottom pb-3 mb-4" style="border-width:2px !important;border-color:var(--crm-red) !important;">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        @if(isset($settings['site_logo']))
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" style="height:50px;width:auto;" alt="Logo">
                        @endif
                        <h3 class="fw-bold mb-0" style="color:var(--crm-red);">
                            @if(isset($settings['site_name']))
                                {{ is_array($settings['site_name']) ? ($settings['site_name'][app()->getLocale()] ?? $settings['site_name']['ar'] ?? array_values($settings['site_name'])[0] ?? 'AutoCRM') : $settings['site_name'] }}
                            @else
                                AutoCRM
                            @endif
                        </h3>
                    </div>
                    <h5 class="text-dark fw-bold mb-1">{{ __('التقرير الشهري للنشاط') }}</h5>
                    <p class="text-muted small mb-0">{{ __('آخر 12 شهر') }}</p>
                </div>
                <div class="text-start" style="min-width:150px;">
                    <small class="text-muted d-block fw-bold">{{ __('تاريخ الطباعة') }}: {{ date('Y-m-d') }}</small>
                    <small class="text-muted d-block">{{ __('بواسطة') }}: {{ auth('employee')->user()->name ?? __('مدير النظام') }}</small>
                </div>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 border-bottom border-4 border-primary bg-light-subtle">
                    <div class="card-body p-4 text-center">
                        <div class="text-muted small fw-bold mb-2">{{ __('إجمالي طلبات التقسيط') }}</div>
                        <div class="fs-2 fw-black text-dark">{{ number_format($totalBookings) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 border-bottom border-4 border-info bg-light-subtle">
                    <div class="card-body p-4 text-center">
                        <div class="text-muted small fw-bold mb-2">{{ __('إجمالي العملاء المحتملين') }}</div>
                        <div class="fs-2 fw-black text-dark">{{ number_format($totalLeads) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 border-bottom border-4 border-warning bg-light-subtle">
                    <div class="card-body p-4 text-center">
                        <div class="text-muted small fw-bold mb-2">{{ __('الإجمالي الكلي') }}</div>
                        <div class="fs-2 fw-black text-dark">{{ number_format($totalBookings + $totalLeads) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" id="report-monthly">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-bold">{{ __('الشهر / السنة') }}</th>
                            <th class="py-3 text-muted fw-bold text-center">{{ __('طلبات التقسيط') }}</th>
                            <th class="py-3 text-muted fw-bold text-center">{{ __('عملاء محتملون') }}</th>
                            <th class="py-3 text-muted fw-bold text-center">{{ __('الإجمالي') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($months as $key => $row)
                            <tr>
                                <td class="px-4 fw-bold text-dark">{{ $row['label'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">{{ $row['bookings'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">{{ $row['leads'] }}</span>
                                </td>
                                <td class="text-center fw-bold">{{ $row['bookings'] + $row['leads'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Print-Only Footer --}}
    <div class="d-none d-print-block print-footer">
        <div class="d-flex justify-content-between align-items-center w-100 px-4">
            <div class="small text-muted">
                @if(isset($settings['footer_text']))
                    {{ is_array($settings['footer_text']) ? ($settings['footer_text'][app()->getLocale()] ?? $settings['footer_text']['ar'] ?? array_values($settings['footer_text'])[0] ?? '') : $settings['footer_text'] }}
                @else
                    {{ __('جميع الحقوق محفوظة © AutoCRM') }}
                @endif
            </div>
            <div class="small text-muted fw-bold">
                @if(isset($settings['site_name']))
                    {{ is_array($settings['site_name']) ? ($settings['site_name'][app()->getLocale()] ?? $settings['site_name']['ar'] ?? array_values($settings['site_name'])[0] ?? 'AutoCRM') : $settings['site_name'] }}
                @else
                    AutoCRM
                @endif
                 - {{ __('نظام إدارة علاقات العملاء') }}
            </div>
        </div>
    </div>

    <style>
        .bg-primary-subtle { background: #e7f1ff; }
        .bg-info-subtle { background: #d0f0fd; }
        .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .fw-black { font-weight: 900; }

        @media print {
            @page {
                size: A4 portrait;
                margin: 25mm 20mm 25mm 20mm;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff !important;
                padding: 10px 0;
                border-top: 1px solid #eee !important;
                z-index: 9999;
            }

            body {
                padding-bottom: 50px !important;
                font-family: 'Cairo', sans-serif !important;
                color: #000 !important;
                font-size: 13px !important;
            }

            html, body, .crm-shell, .crm-main, .crm-content, .container-fluid {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                background: #fff !important;
                overflow: visible !important;
            }

            .crm-sidebar, .crm-topbar, .crm-mob-overlay, .crm-mob-toggle, .d-print-none, .crm-breadcrumb {
                display: none !important;
            }

            .d-print-block {
                display: block !important;
                width: 100% !important;
                margin-bottom: 30px !important;
            }

            .card {
                border: 1px solid #eee !important;
                border-radius: 12px !important;
                padding: 20px !important;
                margin-bottom: 25px !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                background: #fff !important;
            }

            .row {
                display: flex !important;
                flex-wrap: wrap !important;
            }
            .col-md-4 { width: 33.333% !important; }

            .table { width: 100% !important; border-collapse: collapse !important; }
            .table th {
                background-color: #f1f1f1 !important;
                color: #000 !important;
                font-size: 12px !important;
                border: 1px solid #ddd !important;
            }
            .table td {
                border: 1px solid #eee !important;
                font-size: 12px !important;
            }
            .badge {
                border: 1px solid currentColor !important;
            }
        }
    </style>
@endsection
