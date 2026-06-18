<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return Booking::with(['car.brand', 'employee'])
            ->whereDate('created_at', '>=', $this->from)
            ->whereDate('created_at', '<=', $this->to)
            ->orderByDesc('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'اسم العميل',
            'رقم الهاتف',
            'البريد الإلكتروني',
            'السيارة',
            'الماركة',
            'الحالة',
            'الموظف المسؤول',
            'المصدر',
            'الدفعة المقدمة',
            'القسط الشهري',
            'المدة (سنوات)',
            'السعر الإجمالي',
            'تاريخ الإنشاء',
        ];
    }

    public function map($booking): array
    {
        $statusIcon = match ($booking->status) {
            'sold' => '✅',
            'rejected' => '❌',
            'interested' => '⭐',
            'contacted' => '📞',
            default => '🆕',
        };

        return [
            $booking->id,
            $booking->client_name,
            $booking->client_phone,
            $booking->client_email,
            $booking->car?->name ?? '—',
            $booking->car?->brand?->name ?? '—',
            $statusIcon . ' ' . ($booking->status_label ?? $booking->status),
            $booking->employee?->name ?? '—',
            $booking->source ?? '—',
            $booking->down_payment ? number_format($booking->down_payment, 2) : '—',
            $booking->monthly_installment ? number_format($booking->monthly_installment, 2) : '—',
            $booking->duration_years ?? '—',
            $booking->total_price ? number_format($booking->total_price, 2) : '—',
            $booking->created_at->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
