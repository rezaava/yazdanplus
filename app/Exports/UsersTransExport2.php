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

class UsersTransExport2 implements FromView, WithStyles, WithColumnWidths, WithColumnFormatting
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        [$fromYear, $fromMonth] = explode('-', request('from_date'));

        $start = new \Morilog\Jalali\Jalalian((int)$fromYear, (int)$fromMonth, 1);
        $endOfMonth = $start->getEndDayOfMonth()->format('Y-m-d');

        return view('admin.partials.users_trans_excel2', [
            'data'  => $this->data,
            'month' => $endOfMonth,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'E' => '#,##0',
            'F' => '#,##0',
            'G' => '#,##0',
            'H' => '#,##0',
            'I' => '#,##0',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // نام
            'B' => 18,  // فامیل
            'C' => 16,  // موبایل
            'D' => 14,  // کد ملی
            'E' => 18,  // داتیس ۱
            'F' => 18,  // داتیس ۲
            'G' => 22,  // جمع قسط
            'H' => 22,  // قسط این ماه
            'I' => 18,  // شارژ اولیه
            'J' => 18,  // نوع
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastDataRow = $lastRow - 1;

        // هدر
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
                'name' => 'Tahoma',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '008000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // بدنه
        if ($lastDataRow >= 2) {
            $sheet->getStyle("A2:J{$lastDataRow}")->applyFromArray([
                'font' => ['size' => 11, 'name' => 'Tahoma'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['rgb' => 'D0D0D0'],
                    ],
                ],
            ]);
        }

        // ردیف مجموع
        $summaryRow = $lastDataRow + 1;
        $sheet->getStyle("A{$summaryRow}:J{$summaryRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'name' => 'Tahoma'],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF3CD'],
            ],
        ]);

        $sheet->setRightToLeft(true);
        $sheet->freezePane('A2');
        $sheet->getRowDimension(1)->setRowHeight(28);

        return [];
    }
}