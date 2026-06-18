<?php

namespace App\Http\Controllers\CRM;

use App\Exports\BookingsExport;
use App\Exports\MonthlyExport;
use App\Exports\SourcesExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactSource;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function bookings(Request $request)
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $from = $request->input('from', now()->subMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $base = Booking::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        // 1. Financial Performance
        $statuses = ['new', 'contacted', 'interested', 'rejected', 'sold'];
        $statusBreakdown = [];
        foreach ($statuses as $s) {
            $statusBreakdown[$s] = (clone $base)->where('status', $s)->count();
        }

        $financial = [
            'total_bookings' => (clone $base)->count(),
            'total_sold' => $statusBreakdown['sold'],
            'total_rejected' => $statusBreakdown['rejected'],
            'total_pending' => $statusBreakdown['new'] + $statusBreakdown['contacted'] + $statusBreakdown['interested'],
            'total_down_payment' => (clone $base)->where('status', 'sold')->sum('down_payment'),
            'total_remaining' => (clone $base)->where('status', 'sold')
                ->selectRaw('SUM(monthly_installment * (duration_years * 12)) as total')->value('total') ?? 0,
            'total_revenue' => (clone $base)->where('status', 'sold')->sum('total_price'),
            'conversion_rate' => 0,
            'status_breakdown' => $statusBreakdown,
        ];
        $financial['conversion_rate'] = $financial['total_bookings'] > 0
            ? round(($financial['total_sold'] / $financial['total_bookings']) * 100, 1)
            : 0;

        // 2. Employee Performance
        $employees = (clone $base)->whereNotNull('assigned_to')
            ->selectRaw('
                assigned_to,
                count(*) as total_bookings,
                sum(case when status = \'sold\' then 1 else 0 end) as total_sold,
                sum(case when status = \'sold\' then total_price else 0 end) as total_revenue
            ')
            ->groupBy('assigned_to')
            ->with('employee:id,name')
            ->get()
            ->map(function ($e) {
                $e->conversion_rate = $e->total_bookings > 0
                    ? round(($e->total_sold / $e->total_bookings) * 100, 1)
                    : 0;
                $e->avg_deal = $e->total_sold > 0
                    ? round($e->total_revenue / $e->total_sold)
                    : 0;
                return $e;
            })
            ->sortByDesc('total_sold')
            ->values();

        // 3. Car Popularity
        $topCars = (clone $base)->whereNotNull('car_id')
            ->selectRaw('
                car_id,
                count(*) as total_bookings,
                sum(case when status = \'sold\' then 1 else 0 end) as total_sold,
                sum(case when status = \'sold\' then total_price else 0 end) as total_revenue
            ')
            ->groupBy('car_id')
            ->orderByDesc('total_bookings')
            ->take(10)
            ->with('car.brand')
            ->get()
            ->map(function ($c) {
                $c->conversion_rate = $c->total_bookings > 0
                    ? round(($c->total_sold / $c->total_bookings) * 100, 1)
                    : 0;
                return $c;
            });

        // 4. Installments Analysis
        $installments = [
            'avg_down_payment' => (clone $base)->where('down_payment', '>', 0)->avg('down_payment') ?? 0,
            'avg_duration' => (clone $base)->where('duration_years', '>', 0)->avg('duration_years') ?? 0,
            'avg_monthly' => (clone $base)->where('monthly_installment', '>', 0)->avg('monthly_installment') ?? 0,
        ];

        // 5. Sources Analysis
        $sourcesReport = (clone $base)->whereNotNull('source')
            ->selectRaw('
                source,
                count(*) as total_bookings,
                sum(case when status = \'sold\' then 1 else 0 end) as total_sold,
                sum(case when status = \'rejected\' then 1 else 0 end) as total_rejected,
                sum(case when status = \'interested\' then 1 else 0 end) as total_interested,
                sum(case when status not in (\'sold\',\'rejected\',\'interested\') then 1 else 0 end) as total_new
            ')
            ->groupBy('source')
            ->orderByDesc('total_bookings')
            ->get()
            ->map(function ($s) {
                $s->conversion_rate = $s->total_bookings > 0
                    ? round(($s->total_sold / $s->total_bookings) * 100, 1)
                    : 0;
                return $s;
            });

        // 6. All bookings detail for the detail table
        $allBookings = (clone $base)
            ->with(['car.brand', 'employee'])
            ->orderByDesc('created_at')
            ->get();

        return view('crm.reports.bookings', compact(
            'from', 'to', 'financial', 'employees', 'topCars',
            'installments', 'sourcesReport', 'settings', 'allBookings'
        ));
    }

    /** تقرير مصادر التواصل (عملاء محتملون + طلبات الموقع) */
    public function sources()
    {
        $leadBySource = Lead::query()
            ->selectRaw('contact_source_id, count(*) as total')
            ->groupBy('contact_source_id')
            ->pluck('total', 'contact_source_id');

        $contactSources = ContactSource::orderBy('sort_order')->orderBy('id')->get();

        $bookingBySource = Booking::query()
            ->selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source');

        $leadsTotal = Lead::count();
        $bookingsTotal = Booking::count();

        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        return view('crm.reports.sources', compact(
            'leadBySource',
            'contactSources',
            'bookingBySource',
            'leadsTotal',
            'bookingsTotal',
            'settings'
        ));
    }

    /** تقرير شهري: نشاط الطلبات والعملاء المحتملين */
    public function monthly()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        $start = now()->subMonths(11)->startOfMonth();

        $bookingRows = Booking::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at']);

        $leadRows = Lead::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at']);

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->copy()->subMonths($i)->startOfMonth();
            $key = $m->format('Y-m');
            $months[$key] = [
                'label' => $m->translatedFormat('M Y'),
                'bookings' => 0,
                'leads' => 0,
            ];
        }

        foreach ($bookingRows as $b) {
            $key = Carbon::parse($b->created_at)->format('Y-m');
            if (isset($months[$key])) {
                $months[$key]['bookings']++;
            }
        }
        foreach ($leadRows as $l) {
            $key = Carbon::parse($l->created_at)->format('Y-m');
            if (isset($months[$key])) {
                $months[$key]['leads']++;
            }
        }

        $totalBookings = collect($months)->sum('bookings');
        $totalLeads = collect($months)->sum('leads');

        return view('crm.reports.monthly', compact('months', 'totalBookings', 'totalLeads', 'settings'));
    }

    /** تصدير تقرير الحجوزات إلى Excel */
    public function exportBookings(Request $request)
    {
        $from = $request->input('from', now()->subMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $fromAr = Carbon::parse($from)->translatedFormat('d M Y');
        $toAr = Carbon::parse($to)->translatedFormat('d M Y');
        $filename = "تقرير_الحجوزات_{$fromAr}_إلى_{$toAr}.xlsx";

        return Excel::download(new BookingsExport($from, $to), $filename);
    }

    /** تصدير التقرير الشهري إلى Excel */
    public function exportMonthly()
    {
        $start = now()->subMonths(11)->startOfMonth();

        $bookingRows = Booking::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at']);

        $leadRows = Lead::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at']);

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->copy()->subMonths($i)->startOfMonth();
            $key = $m->format('Y-m');
            $months[$key] = [
                'label' => $m->translatedFormat('M Y'),
                'bookings' => 0,
                'leads' => 0,
            ];
        }

        foreach ($bookingRows as $b) {
            $key = Carbon::parse($b->created_at)->format('Y-m');
            if (isset($months[$key])) {
                $months[$key]['bookings']++;
            }
        }
        foreach ($leadRows as $l) {
            $key = Carbon::parse($l->created_at)->format('Y-m');
            if (isset($months[$key])) {
                $months[$key]['leads']++;
            }
        }

        $filename = "التقرير_الشهري.xlsx";
        return Excel::download(new MonthlyExport($months), $filename);
    }

    /** تصدير تقرير المصادر إلى Excel */
    public function exportSources()
    {
        $leadBySource = Lead::query()
            ->selectRaw('contact_source_id, count(*) as total')
            ->groupBy('contact_source_id')
            ->pluck('total', 'contact_source_id');

        $bookingBySource = Booking::query()
            ->selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source');

        $filename = "تقرير_مصادر_التواصل.xlsx";
        return Excel::download(new SourcesExport($leadBySource, $bookingBySource), $filename);
    }
}
