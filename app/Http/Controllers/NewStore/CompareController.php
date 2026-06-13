<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $carIds = array_filter(explode(',', $request->get('cars', '')));

        $cars = Car::with([
            'brand:id,name,slug',
            'specifications:id,name,icon',
            'features_list:id,name,icon',
        ])
            ->whereIn('id', $carIds)
            ->where('is_active', true)
            ->get([
                'id', 'brand_id', 'name', 'slug', 'model', 'year', 'type',
                'thumbnail', 'cash_price', 'min_installment',
            ]);

        return view('new-store.compare.index', compact('cars'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $cars = Car::with('brand:id,name')
            ->where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%")
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$q}%"));
            })
            ->limit(10)
            ->get(['id', 'brand_id', 'name', 'model', 'year', 'thumbnail']);

        return response()->json($cars->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name.' '.$c->model,
            'brand' => $c->brand?->name,
            'year' => $c->year,
            'image' => $c->thumbnail ? asset('storage/'.$c->thumbnail) : asset('new-store/images/car-1.png'),
        ]));
    }
}
