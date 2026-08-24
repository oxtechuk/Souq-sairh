<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class TrackingController extends Controller
{
    public function index()
    {
        $user = auth('employee')->user() ?? auth()->user();
        $isAdmin = $user && ($user->isAdmin() || $user->hasRole('admin') || $user->role === 'admin');

        $columns = [
            'new' => ['label' => 'جديد', 'color' => '#1877F2', 'count' => 0],
            'contacted' => ['label' => 'تم التواصل', 'color' => '#0DCAF0', 'count' => 0],
            'interested' => ['label' => 'مهتم', 'color' => '#FF9800', 'count' => 0],
            'pending_closure' => ['label' => 'بانتظار مراجعة الأدمن', 'color' => '#6c757d', 'count' => 0],
            'closed' => ['label' => 'مغلق (لم يتم الرد)', 'color' => '#343a40', 'count' => 0],
            'sold' => ['label' => 'تم الاستلام ✓', 'color' => '#4CAF50', 'count' => 0],
            'rejected' => ['label' => 'مرفوض', 'color' => '#EE1E26', 'count' => 0],
        ];

        $query = Booking::with(['car.brand', 'assignedTo'])->withCount('notes_list')->latest();

        // لموظف المبيعات العادي: إظهار الطلبات الخاصة به فقط
        if (!$isAdmin) {
            $query->where('assigned_to', $user?->id);
        }

        $bookings = $query->get()->groupBy(function ($b) use ($columns) {
            return array_key_exists($b->status, $columns) ? $b->status : 'new';
        });

        foreach ($columns as $key => &$col) {
            $col['items'] = $bookings[$key] ?? collect();
            $col['count'] = $col['items']->count();
        }

        return view('crm.tracking.index', compact('columns', 'isAdmin'));
    }
}
