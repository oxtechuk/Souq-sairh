<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $cars = Car::with('brand')
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get(['id', 'brand_id', 'name', 'year', 'cash_price', 'min_installment', 'thumbnail']);

        $selectedCar = null;
        if ($request->filled('car_id')) {
            $selectedCar = $cars->find($request->car_id);
        }

        return view('new-store.booking', compact('cars', 'selectedCar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => 'required|string|max:200',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:200',
            'car_id' => 'nullable|exists:cars,id',
            'city' => 'nullable|string|max:100',
            'salary_range' => 'nullable|string|max:100',
            'obligations_range' => 'nullable|string|max:100',
            'contact_type' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:200',
            'tax_number' => 'nullable|string|max:100',
            'down_payment' => 'nullable|numeric',
            'financing_period' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:5000',
        ]);

        $booking = Booking::create([
            'car_id' => $data['car_id'],
            'client_name' => $data['client_name'],
            'client_phone' => $data['client_phone'],
            'client_email' => $data['client_email'] ?? null,
            'city' => $data['city'] ?? null,
            'salary_range' => $data['salary_range'] ?? null,
            'obligations_range' => $data['obligations_range'] ?? null,
            'contact_type' => $data['contact_type'] ?? 'individuals',
            'company_name' => $data['company_name'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'down_payment' => $data['down_payment'] ?? 0,
            'financing_period' => $data['financing_period'] ?? null,
            'notes' => $data['notes'] ?? null,
            'source' => 'website',
            'status' => 'new',
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('booking_documents', 'public');
                $booking->documents()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'title' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('success', 'تم إرسال طلبك بنجاح، سنتواصل معك قريباً.');
    }
}
