<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Employee;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth('employee')->user() ?? auth()->user();
        $isAdmin = $user && ($user->isAdmin() || $user->hasRole('admin') || $user->role === 'admin');

        if ($isAdmin) {
            $stats = \Illuminate\Support\Facades\Cache::remember('crm_dashboard_stats', 300, function () {
                return [
                    'total' => Booking::count(),
                    'new' => Booking::new()->count(),
                    'in_progress' => Booking::inProgress()->count(),
                    'sold' => Booking::completed()->count(),
                    'rejected' => Booking::where('status', 'rejected')->count(),
                ];
            });

            $recentBookings = Booking::with('car.brand')
                ->latest()
                ->limit(10)
                ->get();
        } else {
            $userId = $user?->id;
            $stats = [
                'total' => Booking::where('assigned_to', $userId)->count(),
                'new' => Booking::where('assigned_to', $userId)->new()->count(),
                'in_progress' => Booking::where('assigned_to', $userId)->inProgress()->count(),
                'sold' => Booking::where('assigned_to', $userId)->completed()->count(),
                'rejected' => Booking::where('assigned_to', $userId)->where('status', 'rejected')->count(),
            ];

            $recentBookings = Booking::with('car.brand')
                ->where('assigned_to', $userId)
                ->latest()
                ->limit(10)
                ->get();
        }

        $totals = \Illuminate\Support\Facades\Cache::remember('crm_dashboard_totals', 300, function () {
            return [
                'cars' => Car::where('is_active', true)->count(),
                'brands' => Brand::count(),
                'employees' => Employee::count(),
            ];
        });

        $totalCars = $totals['cars'];
        $totalBrands = $totals['brands'];
        $totalEmployees = $totals['employees'];

        $trackingGA = Setting::where('key', 'google_analytics_id')->first()?->value ?? '';
        $trackingPixel = Setting::where('key', 'meta_pixel_id')->first()?->value ?? '';

        $siteNameSetting = Setting::where('key', 'site_name')->first()?->value;
        $siteName = is_array($siteNameSetting)
            ? ($siteNameSetting[app()->getLocale()] ?? $siteNameSetting['ar'] ?? 'Souq Siarh')
            : ($siteNameSetting ?? 'Souq Siarh');

        return view('crm.dashboard', compact(
            'stats', 'topCars', 'weeklyBookings', 'recentBookings',
            'totalCars', 'totalBrands', 'totalEmployees',
            'trackingGA', 'trackingPixel', 'siteName'
        ));
    }
}
