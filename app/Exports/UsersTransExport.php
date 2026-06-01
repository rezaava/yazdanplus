<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersTransExport implements FromView, WithStyles, WithColumnWidths, WithColumnFormatting
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        [$fromYear, $fromMonth] = explode('-', request('from_date'));
    
        $fromYear = (int)$fromYear;
        $fromMonth = (int)$fromMonth;
    
        $start = new \Morilog\Jalali\Jalalian($fromYear, $fromMonth, 1);
        $startOfMonth = $start->toCarbon();
        $endOfMonth = $start->getEndDayOfMonth()->format('Y-m-d');

        return view('admin.partials.users_trans_excel', [
            'data' => $this->data,
            'month' => $endOfMonth
        ]);
    }

    // اعمال قالب‌بندی عددی برای ستون E (جمع قسط این ماه) - بدون اعشار
    public function columnFormats(): array
    {
        return [
            'E' => '#,##0',  // سه رقم سه رقم جدا کن ولی اعشار نداشته باشه
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 18,
            'C' => 22,
            'D' => 15,
            'E' => 25,
            'F' => 25,
            'G' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $headerRange = 'A1:G1';

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 13,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '008000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $lastRow = $sheet->getHighestRow();
        $bodyRange = "A2:G{$lastRow}";

        $sheet->getStyle($bodyRange)->applyFromArray([
            'font' => ['size' => 11],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => 'D0D0D0'],
                ],
            ],
        ]);

        return [];
    }
}