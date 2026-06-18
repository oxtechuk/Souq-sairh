@extends('partials.Layouts.crm-master')
@section('title', __('تقرير الأداء والمبيعات الشامل') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

        <div class="d-flex align-items-center justify-content-between mb-4 d-print-none bg-white p-3 rounded-4 shadow-sm border">
            <div>
                <h4 class="mb-1 fw-bold text-dark"> {{ __('تقرير الأداء والمبيعات الشامل') }}</h4>
                <p class="text-muted mb-0 small">{{ __('تحليل بيانات الحجوزات والمبيعات وموظفي المبيعات ومصادر العملاء') }}</p>
            </div>
            @can('reports.view')
            <div class="d-flex gap-2">
                <form action="{{ route('crm.reports.export-bookings') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="from" value="{{ $from }}">
                    <input type="hidden" name="to" value="{{ $to }}">
                    <button type="submit" class="btn btn-success btn-lg shadow px-4 d-flex align-items-center gap-2 fw-bold" style="border-radius: 12px; border: none;">
                        <i class="bi bi-file-earmark-excel fs-5"></i> {{ __('تصدير Excel') }}
                    </button>
                </form>
                <button class="btn btn-danger btn-lg shadow px-4 d-flex align-items-center gap-2 fw-bold" onclick="printAllReports()" style="border-radius: 12px; background: var(--crm-red); border: none;">
                    <i class="bi bi-printer-fill fs-5"></i> {{ __('طباعة') }}
                </button>
            </div>
            @endcan
        </div>

        {{-- Print-Only Header --}}
        <div class="d-none d-print-block mb-4 overflow-visible">
            <div class="d-flex justify-content-between align-items-end border-bottom pb-3 mb-4" style="border-width: 2px !important; border-color: var(--crm-red) !important;">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        @if(isset($settings['site_logo']))
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" style="height: 50px; width: auto;" alt="Logo">
                        @endif
                        <h3 class="fw-bold mb-0" style="color: var(--crm-red);">
                            @if(isset($settings['site_name']))
                                {{ is_array($settings['site_name']) ? ($settings['site_name'][app()->getLocale()] ?? $settings['site_name']['ar'] ?? array_values($settings['site_name'])[0] ?? 'AutoCRM Reports') : $settings['site_name'] }}
                            @else
                                AutoCRM Reports
                            @endif
                        </h3>
                    </div>
                    <h5 class="text-dark fw-bold mb-1" id="current-print-title">{{ __('تقرير الأداء والمبيعات الشامل') }}</h5>
                    <p class="text-muted small mb-0">{{ __('الفترة من') }} {{ $from }} {{ __('إلى') }} {{ $to }}</p>
                </div>
                <div class="text-start" style="min-width: 150px;">
                    <small class="text-muted d-block fw-bold">{{ __('تاريخ الطباعة') }}: {{ date('Y-m-d') }}</small>
                    <small class="text-muted d-block">{{ __('بواسطة') }}: {{ auth('employee')->user()->name ?? __('مدير النظام') }}</small>
                    <small class="text-muted d-block">{{ __('عدد الصفحات') }}: {{ __('تلقائي') }}</small>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden d-print-none">
            <div class="card-body p-4 bg-light-subtle">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">{{ __('من تاريخ') }}</label>
                        <input type="date" name="from" class="form-control border-0 shadow-xs" value="{{ $from }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">{{ __('إلى تاريخ') }}</label>
                        <input type="date" name="to" class="form-control border-0 shadow-xs" value="{{ $to }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3 py-2">
                            <i class="bi bi-funnel me-1"></i> {{ __('تحديث التقرير') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="all-reports-container">
            <!-- 1. الأداء المالي والمبيعات -->
            <div id="report-financial" class="report-section mb-5 bg-white p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2" style="color:var(--crm-red)"></i> {{ __('1. الأداء المالي') }}</h5>
                    <div class="d-flex gap-2">
                        <form action="{{ route('crm.reports.export-bookings') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <button type="submit" class="btn btn-sm btn-success border-0 d-print-none"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        </form>
                        <button class="btn btn-sm btn-light border d-print-none print-btn-trigger" onclick="printReport('report-financial', 'تقرير الأداء المالي')"><i class="bi bi-printer"></i> طباعة A4</button>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-xs rounded-4 h-100 border-bottom border-4 border-primary bg-light-subtle">
                            <div class="card-body p-4 text-center">
                                <div class="text-muted small fw-bold mb-2">{{ __('إجمالي الحجوزات') }}</div>
                                <div class="fs-2 fw-black text-dark">{{ number_format($financial['total_bookings']) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-xs rounded-4 h-100 border-bottom border-4 border-success bg-light-subtle">
                            <div class="card-body p-4 text-center">
                                <div class="text-muted small fw-bold mb-2">{{ __('مبيعات مغلقة') }}</div>
                                <div class="fs-2 fw-black text-success">{{ number_format($financial['total_sold']) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-xs rounded-4 h-100 border-bottom border-4 border-warning bg-light-subtle">
                            <div class="card-body p-4 text-center">
                                <div class="text-muted small fw-bold mb-2">{{ __('قيد الانتظار') }}</div>
                                <div class="fs-2 fw-black text-warning">{{ number_format($financial['total_pending']) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-xs rounded-4 h-100 border-bottom border-4 border-danger bg-light-subtle">
                            <div class="card-body p-4 text-center">
                                <div class="text-muted small fw-bold mb-2">{{ __('مرفوض') }}</div>
                                <div class="fs-2 fw-black text-danger">{{ number_format($financial['total_rejected']) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-xs rounded-4 h-100 border-bottom border-4 border-info bg-light-subtle">
                            <div class="card-body p-4 text-center">
                                <div class="text-muted small fw-bold mb-2">{{ __('نسبة التحويل') }}</div>
                                <div class="fs-2 fw-black text-info">{{ $financial['conversion_rate'] }}%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-xs rounded-4 h-100 border-bottom border-4 border-dark bg-light-subtle">
                            <div class="card-body p-4 text-center">
                                <div class="text-muted small fw-bold mb-2">{{ __('إجمالي الإيرادات') }}</div>
                                <div class="fs-3 fw-bold text-dark">{{ number_format($financial['total_revenue']) }} <span class="fs-6 text-muted">ج.م</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-muted fw-bold">{{ __('الحالة') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('العدد') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('النسبة') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @php
                                $statusLabels = [
                                    'new' => ['label' => 'جديد', 'color' => 'primary'],
                                    'contacted' => ['label' => 'تم التواصل', 'color' => 'info'],
                                    'interested' => ['label' => 'مهتم', 'color' => 'warning'],
                                    'rejected' => ['label' => 'مرفوض', 'color' => 'danger'],
                                    'sold' => ['label' => 'تم البيع ✓', 'color' => 'success'],
                                ];
                            @endphp
                            @foreach ($statusLabels as $key => $info)
                                @php $count = $financial['status_breakdown'][$key] ?? 0; @endphp
                                <tr>
                                    <td class="px-4"><span class="badge bg-{{ $info['color'] }}-subtle text-{{ $info['color'] }} px-3 py-2 rounded-pill">{{ $info['label'] }}</span></td>
                                    <td class="text-center fw-bold">{{ $count }}</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; max-width: 120px;">
                                                <div class="progress-bar bg-{{ $info['color'] }}" style="width: {{ $financial['total_bookings'] > 0 ? ($count / $financial['total_bookings']) * 100 : 0 }}%"></div>
                                            </div>
                                            <span class="small fw-bold text-muted">{{ $financial['total_bookings'] > 0 ? round(($count / $financial['total_bookings']) * 100, 1) : 0 }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. تحليل خطط التقسيط -->
            <div id="report-installments" class="report-section mb-5 bg-white p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-calculator me-2" style="color:var(--crm-red)"></i> {{ __('2. تحليل خطط التقسيط') }}</h5>
                    <button class="btn btn-sm btn-light border d-print-none print-btn-trigger" onclick="printReport('report-installments', 'تحليل خطط التقسيط')"><i class="bi bi-printer"></i> طباعة A4</button>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card border-0 bg-light-subtle shadow-xs rounded-4 h-100">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <div class="avatar-md bg-white rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width:50px;height:50px;">
                                    <i class="bi bi-cash-stack fs-4" style="color:var(--crm-red)"></i>
                                </div>
                                <div>
                                    <div class="text-muted small fw-bold mb-1">{{ __('متوسط الدفعة المقدمة') }}</div>
                                    <div class="fs-4 fw-bold text-dark">{{ number_format($installments['avg_down_payment']) }} <span class="fs-6 text-muted">ج.م</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light-subtle shadow-xs rounded-4 h-100">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <div class="avatar-md bg-white rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width:50px;height:50px;">
                                    <i class="bi bi-calendar3 fs-4" style="color:var(--crm-red)"></i>
                                </div>
                                <div>
                                    <div class="text-muted small fw-bold mb-1">{{ __('متوسط مدة التقسيط') }}</div>
                                    <div class="fs-4 fw-bold text-dark">{{ round($installments['avg_duration'], 1) }} <span class="fs-6 text-muted">{{ __('سنوات') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light-subtle shadow-xs rounded-4 h-100">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <div class="avatar-md bg-white rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width:50px;height:50px;">
                                    <i class="bi bi-calendar-check fs-4" style="color:var(--crm-red)"></i>
                                </div>
                                <div>
                                    <div class="text-muted small fw-bold mb-1">{{ __('متوسط القسط الشهري') }}</div>
                                    <div class="fs-4 fw-bold text-dark">{{ number_format($installments['avg_monthly']) }} <span class="fs-6 text-muted">ج.م</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. أداء فريق المبيعات -->
            <div id="report-employees" class="report-section mb-5 bg-white p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-people me-2" style="color:var(--crm-red)"></i> {{ __('3. أداء فريق المبيعات') }}</h5>
                    <button class="btn btn-sm btn-light border d-print-none print-btn-trigger" onclick="printReport('report-employees', 'أداء فريق المبيعات')"><i class="bi bi-printer"></i> طباعة A4</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 py-3 text-muted fw-bold">#</th>
                                <th class="px-3 py-3 text-muted fw-bold">{{ __('الموظف') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الحجوزات') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('المبيعات') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('نسبة التحويل') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الإيرادات') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('متوسط الصفقة') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($employees as $i => $emp)
                                <tr>
                                    <td class="px-3 text-muted small">{{ $i + 1 }}</td>
                                    <td class="px-3 fw-bold text-dark">{{ $emp->employee->name ?? __('غير محدد') }}</td>
                                    <td class="text-center fw-bold">{{ $emp->total_bookings }}</td>
                                    <td class="text-center"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">{{ $emp->total_sold }}</span></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; max-width: 80px;">
                                                <div class="progress-bar bg-{{ $emp->conversion_rate > 50 ? 'success' : ($emp->conversion_rate > 20 ? 'warning' : 'danger') }}" style="width: {{ $emp->conversion_rate }}%"></div>
                                            </div>
                                            <span class="small fw-bold text-muted">{{ $emp->conversion_rate }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold text-dark">{{ number_format($emp->total_revenue) }} <span class="small text-muted">ج.م</span></td>
                                    <td class="text-center text-muted">{{ $emp->avg_deal > 0 ? number_format($emp->avg_deal) . ' ج.م' : '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">{{ __('لا توجد بيانات') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. أكثر السيارات طلباً -->
            <div id="report-cars" class="report-section mb-5 bg-white p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-car-front me-2" style="color:var(--crm-red)"></i> {{ __('4. أكثر السيارات طلباً') }}</h5>
                    <button class="btn btn-sm btn-light border d-print-none print-btn-trigger" onclick="printReport('report-cars', 'أكثر السيارات طلباً')"><i class="bi bi-printer"></i> طباعة A4</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 py-3 text-muted fw-bold">#</th>
                                <th class="px-3 py-3 text-muted fw-bold">{{ __('السيارة') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الماركة') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الطلبات') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('المبيعات') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('نسبة التحويل') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الإيرادات') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($topCars as $i => $carStat)
                                <tr>
                                    <td class="px-3 text-muted small">{{ $i + 1 }}</td>
                                    <td class="px-3 fw-bold text-dark">{{ $carStat->car->name ?? __('غير محدد') }}</td>
                                    <td class="text-center text-muted">{{ $carStat->car->brand->name ?? '—' }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $carStat->total_bookings }}</td>
                                    <td class="text-center"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">{{ $carStat->total_sold }}</span></td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $carStat->conversion_rate > 50 ? 'success' : 'secondary' }}-subtle text-{{ $carStat->conversion_rate > 50 ? 'success' : 'secondary' }} px-3 py-2 rounded-pill small">
                                            {{ $carStat->conversion_rate }}%
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-dark">{{ number_format($carStat->total_revenue) }} <span class="small text-muted">ج.م</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">{{ __('لا توجد بيانات') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. مصادر العملاء -->
            <div id="report-sources" class="report-section mb-5 bg-white p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-funnel me-2" style="color:var(--crm-red)"></i> {{ __('5. مصادر العملاء') }}</h5>
                    <button class="btn btn-sm btn-light border d-print-none print-btn-trigger" onclick="printReport('report-sources', 'مصادر العملاء')"><i class="bi bi-printer"></i> طباعة A4</button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-muted fw-bold">{{ __('المصدر') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الإجمالي') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('جديد') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('مهتم') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('مرفوض') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('مبيعات') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('نسبة النجاح') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($sourcesReport as $src)
                                <tr>
                                    <td class="px-4 fw-bold text-dark">{{ $src->source }}</td>
                                    <td class="text-center fw-bold">{{ $src->total_bookings }}</td>
                                    <td class="text-center text-primary fw-bold">{{ $src->total_new }}</td>
                                    <td class="text-center text-warning fw-bold">{{ $src->total_interested }}</td>
                                    <td class="text-center text-danger fw-bold">{{ $src->total_rejected }}</td>
                                    <td class="text-center"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">{{ $src->total_sold }}</span></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; max-width: 80px;">
                                                <div class="progress-bar bg-{{ $src->conversion_rate > 50 ? 'success' : 'secondary' }}" style="width: {{ $src->conversion_rate }}%"></div>
                                            </div>
                                            <span class="small fw-bold text-muted">{{ $src->conversion_rate }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">{{ __('لا توجد بيانات') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 6. قائمة الحجوزات التفصيلية -->
            <div id="report-detail" class="report-section mb-5 bg-white p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-list-table me-2" style="color:var(--crm-red)"></i> {{ __('6. قائمة الحجوزات التفصيلية') }}</h5>
                    <div class="d-flex gap-2">
                        <form action="{{ route('crm.reports.export-bookings') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <button type="submit" class="btn btn-sm btn-success border-0 d-print-none"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        </form>
                        <button class="btn btn-sm btn-light border d-print-none print-btn-trigger" onclick="printReport('report-detail', 'قائمة الحجوزات التفصيلية')"><i class="bi bi-printer"></i> طباعة</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="bookingsDetailTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 py-3 text-muted fw-bold">#</th>
                                <th class="px-3 py-3 text-muted fw-bold">{{ __('العميل') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ ('الهاتف') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('السيارة') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الحالة') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الموظف') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('المصدر') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('التاريخ') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($allBookings as $i => $b)
                                <tr>
                                    <td class="px-3 text-muted small">{{ $i + 1 }}</td>
                                    <td class="px-3 fw-bold text-dark">{{ $b->client_name }}</td>
                                    <td class="text-center text-muted">{{ $b->client_phone }}</td>
                                    <td class="text-center">{{ $b->car?->name ?? '—' }}</td>
                                    <td class="text-center"><span class="badge bg-{{ $b->status_color }}-subtle text-{{ $b->status_color }} px-3 py-2 rounded-pill">{{ $b->status_label }}</span></td>
                                    <td class="text-center text-muted">{{ $b->employee?->name ?? '—' }}</td>
                                    <td class="text-center text-muted">{{ $b->source ?? '—' }}</td>
                                    <td class="text-center text-muted small">{{ $b->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted py-4">{{ __('لا توجد حجوزات في هذه الفترة') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($allBookings->count() > 0)
                    <div class="text-muted small mt-2 text-center">{{ __('إجمالي') }}: {{ $allBookings->count() }} {{ __('حجز') }}</div>
                @endif
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
        .bg-warning-subtle { background: #fff3cd; }
        .bg-danger-subtle { background: #f8d7da; }
        .bg-success-subtle { background: #d1e7dd; }
        .bg-secondary-subtle { background: #e9ecef; }

        .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .fw-black { font-weight: 900; }

        @media print {
            @page {
                size: A4 portrait;
                margin: 22mm 18mm 22mm 18mm;
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
                padding: 8px 0;
                border-top: 1px solid #eee !important;
                z-index: 9999;
            }

            body {
                padding-bottom: 45px !important;
                font-family: 'Cairo', sans-serif !important;
                color: #000 !important;
                font-size: 11px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            html, body, .crm-shell, .crm-main, .crm-content, .container-fluid, #all-reports-container {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 100% !important;
                background: #fff !important;
                overflow: visible !important;
            }

            .crm-sidebar, .crm-topbar, .crm-mob-overlay, .crm-mob-toggle, .d-print-none, .print-btn-trigger, .crm-breadcrumb {
                display: none !important;
            }

            /* Full-report print: page break before each section */
            body:not(.print-single-section) .report-section {
                page-break-before: always !important;
                break-before: page !important;
            }
            body:not(.print-single-section) .report-section:first-of-type {
                page-break-before: avoid !important;
                break-before: avoid !important;
            }

            .report-section {
                border: 1px solid #eee !important;
                border-radius: 8px !important;
                padding: 18px !important;
                margin-bottom: 20px !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                display: block !important;
                width: 100% !important;
                background: #fff !important;
                overflow: visible !important;
            }

            .card {
                border: 1px solid #eee !important;
                border-radius: 8px !important;
                padding: 14px !important;
                margin-bottom: 16px !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                background: #fff !important;
                overflow: visible !important;
            }

            .card-body {
                padding: 8px !important;
            }

            .d-print-block {
                display: block !important;
                width: 100% !important;
                margin-bottom: 20px !important;
            }

            body.print-single-section .report-section { display: none !important; }
            body.print-single-section .report-section.print-this { display: block !important; }

            .row {
                display: flex !important;
                flex-wrap: wrap !important;
                margin-right: -8px !important;
                margin-left: -8px !important;
                flex-direction: row !important;
            }
            .row > [class*="col-"] {
                padding-right: 8px !important;
                padding-left: 8px !important;
                flex-shrink: 0 !important;
            }

            .col-md-4, .col-4 { width: 33.3333% !important; }
            .col-md-6, .col-6 { width: 50% !important; }
            .col-md-12, .col-12 { width: 100% !important; }
            .col-md-7 { width: 58.333% !important; }
            .col-md-5 { width: 41.666% !important; }
            .col-md-8 { width: 66.666% !important; }

            .border-bottom { border-bottom: 1px solid #ddd !important; }
            .mb-4 { margin-bottom: 14px !important; }
            .mb-5 { margin-bottom: 18px !important; }
            .gap-2 { gap: 6px !important; }
            .gap-3 { gap: 10px !important; }

            .table-responsive {
                overflow: visible !important;
            }

            .table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 10px !important;
            }
            .table th {
                background-color: #f1f1f1 !important;
                color: #000 !important;
                border: 1px solid #ddd !important;
                padding: 6px 8px !important;
                font-size: 10px !important;
            }
            .table td {
                border: 1px solid #eee !important;
                padding: 5px 8px !important;
                font-size: 10px !important;
            }
            .table tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .badge {
                border: 1px solid currentColor !important;
                padding: 3px 8px !important;
                font-size: 9px !important;
            }

            .progress {
                border: 1px solid #ddd !important;
                background: #f5f5f5 !important;
            }
            .progress-bar {
                border-radius: 3px !important;
            }

            h5 {
                font-size: 13px !important;
                margin-bottom: 8px !important;
            }
            .fs-2 { font-size: 18px !important; }
            .fs-3 { font-size: 15px !important; }
            .fs-4 { font-size: 13px !important; }
            .fs-6 { font-size: 9px !important; }
            .small { font-size: 9px !important; }
            .fw-black { font-weight: 900 !important; }
            .fw-bold { font-weight: 700 !important; }
            .text-muted { color: #666 !important; }
        }
    </style>
@endsection

@section('scripts')
<script>
    function printReport(sectionId, title) {
        document.getElementById('current-print-title').innerText = title;
        document.body.classList.add('print-single-section');
        const section = document.getElementById(sectionId);
        section.classList.add('print-this');
        window.print();
        window.setTimeout(() => {
            document.body.classList.remove('print-single-section');
            section.classList.remove('print-this');
        }, 500);
    }

    function printAllReports() {
        document.getElementById('current-print-title').innerText = "{{ __('تقرير الأداء والمبيعات الشامل') }}";
        window.print();
    }
</script>
@endsection
