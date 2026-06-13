<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\CacheService;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $cars = \App\Models\Car::select('id', 'name')->where('is_active', true)->orderBy('name')->get();

        $bentoCars = $settings['bento_cars'] ?? [];
        if (! is_array($bentoCars) && is_string($bentoCars)) {
            $bentoCars = json_decode($bentoCars, true) ?: [];
        }

        $socialMedia = $settings['social_media'] ?? [];
        if (! is_array($socialMedia) && is_string($socialMedia)) {
            $socialMedia = json_decode($socialMedia, true) ?: [];
        }

        return view('crm.settings.general', compact('settings', 'cars', 'bentoCars', 'socialMedia'));
    }

    public function seo()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('crm.settings.seo', compact('settings'));
    }

    public function update(Request $request, CacheService $cache)
    {
        // Whitelist of settings to update
        $keys = [
            'site_name', 'footer_text', 'contact_email', 'contact_phone',
            'contact_whatsapp', 'contact_address', 'bento_cars',
            'hero_ad_1_link', 'hero_ad_2_link', 'store_home_hero',
            'twilio_sid', 'twilio_auth_token', 'twilio_from',
            'google_analytics_id', 'meta_pixel_id', 'offers_grid_title',
        ];

        // Update text/array settings
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->get($key)]);
            }
        }

        // Handle Social Media Array
        $socialIcons = $request->input('social_icon', []);
        $socialLinks = $request->input('social_link', []);
        $socialColors = $request->input('social_color', []);
        $socialMedia = [];
        foreach ($socialIcons as $index => $icon) {
            if (! empty($icon) && ! empty($socialLinks[$index])) {
                $socialMedia[] = [
                    'icon' => $icon,
                    'link' => $socialLinks[$index],
                    'color' => $socialColors[$index] ?? '#333333',
                ];
            }
        }
        Setting::updateOrCreate(['key' => 'social_media'], ['value' => $socialMedia]);

        // Handle File Uploads (Only if new files are uploaded)
        $files = ['site_logo', 'site_favicon', 'breadcrumb_bg', 'hero_video', 'hero_ad_1_image', 'hero_ad_2_image', 'hero_ad_3_image', 'about_why_choose_image'];
        foreach ($files as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $path = $request->file($fileKey)->store('settings', 'public');
                Setting::updateOrCreate(['key' => $fileKey], ['value' => $path]);
            }
        }

        // Handle Main Gallery (Multiple Images)
        if ($request->hasFile('main_gallery')) {
            $existingGallery = Setting::where('key', 'main_gallery')->first();
            $galleryPaths = [];
            if ($existingGallery && ! empty($existingGallery->value)) {
                $galleryPaths = is_array($existingGallery->value) ? $existingGallery->value : (json_decode($existingGallery->value, true) ?: []);
            }

            foreach ($request->file('main_gallery') as $image) {
                $path = $image->store('settings/gallery', 'public');
                $galleryPaths[] = $path;
            }
            Setting::updateOrCreate(['key' => 'main_gallery'], ['value' => $galleryPaths]);
        }

        // Handle Gallery Image Deletion
        if ($request->has('delete_gallery_image')) {
            $imageToDelete = $request->get('delete_gallery_image');
            $existingGallery = Setting::where('key', 'main_gallery')->first();
            if ($existingGallery && ! empty($existingGallery->value)) {
                $galleryPaths = is_array($existingGallery->value) ? $existingGallery->value : (json_decode($existingGallery->value, true) ?: []);
                $galleryPaths = array_values(array_filter($galleryPaths, function ($path) use ($imageToDelete) {
                    return $path !== $imageToDelete;
                }));
                Setting::updateOrCreate(['key' => 'main_gallery'], ['value' => $galleryPaths]);

                if (\Storage::disk('public')->exists($imageToDelete)) {
                    \Storage::disk('public')->delete($imageToDelete);
                }
            }
        }

        // Clear cached settings so frontend picks up changes immediately
        $cache->forget('settings.all');

        return back()->with('success', __('تم تحديث الإعدادات بنجاح'));
    }

    public function testTwilio(Request $request)
    {
        $sid = Setting::where('key', 'twilio_sid')->value('value');
        $token = Setting::where('key', 'twilio_auth_token')->value('value');
        $from = Setting::where('key', 'twilio_from')->value('value');

        if (empty($sid) || empty($token)) {
            return response()->json([
                'success' => false,
                'message' => __('بيانات Twilio غير مكتملة. يرجى حفظ Account SID و Auth Token أولاً.'),
            ]);
        }

        try {
            $twilio = new \Twilio\Rest\Client($sid, $token);
            $twilio->api->v2010->accounts($sid)->fetch();

            $message = __('تم الاتصال بنجاح بخوادم Twilio.');
            if (! empty($from)) {
                $message .= ' '.__('رقم المرسل:').' '.e($from);
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Twilio\Exceptions\RestException $e) {
            return response()->json([
                'success' => false,
                'message' => __('خطأ Twilio:').' '.e($e->getMessage()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('تعذر الاتصال:').' '.e($e->getMessage()),
            ]);
        }
    }
}
