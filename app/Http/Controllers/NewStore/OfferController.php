<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Services\CacheService;

class OfferController extends Controller
{
    public function index(CacheService $cache)
    {
        $offers = Offer::active()
            ->with('cars.brand:id,name,slug')
            ->latest()
            ->get(['id', 'title', 'description', 'image', 'discount_percent', 'discount_value', 'special_installment']);

        return view('new-store.offers.index', compact('offers'));
    }
}
