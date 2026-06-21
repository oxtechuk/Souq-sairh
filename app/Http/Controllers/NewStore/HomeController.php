<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(CacheService $cache)
    {
        $featuredCars = Car::with(['brand:id,name,slug', 'activeOffers'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get(['id', 'brand_id', 'name', 'slug', 'model', 'year', 'thumbnail', 'cash_price', 'min_installment']);

        if ($featuredCars->isEmpty()) {
            $featuredCars = Car::with(['brand:id,name,slug', 'activeOffers'])
                ->where('is_active', true)
                ->latest()
                ->limit(8)
                ->get(['id', 'brand_id', 'name', 'slug', 'model', 'year', 'thumbnail', 'cash_price', 'min_installment']);
        }

        $latestPosts = BlogPost::published()
            ->latest('published_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'thumbnail', 'excerpt', 'published_at']);

        $testimonials = Testimonial::where('is_visible', true)
            ->get(['id', 'name', 'title', 'content', 'image', 'review_image', 'rating']);

        $highlightedCars = Car::with(['brand:id,name,slug', 'activeOffers'])
            ->where('is_highlighted', '!=', 'none')
            ->where('is_active', true)
            ->latest()
            ->get(['id', 'brand_id', 'name', 'slug', 'model', 'year', 'thumbnail', 'cash_price', 'min_installment', 'is_highlighted']);

        $brands = $cache->remember('home.brands', function () {
            return Brand::where('is_active', true)
                ->withCount('cars')
                ->get(['id', 'name', 'slug', 'logo']);
        });

        $partners = $cache->remember('home.partners', function () {
            return Partner::orderBy('sort_order')->get(['id', 'name', 'logo', 'link']);
        });

        $filterBrands = $cache->remember('home.filterBrands', function () {
            return Brand::whereHas('cars')->orderBy('name')->get(['id', 'name']);
        });

        $filterCategories = $cache->remember('home.filterCategories', function () {
            return CarCategory::orderBy('name')->get(['id', 'name']);
        });

        $filterYears = $cache->remember('home.filterYears', function () {
            return Car::where('is_active', true)->distinct()->pluck('year')->sortDesc();
        });

        $settings = $cache->remember('settings.all', function () {
            return Setting::all()->pluck('value', 'key');
        }, null);

        $hero = $settings->has('store_home_hero') ? $settings->get('store_home_hero') : [
            'title' => 'تخيّر موتِرك..',
            'subtitle' => 'وحنّا نيسّر لك التمويل',
        ];

        $heroVideo = $settings->get('hero_video');
        $heroAd1Image = $settings->get('hero_ad_1_image');
        $heroAd2Image = $settings->get('hero_ad_2_image');
        $heroAd3Image = $settings->get('hero_ad_3_image');
        $heroAd1Link = $settings->get('hero_ad_1_link');
        $heroAd2Link = $settings->get('hero_ad_2_link');
        $heroAd3Link = $settings->get('hero_ad_3_link');

        $heroAd1 = ['image' => $heroAd1Image, 'link' => $heroAd1Link];
        $heroAd2 = ['image' => $heroAd2Image, 'link' => $heroAd2Link];
        $heroAd3 = ['image' => $heroAd3Image, 'link' => $heroAd3Link];

        $stats = $cache->remember('home.stats', function () {
            return [
                'cars' => Car::where('is_active', true)->count(),
                'brands' => Brand::where('is_active', true)->count(),
            ];
        });

        return view('new-store.home', compact(
            'featuredCars', 'brands', 'latestPosts', 'stats',
            'testimonials', 'partners', 'hero', 'heroVideo', 'heroAd1', 'heroAd2', 'heroAd3',
            'filterBrands', 'filterCategories', 'filterYears', 'highlightedCars'
        ));
    }

    public function about()
    {
        $testimonials = Testimonial::where('is_visible', true)
            ->get(['id', 'name', 'title', 'content', 'image', 'rating']);

        $partners = Partner::orderBy('sort_order')
            ->get(['id', 'name', 'logo', 'link']);

        $stats = [
            'cars' => Car::where('is_active', true)->count(),
            'brands' => Brand::where('is_active', true)->count(),
        ];

        return view('new-store.about', compact('testimonials', 'partners', 'stats'));
    }

    public function page($page)
    {
        $settings = Cache::remember('settings.all', 3600, function () {
            return Setting::all()->pluck('value', 'key');
        });

        $allowed = ['privacy-policy', 'terms-conditions'];
        if (! in_array($page, $allowed)) {
            abort(404);
        }

        $key = $page === 'privacy-policy' ? 'privacy_policy' : 'terms_conditions';
        $title = $page === 'privacy-policy' ? 'سياسة الخصوصية' : 'الشروط والأحكام';
        $content = $settings[$key] ?? '';

        return view('new-store.page', compact('title', 'content'));
    }
}
