<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportExport implements FromView, WithStyles, WithColumnWidths, ShouldAutoSize
{
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('admin.report_excel', [
            'report' => $this->report,
        ]);
    }

    public function columnWidths(): array
    {
        // ستون‌ها: ماه، کل فروش، کل تسویه، تخفیف، باقی مانده
        return [
            'A' => 12,
            'B' => 18,
            'C' => 18,
            'D' => 12,
            'E' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // هدر معمولاً ردیف 1 است چون جدول شما تگ thead دارد
        $headerRange = 'A1:E1';

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // رنگ متن سفید
                'name' => 'Calibri',
                'size' => 14, // فونت بزرگتر شد
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '008000'], // رنگ سبز (قبلا آبی تیره بود)
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'outline' => ['borderStyle' => 'thin'],
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // استایل کل بدنه (از ردیف 2 تا انتها)
        $lastRow = $sheet->getHighestRow();
        $bodyRange = "A2:E{$lastRow}";

        $sheet->getStyle($bodyRange)->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 12, // فونت بدنه هم کمی بزرگتر شد
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT, // راست‌چین کردن برای اعداد
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => 'D0D0D0'],
                ],
            ],
        ]);

        // ردیف جمع کل (اگر آخرین ردیف است)
        $sheet->getStyle("A{$lastRow}:E{$lastRow}")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2'], // رنگ خاکستری برای جمع کل
            ],
        ]);

        return [];
    }
}
