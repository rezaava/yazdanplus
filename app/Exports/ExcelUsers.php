<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelUsers implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths
{
    public function collection()
    {
        $users = User::where('type', 2)
            ->orderBy('id', 'desc')
            ->get();

        return $users->map(function ($user) {

            return [
                $user->name ?? '',
                $user->family ?? '',
                $user->mobile ?? '',
                $user->nationalcode ?? '',
                $user->wallet ?? 0,
                $this->getTypeName($user->type),
            ];

        });
    }

    public function headings(): array
    {
        return [
            'نام',
            'نام خانوادگی',
            'موبایل',
            'کد ملی',
            'اعتبار',
            'وضعیت',
        ];
    }

    private function getTypeName($type)
    {
        return match ((int) $type) {
            1 => 'هیئت علمی',
            2 => 'کارمند',
            3 => 'هیئت علمی بازنشسته',
            4 => 'کارمند بازنشسته',
            5 => 'لیست دکتر تدین',
            default => '',
        };
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // راست‌چین کردن کل Sheet
        $sheet->setRightToLeft(true);

        // وسط‌چین کردن همه سلول‌ها
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // هدر
        $sheet->getStyle("A1:{$highestColumn}1")
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                    'size' => 12,
                    'name' => 'Tahoma',
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '198754',
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [
                            'rgb' => 'CCCCCC',
                        ],
                    ],
                ],
            ]);

        // بدنه جدول
        if ($highestRow >= 2) {

            $sheet->getStyle("A2:{$highestColumn}{$highestRow}")
                ->applyFromArray([
                    'font' => [
                        'name' => 'Tahoma',
                        'size' => 11,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => [
                                'rgb' => 'DDDDDD',
                            ],
                        ],
                    ],
                ]);
        }

        // فرمت Wallet
        if ($highestRow >= 2) {

            $sheet->getStyle("E2:E{$highestRow}")
                ->getNumberFormat()
                ->setFormatCode('#,##0');
        }

        // فیلتر
        $sheet->setAutoFilter(
            "A1:{$highestColumn}{$highestRow}"
        );

        // ثابت ماندن هدر
        $sheet->freezePane('A2');

        // ارتفاع هدر
        $sheet->getRowDimension(1)->setRowHeight(30);

        // ارتفاع ردیف‌ها
        for ($row = 2; $row <= $highestRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(23);
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // نام
            'B' => 25, // نام خانوادگی
            'C' => 20, // موبایل
            'D' => 20, // کد ملی
            'E' => 20, // Wallet
            'F' => 25, // وضعیت
        ];
    }
}