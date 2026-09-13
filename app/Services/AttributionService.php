<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class AttributionService
{
    const COOKIE_NAME = 'souq_traffic_attribution';
    const SESSION_KEY = 'souq_traffic_attribution';
    const COOKIE_LIFETIME_MINUTES = 60 * 24 * 30; // 30 days

    /**
     * Detect and capture attribution from incoming HTTP request.
     */
    public function capture(Request $request): ?array
    {
        // Don't capture on admin/CRM or API requests
        if ($request->is('Souq-admin*') || $request->is('api/*')) {
            return null;
        }

        $utmSource = $request->query('utm_source');
        $utmMedium = $request->query('utm_medium');
        $utmCampaign = $request->query('utm_campaign');
        $utmContent = $request->query('utm_content');
        $utmTerm = $request->query('utm_term');

        // Check click IDs and detect platform directly
        $fbclid = $request->query('fbclid');
        $gclid = $request->query('gclid') ?? $request->query('wbraid') ?? $request->query('gbraid');
        $sccid = $request->query('sccid') ?? $request->query('ScCid');
        $ttclid = $request->query('ttclid');
        $igshid = $request->query('igshid');

        $clickType = null;
        $clickId = null;

        if (!empty($sccid)) {
            $clickType = 'snapchat';
            $clickId = $sccid;
        } elseif (!empty($fbclid)) {
            $clickType = 'facebook';
            $clickId = $fbclid;
        } elseif (!empty($ttclid)) {
            $clickType = 'tiktok';
            $clickId = $ttclid;
        } elseif (!empty($igshid)) {
            $clickType = 'instagram';
            $clickId = $igshid;
        } elseif (!empty($gclid)) {
            $clickType = 'google';
            $clickId = $gclid;
        }

        $referer = $request->headers->get('referer');

        $hasCampaignParams = $utmSource || $utmCampaign || $clickId;

        // Infer platform (giving priority to explicit click ID platform, then UTM, then referer)
        $platform = $this->inferPlatform($utmSource, $clickType, $referer);

        // If no explicit ad parameters or external referer found, check if we already have attribution
        if (!$hasCampaignParams && ($platform === 'website' || $platform === null)) {
            return $this->getStored();
        }

        $data = [
            'platform' => $platform ?: 'website',
            'utm_source' => $utmSource ? substr($utmSource, 0, 191) : ($clickType ?: null),
            'utm_medium' => $utmMedium ? substr($utmMedium, 0, 191) : ($clickType ? 'cpc' : null),
            'utm_campaign' => $utmCampaign ? substr($utmCampaign, 0, 191) : null,
            'utm_content' => $utmContent ? substr($utmContent, 0, 191) : null,
            'utm_term' => $utmTerm ? substr($utmTerm, 0, 191) : null,
            'click_id' => $clickId ? substr($clickId, 0, 191) : null,
            'referrer_url' => $referer ? substr($referer, 0, 500) : null,
            'captured_at' => now()->toIso8601String(),
        ];

        // Store in session
        Session::put(self::SESSION_KEY, $data);

        // Queue cookie for 30 days
        Cookie::queue(self::COOKIE_NAME, json_encode($data), self::COOKIE_LIFETIME_MINUTES);

        return $data;
    }

    /**
     * Get stored attribution data from Session or Cookie.
     */
    public function getStored(): array
    {
        $sessionData = Session::get(self::SESSION_KEY);
        if (is_array($sessionData) && !empty($sessionData['platform'])) {
            return $sessionData;
        }

        $cookieRaw = Cookie::get(self::COOKIE_NAME);
        if ($cookieRaw) {
            $decoded = json_decode($cookieRaw, true);
            if (is_array($decoded) && !empty($decoded['platform'])) {
                Session::put(self::SESSION_KEY, $decoded);
                return $decoded;
            }
        }

        return [
            'platform' => 'website',
            'utm_source' => null,
            'utm_medium' => null,
            'utm_campaign' => null,
            'utm_content' => null,
            'utm_term' => null,
            'click_id' => null,
            'referrer_url' => null,
        ];
    }

    /**
     * Infer platform string from utm_source, clickType, or referer.
     */
    public function inferPlatform(?string $utmSource, ?string $clickType = null, ?string $referer = null): string
    {
        if (!empty($clickType)) {
            return $clickType;
        }

        $src = strtolower(trim((string) $utmSource));
        $ref = strtolower(trim((string) $referer));

        // Snapchat
        if (str_contains($src, 'snap') || str_contains($ref, 'snapchat.com')) {
            return 'snapchat';
        }

        // Instagram
        if (str_contains($src, 'instagram') || str_contains($src, 'ig') || str_contains($ref, 'instagram.com')) {
            return 'instagram';
        }

        // Facebook / Meta
        if (str_contains($src, 'facebook') || str_contains($src, 'fb') || str_contains($ref, 'facebook.com') || str_contains($ref, 'fb.com')) {
            return 'facebook';
        }

        // Google
        if (str_contains($src, 'google') || str_contains($ref, 'google.')) {
            return 'google';
        }

        // TikTok
        if (str_contains($src, 'tiktok') || str_contains($ref, 'tiktok.com')) {
            return 'tiktok';
        }

        // Twitter / X
        if (str_contains($src, 'twitter') || str_contains($src, 'x.com') || str_contains($ref, 'twitter.com') || str_contains($ref, 't.co')) {
            return 'twitter';
        }

        // WhatsApp
        if (str_contains($src, 'whatsapp') || str_contains($src, 'wa') || str_contains($ref, 'whatsapp.com')) {
            return 'whatsapp';
        }

        // Haraj
        if (str_contains($src, 'haraj') || str_contains($ref, 'haraj.com.sa')) {
            return 'haraj';
        }

        // Internal / CRM
        if (str_contains($src, 'internal') || str_contains($src, 'crm') || str_contains($src, 'داخلي')) {
            return 'internal';
        }

        if (!empty($src)) {
            return $src;
        }

        return 'website';
    }
}
