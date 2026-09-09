<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Services\CacheService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request, CacheService $cache)
    {
        $query = Car::with(['brand:id,name,slug', 'activeOffers'])
            ->where('is_active', true);

        $this->applyFilters($query, $request);

        match ($request->sort ?? 'latest') {
            'price_asc' => $query->orderBy('cash_price'),
            'price_desc' => $query->orderByDesc('cash_price'),
            'year_desc' => $query->orderByDesc('year'),
            default => $query->latest(),
        };

        $cars = $query->paginate(12, [
            'id', 'brand_id', 'name', 'slug', 'model', 'year', 'type',
            'thumbnail', 'cash_price', 'min_installment', 'created_at',
        ])->withQueryString();

        $brands = $cache->remember('cars.brands.list', function () {
            return Brand::where('is_active', true)
                ->get(['id', 'name']);
        });

        $brandCarQuery = Car::where('is_active', true);
        $this->applyFilters($brandCarQuery, $request, excludeBrands: true);
        $brandCounts = (clone $brandCarQuery)
            ->selectRaw('brand_id, COUNT(*) as count')
            ->groupBy('brand_id')
            ->pluck('count', 'brand_id');

        $brands->each(function ($brand) use ($brandCounts) {
            $brand->cars_count = $brandCounts[$brand->id] ?? 0;
        });

        $types = [
            'sedan' => 'سيدان', 'suv' => 'SUV', 'coupe' => 'كوبيه',
            'hatchback' => 'هاتشباك', 'pickup' => 'بيك آب', 'van' => 'فان', 'other' => 'أخرى',
        ];

        if ($request->ajax() || $request->wantsJson()) {
            $html = '';
            if ($cars->isEmpty()) {
                $html = '<div class="col-span-full text-center py-12">
                           <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
                           <h3 class="text-xl font-bold text-gray-500">لا توجد سيارات تطابق بحثك</h3>
                         </div>';
            } else {
                foreach ($cars as $car) {
                    $html .= view('new-store.partials.car-card', compact('car'))->render();
                }
            }

            $brandsHtml = view('new-store.partials.brand-list-items', compact('brands', 'request'))->render();

            return response()->json([
                'html' => $html,
                'brands_html' => $brandsHtml,
                'total' => $cars->total(),
                'next_page_url' => $cars->nextPageUrl(),
                'last_page' => $cars->lastPage(),
            ]);
        }

        return view('new-store.cars.index', compact('cars', 'brands', 'types'));
    }

    public function show($slug)
    {
        $car = Car::with([
            'brand:id,name,slug',
            'images:id,car_id,image_path,type,sort_order',
            'specifications:id,name,icon',
            'features_list:id,name,icon',
            'activeOffers',
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail([
                'id', 'brand_id', 'name', 'slug', 'model', 'year', 'type',
                'color', 'colors', 'cash_price', 'min_down_payment', 'min_installment',
                'description', 'features', 'specs', 'thumbnail', 'is_active', 'availability_status',
            ]);

        $car->increment('views');

        $related = Car::with(['brand:id,name,slug', 'activeOffers'])
            ->where('brand_id', $car->brand_id)
            ->where('id', '!=', $car->id)
            ->where('is_active', true)
            ->limit(4)
            ->get(['id', 'brand_id', 'name', 'slug', 'model', 'year', 'thumbnail', 'cash_price']);

        return view('new-store.cars.show', compact('car', 'related'));
    }

    public function apiFilter(Request $request)
    {
        $filter = $request->query('filter', 'popular');

        $query = Car::with(['brand:id,name,slug', 'activeOffers'])
            ->where('is_active', true);

        match ($filter) {
            'popular' => $query->orderByDesc('views'),
            'test-drive' => $query->where('availability_status', 'available'),
            'offers' => $query->whereHas('activeOffers'),
            'new' => $query->where('year', '>=', now()->year),
            'discount' => $query->whereHas('activeOffers'),
            default => $query->latest(),
        };

        $cars = $query->limit(8)->get(['id', 'brand_id', 'name', 'slug', 'model', 'year', 'type', 'specs', 'thumbnail', 'cash_price', 'min_installment']);

        $html = '';
        foreach ($cars as $car) {
            $html .= view('new-store.partials.car-card', compact('car'))->render();
        }

        return response()->json(['html' => $html]);
    }

    protected function applyFilters($query, Request $request, bool $excludeBrands = false): void
    {
        if (! $excludeBrands && $request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('engine_type')) {
            $query->where('specs->engine_type', $request->engine_type);
        }
        if ($request->filled('transmission')) {
            $query->where('specs->transmission', $request->transmission);
        }
        if ($request->filled('fuel_type')) {
            $query->where('specs->fuel_type', $request->fuel_type);
        }
        if ($request->filled('min_price')) {
            $query->where('cash_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('cash_price', '<=', $request->max_price);
        }
        if ($request->filled('search') || $request->filled('q')) {
            $s = $request->search ?: $request->q;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('model', 'like', "%{$s}%")
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$s}%"));
            });
        }
    }
}
