<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyExport implements FromArray, WithHeadings, WithStyles
{
    protected $months;

    public function __construct(array $months)
    {
        $this->months = $months;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->months as $key => $row) {
            $rows[] = [
                'month' => $row['label'],
                'bookings' => $row['bookings'],
                'leads' => $row['leads'],
                'total' => $row['bookings'] + $row['leads'],
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'الشهر / السنة',
            'طلبات التقسيط',
            'عملاء محتملون',
            'الإجمالي',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
