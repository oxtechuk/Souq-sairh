<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\ContactSource;
use App\Models\Lead;
use App\Models\Setting;
use App\Services\CacheService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(CacheService $cache)
    {
        $settings = $cache->remember('settings.all', function () {
            return Setting::all()->pluck('value', 'key');
        }, null);

        return view('new-store.contact', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
        ]);

        Lead::create([
            'client_name' => $data['name'],
            'client_phone' => $data['phone'],
            'client_email' => $data['email'] ?? null,
            'message' => $data['message'] ?? null,
            'source' => 'contact_form',
            'status' => 'new',
            'started_at' => now(),
            'contact_source_id' => ContactSource::firstOrCreate(['name' => 'نموذج التواصل'])->id,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً.']);
        }

        return back()->with('success', 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً.');
    }
}
