<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Car extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description', 'features'];

    protected $fillable = [
        'brand_id', 'category_id', 'name', 'slug', 'model', 'year', 'type',
        'color', 'colors', 'cash_price', 'min_down_payment', 'min_installment',
        'description', 'features', 'specs', 'thumbnail', 'is_featured', 'is_active', 'is_highlighted', 'views',
        'availability_status',
    ];

    public function specifications()
    {
        return $this->belongsToMany(Specification::class, 'car_specification');
    }

    public function features_list()
    {
        return $this->belongsToMany(Feature::class, 'car_feature');
    }

    protected $casts = [
        'specs' => 'array',
        'colors' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CarCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'car_offer');
    }

    public function activeOffers()
    {
        return $this->belongsToMany(Offer::class, 'car_offer')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest();
    }

    public function getActiveOfferAttribute()
    {
        return $this->activeOffers->first();
    }

    public function getCurrentPriceAttribute()
    {
        $offer = $this->activeOffer;
        if ($offer && $offer->special_price) {
            return $offer->special_price;
        }

        return $this->cash_price;
    }

    /** حساب القسط الشهري */
    public function calculateInstallment(int $downPayment, int $months, float $interestRate): array
    {
        $principal = $this->cash_price - $downPayment;
        $monthlyRate = $interestRate / 100 / 12;

        if ($monthlyRate == 0) {
            $monthly = $principal / $months;
        } else {
            $monthly = $principal * ($monthlyRate * pow(1 + $monthlyRate, $months))
                     / (pow(1 + $monthlyRate, $months) - 1);
        }

        return [
            'monthly' => round($monthly),
            'total' => round($monthly * $months) + $downPayment,
            'principal' => $principal,
        ];
    }
}
