<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::where('source', 'contact_form')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%{$s}%")
                    ->orWhere('client_phone', 'like', "%{$s}%")
                    ->orWhere('client_email', 'like', "%{$s}%")
                    ->orWhere('message', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(20)->withQueryString();
        $statuses = Lead::STATUSES;

        return view('crm.contact-messages.index', compact('messages', 'statuses'));
    }

    public function destroy(Lead $lead)
    {
        if ($lead->source !== 'contact_form') {
            abort(404);
        }

        $lead->delete();

        return redirect()->route('crm.contact-messages.index')->with('success', __('تم حذف الرسالة'));
    }
}
