<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingNote;
use App\Models\Car;
use App\Models\Employee;
use App\Notifications\NewBookingNotification;
use App\Services\OrderDistributionService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('employee')->user() ?? auth()->user();
        $isAdmin = $user && ($user->isAdmin() || $user->hasRole('admin') || $user->role === 'admin');

        $query = Booking::with(['car.brand', 'employee'])->latest();

        // فلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة بالموظف
        if ($request->filled('employee_id')) {
            $query->where('assigned_to', $request->employee_id);
        }

        // فلترة بنوع الطلب (عميل حاسبة / طلب سيارة / تمويل / كاش أفراد / شركات)
        if ($request->filled('contact_type')) {
            $ct = $request->contact_type;
            if ($ct === 'calculator') {
                $query->where(function ($q) {
                    $q->where('contact_type', 'calculator')->orWhere('source', 'عميل حاسبة');
                });
            } elseif ($ct === 'car_request') {
                $query->where(function ($q) {
                    $q->where('contact_type', 'car_request')->orWhere('source', 'طلب سيارة');
                });
            } else {
                $query->where('contact_type', $ct);
            }
        } elseif ($request->filled('type')) {
            if ($request->type === 'calculator') {
                $query->where(function ($q) {
                    $q->where('contact_type', 'calculator')->orWhere('source', 'عميل حاسبة');
                });
            } elseif ($request->type === 'car_request') {
                $query->where(function ($q) {
                    $q->where('contact_type', 'car_request')->orWhere('source', 'طلب سيارة');
                });
            } elseif ($request->type === 'financing' || $request->type === 'loan') {
                $query->where('contact_type', 'financing');
            } elseif ($request->type === 'individuals' || $request->type === 'cash') {
                $query->where('contact_type', 'individuals');
            } elseif ($request->type === 'companies') {
                $query->where('contact_type', 'companies');
            }
        }

        // فلترة بالتاريخ
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // للموظف العادي: إظهار الطلبات المسندة إليه فقط، واستبعاد الطلبات المغلقة أو في انتظار مراجعة الأدمن
        if (!$isAdmin) {
            $query->where('assigned_to', $user?->id);
            if (!$request->filled('status')) {
                $query->whereNotIn('status', ['pending_closure', 'closed']);
            }
        }

        // بحث شامل: رقم الطلب (#ID)، رقم/جوال العميل، اسم العميل، اسم السيارة/الماركة
        if ($request->filled('search')) {
            $s = trim($request->search);
            $cleanS = ltrim($s, '#');
            $query->where(function ($q) use ($s, $cleanS) {
                if (is_numeric($cleanS)) {
                    $q->where('id', $cleanS);
                }
                $q->orWhere('client_name', 'like', "%$s%")
                    ->orWhere('client_phone', 'like', "%$s%")
                    ->orWhereHas('car', function ($carQ) use ($s) {
                        $carQ->where('name', 'like', "%$s%")
                            ->orWhereHas('brand', function ($bQ) use ($s) {
                                $bQ->where('name', 'like', "%$s%");
                            });
                    });
            });
        }

        $bookings = $query->paginate(20)->withQueryString();
        $employees = Employee::where('is_active', true)->get();
        $statuses = Booking::STATUSES;
        $cars = Car::with('brand')->where('is_active', true)->get();

        $stats = [
            'pending_review' => Booking::where('status', 'pending_closure')->count(),
            'today_count' => Booking::whereDate('created_at', now()->format('Y-m-d'))->count(),
            'total' => Booking::count(),
        ];

        return view('crm.bookings.index', compact('bookings', 'employees', 'statuses', 'cars', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:191',
            'client_phone' => 'required|string|max:191',
            'client_email' => 'nullable|email|max:191',
            'car_id' => 'nullable|exists:cars,id',
            'total_price' => 'nullable|numeric',
            'down_payment' => 'nullable|numeric',
            'duration_years' => 'nullable|integer|min:0',
            'monthly_installment' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        $booking = Booking::create([
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'client_email' => $request->client_email,
            'car_id' => $request->car_id,
            'total_price' => $request->total_price ?? 0,
            'down_payment' => $request->down_payment ?? 0,
            'duration_years' => $request->duration_years ?? 5,
            'monthly_installment' => $request->monthly_installment ?? 0,
            'notes' => $request->notes,
            'source' => 'CRM (يدوي)',
            'status' => 'new',
            'assigned_to' => auth('employee')->id(), // assign to the creator by default
        ]);

        app(OrderDistributionService::class)->distribute($booking);

        return back()->with('success', 'تم إنشاء الطلب بنجاح');
    }

    public function show(Booking $booking)
    {
        $booking->load(['car.brand', 'employee', 'notes_list.employee', 'documents.employee']);
        $employees = Employee::where('is_active', true)->get();
        $statuses = Booking::STATUSES;

        return view('crm.bookings.show', compact('booking', 'employees', 'statuses'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $user = auth('employee')->user() ?? auth()->user();
        $isAdmin = $user && ($user->isAdmin() || $user->hasRole('admin') || $user->role === 'admin');

        $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(Booking::STATUSES)),
            'final_price' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:2000',
        ]);

        $oldStatus = $booking->status;
        $targetStatus = $request->status;

        // إذا كان الموظف ليس أدمن ويحاول غلق الطلب، يتحول الطلب إلى "في انتظار مراجعة الأدمن"
        if (!$isAdmin && in_array($targetStatus, ['closed', 'pending_closure'])) {
            $targetStatus = 'pending_closure';
        }

        $updateData = [
            'status' => $targetStatus,
            'last_contacted_at' => now(),
        ];

        // في حالة تم الاستلام (sold)
        if ($targetStatus === 'sold') {
            if ($request->filled('final_price')) {
                $updateData['final_price'] = $request->final_price;
            }
            if ($request->filled('interest_rate')) {
                $updateData['interest_rate'] = $request->interest_rate;
            }
            if ($request->filled('commission')) {
                $updateData['commission'] = $request->commission;
            }
        }

        $booking->update($updateData);

        // بناء نص الملاحظة السجلي
        $noteText = 'تم تغيير الحالة من "'.(Booking::STATUSES[$oldStatus]['label'] ?? $oldStatus).'" إلى "'.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus).'"';

        if ($targetStatus === 'sold') {
            $details = [];
            if ($request->filled('final_price')) {
                $details[] = 'السعر النهائي: '.number_format($request->final_price).' ريال';
            }
            if ($request->filled('interest_rate')) {
                $details[] = 'سعر الفائدة: '.$request->interest_rate.'%';
            }
            if ($request->filled('commission')) {
                $details[] = 'العمولة: '.number_format($request->commission).' ريال';
            }
            if (!empty($details)) {
                $noteText .= ' ('.implode(' - ', $details).')';
            }
        }

        if ($request->filled('note')) {
            $noteText .= "\nملاحظة: ".$request->note;
        }

        BookingNote::create([
            'booking_id' => $booking->id,
            'employee_id' => $user?->id,
            'note' => $noteText,
            'type' => 'status_change',
            'old_status' => $oldStatus,
            'new_status' => $targetStatus,
        ]);

        if ($booking->assignedTo) {
            $booking->assignedTo->notify(new NewBookingNotification(
                $booking,
                __('تحديث حالة الطلب'),
                __('تم تغيير حالة طلب العميل').' '.$booking->client_name.' '.__('إلى').' '.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus)
            ));
        }

        $message = 'تم تحديث حالة الطلب بنجاح';
        if (!$isAdmin && $targetStatus === 'pending_closure') {
            $message = 'تم إرسال طلب إغلاق الطلب، وهو الآن في انتظار مراجعة الأدمن';
        }

        return back()->with('success', $message);
    }

    public function assign(Request $request, Booking $booking)
    {
        $user = auth('employee')->user() ?? auth()->user();
        $isAdmin = $user && ($user->isAdmin() || $user->hasRole('admin') || $user->role === 'admin');

        if (!$isAdmin) {
            return back()->with('error', 'عفواً، تحويل الطلبات متاح للأدمن فقط');
        }

        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $booking->update(['assigned_to' => $request->employee_id]);

        // Notify the assigned employee
        $employee = Employee::find($request->employee_id);
        if ($employee) {
            $employee->notify(new NewBookingNotification($booking, __('طلب جديد'), __('تم تعيين طلب جديد لك للعميل').' '.$booking->client_name));
        }

        return back()->with('success', 'تم توزيع الطلب على الموظف');
    }

    public function addNote(Request $request, Booking $booking)
    {
        $request->validate(['note' => 'required|string|max:2000', 'type' => 'in:note,call']);
        BookingNote::create([
            'booking_id' => $booking->id,
            'employee_id' => auth('employee')->id(),
            'note' => $request->note,
            'type' => $request->type ?? 'note',
        ]);

        return back()->with('success', 'تمت إضافة الملاحظة');
    }

    public function uploadDocument(Request $request, Booking $booking)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('booking_documents', 'public');

        $booking->documents()->create([
            'employee_id' => auth('employee')->id(),
            'title' => $request->title ?? $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'تم رفع المستند بنجاح');
    }

    public function deleteDocument(\App\Models\BookingDocument $document)
    {
        if ($document->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($document->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();

        return back()->with('success', 'تم حذف المستند بنجاح');
    }

    public function destroy(Booking $booking)
    {
        $user = auth('employee')->user() ?? auth()->user();
        $isAdmin = $user && ($user->isAdmin() || $user->hasRole('admin') || $user->role === 'admin');

        if (!$isAdmin) {
            return back()->with('error', 'عفواً، حذف الطلبات متاح للأدمن فقط');
        }

        $booking->delete();

        return redirect()->route('crm.bookings.index')->with('success', 'تم حذف الطلب');
    }
}
