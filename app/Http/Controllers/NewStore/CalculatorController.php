<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingNote;
use App\Models\CalculatorBank;
use App\Models\CalculatorFactor;
use App\Models\CalculatorLead;
use App\Models\Car;
use App\Services\CacheService;
use App\Services\OrderDistributionService;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index(CacheService $cache, Request $request)
    {
        $tab = $request->query('tab', 'individuals');

        if (!in_array($tab, ['individuals', 'companies'])) {
            $tab = 'individuals';
        }

        $banks = CalculatorBank::orderBy('name')
            ->get(['id', 'name', 'annual_rate']);

        $factors = CalculatorFactor::orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $cars = Car::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'cash_price', 'model']);

        return view('new-store.calculator', compact('banks', 'factors', 'cars', 'tab'));
    }

    public function saveLead(Request $request)
    {
        $tab = $request->input('tab', 'individuals');
        if (!in_array($tab, ['individuals', 'companies'])) {
            $tab = 'individuals';
        }

        $rules = [
            'individuals' => [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'car_id' => 'nullable|exists:cars,id',
                'city' => 'nullable|string|max:100',
                'salary_range' => 'nullable|string|max:100',
                'obligations_range' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:1000',
            ],
            'companies' => [
                'company_name' => 'required|string|max:255',
                'contact_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'num_cars' => 'nullable|integer|min:1|max:50',
                'city' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:1000',
            ],
        ];

        $validated = $request->validate($rules[$tab]);
        $validated['type'] = $tab;

        $details = collect($validated)->except(['name', 'phone', 'type', 'car_id'])->toArray();

        $lead = CalculatorLead::create([
            'name' => $validated['name'] ?? $validated['contact_name'] ?? '',
            'phone' => $validated['phone'],
            'type' => $tab,
            'car_id' => $validated['car_id'] ?? null,
            'details' => $details,
        ]);

        $carPrice = 0;
        $carName = '';
        if (!empty($validated['car_id'])) {
            $car = Car::find($validated['car_id']);
            if ($car) {
                $carPrice = $car->cash_price;
                $carName = $car->name . ' ' . $car->model;
            }
        }

        // إنشاء طلب مباشر في جدول الطلبات (Bookings) وتسميته "عميل حاسبة"
        $clientName = $tab === 'companies' ? ($validated['contact_name'] ?? '') : ($validated['name'] ?? '');
        $companyName = $tab === 'companies' ? ($validated['company_name'] ?? null) : null;
        $numCars = $tab === 'companies' ? ($validated['num_cars'] ?? 1) : null;

        $attribution = app(\App\Services\AttributionService::class)->getStored();
        $sourcePlatform = ($attribution['platform'] ?? 'website') !== 'website' ? $attribution['platform'] : 'website';

        $booking = Booking::create([
            'car_id' => $validated['car_id'] ?? null,
            'client_name' => $clientName,
            'client_phone' => $validated['phone'],
            'client_email' => $validated['email'] ?? null,
            'city' => $validated['city'] ?? null,
            'salary_range' => $validated['salary_range'] ?? null,
            'obligations_range' => $validated['obligations_range'] ?? null,
            'company_name' => $companyName,
            'num_cars' => $numCars,
            'notes' => $validated['notes'] ?? null,
            'total_price' => $carPrice,
            'down_payment' => 0,
            'duration_years' => 3,
            'monthly_installment' => 0,
            'contact_type' => 'calculator',
            'source' => $sourcePlatform,
            'utm_source' => $attribution['utm_source'] ?? null,
            'utm_medium' => $attribution['utm_medium'] ?? null,
            'utm_campaign' => $attribution['utm_campaign'] ?? null,
            'utm_content' => $attribution['utm_content'] ?? null,
            'utm_term' => $attribution['utm_term'] ?? null,
            'click_id' => $attribution['click_id'] ?? null,
            'referrer_url' => $attribution['referrer_url'] ?? null,
            'status' => 'new',
        ]);

        try {
            app(OrderDistributionService::class)->distribute($booking);
        } catch (\Throwable $e) {
            // توزيع الطلب اختياري بحسب إعدادات النظام
        }

        try {
            BookingNote::create([
                'booking_id' => $booking->id,
                'note' => 'تم إنشاء الطلب آلياً من حاسبة التمويل (الخطوة الأولى) - نوع العميل: ' . ($tab === 'companies' ? 'شركات' : 'أفراد'),
                'type' => 'note',
            ]);
        } catch (\Throwable $e) {
        }

        session(['calculator_result' => [
            'lead_id' => $lead->id,
            'booking_id' => $booking->id,
            'tab' => $tab,
            'name' => $clientName,
            'phone' => $booking->client_phone,
            'email' => $booking->client_email,
            'car_id' => $validated['car_id'] ?? null,
            'car_name' => $carName,
            'car_price' => $carPrice,
            'city' => $details['city'] ?? '',
            'salary_range' => $details['salary_range'] ?? '',
            'obligations_range' => $details['obligations_range'] ?? '',
            'company_name' => $companyName,
            'num_cars' => $numCars,
            'notes' => $details['notes'] ?? '',
        ]]);

        return response()->json([
            'success' => true,
            'booking_id' => $booking->id,
        ]);
    }

    public function confirmBooking(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'nullable|exists:cars,id',
            'bank_name' => 'nullable|string|max:100',
            'duration_months' => 'nullable|integer',
            'monthly_installment' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
        ]);

        $data = session('calculator_result', []);
        $bookingId = $data['booking_id'] ?? null;

        $booking = null;
        if ($bookingId) {
            $booking = Booking::find($bookingId);
        }

        if (!$booking && !empty($data['phone'])) {
            $booking = Booking::where('client_phone', $data['phone'])->latest()->first();
        }

        $durationYears = !empty($validated['duration_months']) ? (int) ceil($validated['duration_months'] / 12) : 3;

        $bankPart = !empty($validated['bank_name']) ? "البنك: " . $validated['bank_name'] : "";
        $calcNote = "تم تأكيد طلب السيارة من حاسبة التمويل";
        if ($bankPart) {
            $calcNote .= " - " . $bankPart;
        }
        if (!empty($validated['monthly_installment'])) {
            $calcNote .= " - القسط التقريبي: " . number_format($validated['monthly_installment']) . " ريال";
        }

        if ($booking) {
            $existingNotes = $booking->notes ? $booking->notes . "\n" : '';
            $booking->update([
                'car_id' => $validated['car_id'] ?? $booking->car_id,
                'total_price' => $validated['total_price'] ?? $booking->total_price,
                'duration_years' => $durationYears,
                'monthly_installment' => $validated['monthly_installment'] ?? $booking->monthly_installment,
                'contact_type' => 'car_request',
                'notes' => $existingNotes . $calcNote,
            ]);
        } else {
            $attribution = app(\App\Services\AttributionService::class)->getStored();
            $sourcePlatform = ($attribution['platform'] ?? 'website') !== 'website' ? $attribution['platform'] : 'website';

            $booking = Booking::create([
                'client_name' => $data['name'] ?? 'عميل حاسبة',
                'client_phone' => $data['phone'] ?? '',
                'client_email' => $data['email'] ?? null,
                'city' => $data['city'] ?? null,
                'salary_range' => $data['salary_range'] ?? null,
                'obligations_range' => $data['obligations_range'] ?? null,
                'company_name' => $data['company_name'] ?? null,
                'num_cars' => $data['num_cars'] ?? null,
                'car_id' => $validated['car_id'] ?? null,
                'total_price' => $validated['total_price'] ?? 0,
                'duration_years' => $durationYears,
                'monthly_installment' => $validated['monthly_installment'] ?? 0,
                'contact_type' => 'car_request',
                'source' => $sourcePlatform,
                'utm_source' => $attribution['utm_source'] ?? null,
                'utm_medium' => $attribution['utm_medium'] ?? null,
                'utm_campaign' => $attribution['utm_campaign'] ?? null,
                'utm_content' => $attribution['utm_content'] ?? null,
                'utm_term' => $attribution['utm_term'] ?? null,
                'click_id' => $attribution['click_id'] ?? null,
                'referrer_url' => $attribution['referrer_url'] ?? null,
                'status' => 'new',
                'notes' => $calcNote,
            ]);

            try {
                app(OrderDistributionService::class)->distribute($booking);
            } catch (\Throwable $e) {
            }
        }

        try {
            BookingNote::create([
                'booking_id' => $booking->id,
                'note' => 'قام العميل بالضغط على "طلب السيارة" وتأكيد تفاصيل الحسبة.',
                'type' => 'note',
            ]);
        } catch (\Throwable $e) {
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب السيارة بنجاح، سنتواصل معك قريباً.',
            'booking_id' => $booking->id,
        ]);
    }

    public function result()
    {
        $data = session('calculator_result');

        if (!$data) {
            return redirect()->route('new.calculator');
        }

        $banks = CalculatorBank::orderBy('name')
            ->get(['id', 'name', 'annual_rate']);

        $cars = Car::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'cash_price', 'model']);

        return view('new-store.calculator-result', compact('data', 'banks', 'cars'));
    }
}
