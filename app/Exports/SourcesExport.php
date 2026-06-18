<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SourcesExport implements FromArray, WithHeadings, WithStyles
{
    protected $leadBySource;
    protected $bookingBySource;

    public function __construct($leadBySource, $bookingBySource)
    {
        $this->leadBySource = $leadBySource;
        $this->bookingBySource = $bookingBySource;
    }

    public function array(): array
    {
        $rows = [];
        $allSources = collect($this->leadBySource)->keys()
            ->merge(collect($this->bookingBySource)->keys())
            ->unique()
            ->sort()
            ->values();

        foreach ($allSources as $source) {
            $leads = $this->leadBySource[$source] ?? 0;
            $bookings = $this->bookingBySource[$source] ?? 0;
            $total = $leads + $bookings;
            $rows[] = [
                'source' => $source,
                'leads' => $leads,
                'bookings' => $bookings,
                'total' => $total,
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'المصدر',
            'عملاء محتملون',
            'طلبات تقسيط',
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
