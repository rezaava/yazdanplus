<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditExport implements FromArray, WithStyles, WithEvents, WithTitle
{
    protected $shopId;
    protected $fromDate;
    protected $toDate;
    protected $lastRow = 1;

    public function __construct($shopId, $fromDate, $toDate)
    {
        $this->shopId = $shopId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function array(): array
    {
        $rows = [];

        // =======================
        // اقساط
        // =======================
        $installments = Order::where('shop_id', $this->shopId)
            ->whereIn('status', [2])
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->get();

        $rows[] = ['گزارش فروش'];
        $rows[] = ['تاریخ','نوع','مبلغ'];

        $sumInstallments = 0;

        foreach ($installments as $t) {
            $rows[] = [
                jdate($t->tarikh_ghest)->format('Y/m/d'),
                $t->status,
                $t->price
            ];
            $sumInstallments += $t->price;
        }

        $rows[] = ['جمع کل','', $sumInstallments];
        $rows[] = [];
        $rows[] = [];

        // =======================
        // تسویه
        // =======================
        $settlements = Transaction::where('shop_id', $this->shopId)
            ->where('type', 5)
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->get();

        $rows[] = ['گزارش تسویه‌ها'];
        $rows[] = ['تاریخ','نوع','مبلغ'];

        $sumSettlement = 0;

        foreach ($settlements as $t) {
            $rows[] = [
                jdate($t->created_at)->format('Y/m/d'),
                $t->type,
                $t->value
            ];
            $sumSettlement += $t->value;
        }

        $rows[] = ['جمع کل','', $sumSettlement];
        $rows[] = [];
        $rows[] = [];

        // =======================
        // تسویه با تخفیف
        // =======================
        $discount = Transaction::where('shop_id', $this->shopId)
            ->where('type', 18)
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->get();

        $rows[] = ['گزارش تسویه با تخفیف'];
        $rows[] = ['تاریخ','نوع','مبلغ'];

        $sumDiscount = 0;

        foreach ($discount as $t) {
            $rows[] = [
                jdate($t->created_at)->format('Y/m/d'),
                $t->type,
                $t->value
            ];
            $sumDiscount += $t->value;
        }

        $rows[] = ['جمع کل','', $sumDiscount];

        $this->lastRow = count($rows);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A:C' => [
                'alignment' => ['horizontal' => 'center'],
                'font' => ['size' => 12],
            ],
        ];
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            $sheet = $event->sheet->getDelegate();

            // =========================
            // عرض ستون‌ها
            // =========================
            $sheet->getColumnDimension('A')->setWidth(25);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(20);

            // =========================
            // هدر اصلی
            // =========================
            $sheet->insertNewRowBefore(1, 2);

            $sheet->mergeCells('A1:C1');
            $sheet->setCellValue('A1', 'گزارش حسابرسی فروشگاه');

            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['argb' => 'FF2E7D32'],
                ],
            ]);

            // =========================
            // استایل کل جدول
            // =========================
            $lastRow = $this->lastRow + 2;

            $sheet->getStyle("A1:C{$lastRow}")
                ->getAlignment()
                ->setHorizontal('center');

            // =========================
            // پیدا کردن تیترها و جمع کل‌ها
            // =========================
            for ($row = 1; $row <= $lastRow; $row++) {

                $value = $sheet->getCell("A{$row}")->getValue();

                if (!$value) continue;

                // تیتر بخش
                if (str_contains($value, 'گزارش')) {

                    $sheet->mergeCells("A{$row}:C{$row}");

                    $sheet->getStyle("A{$row}:C{$row}")
                        ->applyFromArray([
                            'font' => ['bold' => true, 'size' => 14],
                            'fill' => [
                                'fillType' => 'solid',
                                'startColor' => ['argb' => 'FFE8F5E9'],
                            ],
                        ]);
                }

                // ردیف عنوان جدول
                if ($value === 'تاریخ') {
                    $sheet->getStyle("A{$row}:C{$row}")
                        ->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['argb' => 'FFFFFFFF']
                            ],
                            'fill' => [
                                'fillType' => 'solid',
                                'startColor' => ['argb' => 'FF4CAF50'],
                            ],
                        ]);
                }

                // جمع کل
                if (str_contains($value, 'جمع کل')) {
                    $sheet->getStyle("A{$row}:C{$row}")
                        ->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => 'solid',
                                'startColor' => ['argb' => 'FFC8E6C9'],
                            ],
                        ]);
                }
            }

            // =========================
            // بوردر کل محدوده
            // =========================
            $sheet->getStyle("A1:C{$lastRow}")
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FFDDDDDD'],
                        ],
                    ],
                ]);
        },
    ];
}

    public function title(): string
    {
        return 'گزارش حسابرسی';
    }
}