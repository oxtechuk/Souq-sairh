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
            'hero_ad_1_link', 'hero_ad_2_link', 'hero_ad_3_link', 'store_home_hero', 'store_home_description',
            'order_distribution_method',
            'google_analytics_id', 'google_tag_manager_id', 'meta_pixel_id',
            'tiktok_pixel_id', 'tiktok_access_token',
            'snapchat_pixel_id', 'snapchat_api_token',
            'offers_grid_title',
            'privacy_policy', 'terms_conditions',
        ];

        // Update text/array settings
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->get($key)]);
            }
        }

        // Direct video URL support
        if ($request->filled('hero_video_url')) {
            Setting::updateOrCreate(['key' => 'hero_video'], ['value' => trim($request->hero_video_url)]);
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

        // Delete Hero Video if requested
        if ($request->boolean('delete_hero_video')) {
            $oldVideo = Setting::where('key', 'hero_video')->value('value');
            if ($oldVideo && !str_starts_with($oldVideo, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldVideo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldVideo);
            }
            Setting::where('key', 'hero_video')->delete();
        }

        // Check if hero_video was attempted to upload but failed at PHP level (size limits)
        if ($request->file('hero_video') && !$request->file('hero_video')->isValid()) {
            $errMsg = $request->file('hero_video')->getErrorMessage();
            return back()->with('error', 'تعذر رفع الفيديو: ' . $errMsg . ' (يرجى التأكد من أن حجم الفيديو لا يتجاوز حد السيرفر، أو استخدم زر رفع الفيديو المخصص).');
        }

        // Handle File Uploads (Only if new files are uploaded)
        $files = [
            'site_logo', 'site_favicon', 'breadcrumb_bg', 'hero_video',
            'hero_ad_1_image', 'hero_ad_1_mobile_image',
            'hero_ad_2_image', 'hero_ad_2_mobile_image',
            'hero_ad_3_image', 'hero_ad_3_mobile_image',
            'about_why_choose_image'
        ];
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

    public function uploadHeroVideo(Request $request, CacheService $cache)
    {
        // Check if file was dropped due to post_max_size / upload_max_filesize before reaching Laravel
        if (! $request->hasFile('hero_video')) {
            $maxUpload = ini_get('upload_max_filesize');
            $maxPost = ini_get('post_max_size');

            return response()->json([
                'success' => false,
                'message' => "لم يتم استلام ملف الفيديو بشكل سليم. قد يكون حجم الملف أكبر من الحد المسموح به في السيرفر (upload_max_filesize: {$maxUpload}, post_max_size: {$maxPost}). يرجى ضغط الفيديو أو اختيار ملف أصغر.",
            ], 422);
        }

        $file = $request->file('hero_video');

        if (! $file->isValid()) {
            $errMsg = $file->getErrorMessage();

            return response()->json([
                'success' => false,
                'message' => "تعذر رفع الفيديو: {$errMsg}",
            ], 422);
        }

        $request->validate([
            'hero_video' => 'required|file|mimes:mp4,webm,mov,ogg,mkv,avi|max:512000',
        ], [
            'hero_video.required' => 'يرجى اختيار ملف فيديو للرفع.',
            'hero_video.file' => 'الملف المحدد غير صالح.',
            'hero_video.mimes' => 'صيغة الفيديو غير مدعومة. الصيغ المدعومة: MP4, WebM, MOV.',
            'hero_video.max' => 'حجم الفيديو كبير جداً (الحد الأقصى 500 ميغابايت).',
        ]);

        $path = $file->store('settings/videos', 'public');

        // Delete previous video file if exists and local
        $oldVideo = Setting::where('key', 'hero_video')->value('value');
        if ($oldVideo && ! str_starts_with($oldVideo, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldVideo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldVideo);
        }

        Setting::updateOrCreate(['key' => 'hero_video'], ['value' => $path]);
        $cache->forget('settings.all');

        return response()->json([
            'success' => true,
            'message' => __('تم رفع وحفظ فيديو الهيرو بنجاح!'),
            'path' => $path,
            'url' => asset('storage/'.$path),
        ]);
    }

    public function deleteHeroVideo(Request $request, CacheService $cache)
    {
        $oldVideo = Setting::where('key', 'hero_video')->value('value');
        if ($oldVideo) {
            if (! str_starts_with($oldVideo, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldVideo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldVideo);
            }
            Setting::where('key', 'hero_video')->delete();
        }

        $cache->forget('settings.all');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('تم حذف فيديو الهيرو والعودة للفيديو الافتراضي.'),
            ]);
        }

        return back()->with('success', __('تم حذف فيديو الهيرو والعودة للفيديو الافتراضي.'));
    }
}
