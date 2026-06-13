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
    public function index(CacheService $cache)
    {
        $banks = CalculatorBank::orderBy('name')
            ->get(['id', 'name', 'interest_rate']);

        $factors = CalculatorFactor::with('bank')
            ->get();

        $cars = Car::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'cash_price', 'model']);

        return view('new-store.calculator', compact('banks', 'factors', 'cars'));
    }

    public function saveLead(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        CalculatorLead::create($request->only('name', 'phone', 'car_price', 'down_payment', 'months', 'monthly'));

        return response()->json(['success' => true]);
    }
}
