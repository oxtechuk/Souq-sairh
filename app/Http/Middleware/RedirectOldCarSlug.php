<?php

namespace App\Http\Middleware;

use App\Models\Car;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RedirectOldCarSlug
{
    /**
     * يعالج طلبات السيارات ويحوّل الـ slugs القديمة (التي تحتوي hash) إلى الجديدة بـ 301
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');

        if (! $slug) {
            return $next($request);
        }

        // لو الـ slug يحتوي على hash عشوائي أو سنة مكررة → ابحث عن السيارة بأي وسيلة
        $hasOldFormat = preg_match('/[a-f0-9]{8,}/', $slug) ||
                        preg_match('/(\d{4})-\1/', $slug); // سنة مكررة مثل 2026-2026

        if (! $hasOldFormat) {
            return $next($request);
        }

        // ابحث عن السيارة بجزء من الـ slug (بدون الـ hash)
        $cleanSlug = Cache::remember("redirect_slug_{$slug}", 3600, function () use ($slug) {
            // إزالة الـ hash (آخر جزء طوله +8 حروف hex)
            $withoutHash = preg_replace('/-[a-f0-9]{8,}$/', '', $slug);
            // إزالة السنة المكررة
            $withoutDupeYear = preg_replace('/(\d{4})-\1/', '$1', $withoutHash);

            $car = Car::where('slug', $withoutDupeYear)
                ->where('is_active', true)
                ->first(['slug']);

            return $car?->slug;
        });

        if ($cleanSlug && $cleanSlug !== $slug) {
            return redirect()->route('new.cars.show', $cleanSlug, 301);
        }

        return $next($request);
    }
}
