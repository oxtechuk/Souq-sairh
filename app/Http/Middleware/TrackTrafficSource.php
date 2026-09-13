<?php

namespace App\Http\Middleware;

use App\Services\AttributionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackTrafficSource
{
    public function __construct(protected AttributionService $attributionService)
    {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Capture attribution if available
        try {
            $this->attributionService->capture($request);
        } catch (\Throwable $e) {
            // Silently ignore tracking exceptions so user request never breaks
        }

        return $next($request);
    }
}
