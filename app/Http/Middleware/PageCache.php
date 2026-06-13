<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class PageCache
{
    public array $except = [
        'new.cars.index',
        'new.cars.show',
        'new.compare',
        'new.contact.store',
        'new.calculator.lead',
        'new.compare.search',
    ];

    public int $ttl = 300;

    public function handle(Request $request, Closure $next, ?int $ttl = null): SymfonyResponse
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $route = $request->route();
        if ($route && in_array($route->getName(), $this->except, true)) {
            return $next($request);
        }

        if ($request->query->count() > 0) {
            return $next($request);
        }

        $key = 'page_cache:'.$request->getPathInfo();

        $ttl ??= $this->ttl;

        $cached = Cache::get($key);

        if ($cached !== null) {
            $response = new SymfonyResponse($cached);
            $response->headers->set('X-Page-Cache', 'HIT');

            return $response;
        }

        $response = $next($request);

        if ($response->getStatusCode() === 200) {
            Cache::put($key, $response->getContent(), $ttl);
            $response->headers->set('X-Page-Cache', 'MISS');
        }

        return $response;
    }

    public static function flush(string $path): void
    {
        Cache::forget('page_cache:'.$path);
    }
}
