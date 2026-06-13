<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public array $ttl = [
        'home.brands' => 86400,
        'home.partners' => 86400,
        'home.filterBrands' => 86400,
        'home.filterCategories' => 86400,
        'home.filterYears' => 3600,
        'settings.all' => null,
        'home.stats' => 86400,
        'cars.brands' => 86400,
        'cars.years' => 86400,
        'offers.list' => 3600,
        'cars.listing' => 300,
        'cars.detail' => 3600,
        'cars.related' => 3600,
        'blog.list' => 600,
        'blog.detail' => 3600,
        'calculator.banks' => 86400,
        'calculator.factors' => 86400,
    ];

    public function remember(string $key, \Closure $callback, ?int $ttl = null): mixed
    {
        $ttl ??= $this->ttl[$key] ?? 3600;

        if ($ttl === null) {
            return Cache::rememberForever($key, $callback);
        }

        return Cache::remember($key, $ttl, $callback);
    }

    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    public function flushTags(array $tags): void
    {
        Cache::tags($tags)->flush();
    }

    public function rememberWithTag(string $key, array $tags, \Closure $callback, ?int $ttl = null): mixed
    {
        $ttl ??= $this->ttl[$key] ?? 3600;

        return Cache::tags($tags)->remember($key, $ttl, $callback);
    }
}
