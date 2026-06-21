<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\CalculatorBank;
use App\Models\CalculatorFactor;
use App\Models\CalculatorLead;
use App\Models\Car;
use App\Services\CacheService;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index(CacheService $cache, Request $request)
    {
        $tab = $request->query('tab', 'individuals');

        if (!in_array($tab, ['individuals', 'companies', 'financing'])) {
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
            'financing' => [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'car_id' => 'nullable|exists:cars,id',
                'down_payment' => 'nullable|string|max:100',
                'trade_in' => 'nullable|string|max:10',
                'financing_amount' => 'nullable|string|max:100',
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

        $lead->refresh();

        $carPrice = 0;
        $carName = '';
        if (!empty($validated['car_id'])) {
            $car = Car::find($validated['car_id']);
            if ($car) {
                $carPrice = $car->cash_price;
                $carName = $car->name . ' ' . $car->model;
            }
        }

        session(['calculator_result' => [
            'lead_id' => $lead->id,
            'tab' => $tab,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'car_id' => $validated['car_id'] ?? null,
            'car_name' => $carName,
            'car_price' => $carPrice,
            'city' => $details['city'] ?? '',
            'salary_range' => $details['salary_range'] ?? '',
            'notes' => $details['notes'] ?? '',
        ]]);

        return response()->json(['success' => true]);
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
