<?php

namespace App\Exports;

use App\Models\Product_orders;
use App\Models\Products;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class datis_old implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths
{
    private $products;

    private $productTotals = [];
    private $productAmounts = [];

    public function __construct()
    {
        $this->products = Products::where('status', 1)->where('type',1)->get();
    }

    public function collection()
    {
        $productOrders = Product_orders::with([
            'order.user',
            'product'
        ])->get();
    
        $users = $productOrders
            ->filter(function ($item) {
                return $item->order && $item->order->user;
            })
            ->groupBy(function ($item) {
    
                // کاربر + نوبت داتیس
                return $item->order->user_id . '_' . $item->order->datis_turn;
            });
    
        $data = collect();
    
        // ریست کردن مجموع‌ها
        $this->productTotals = [];
        $this->productAmounts = [];
    
        foreach ($this->products as $product) {
            $this->productTotals[$product->id] = 0;
            $this->productAmounts[$product->id] = 0;
        }
    
        foreach ($users as $userOrders) {
    
            $firstItem = $userOrders->first();
    
            if (
                !$firstItem ||
                !$firstItem->order ||
                !$firstItem->order->user
            ) {
                continue;
            }
    
            $user = $firstItem->order->user;
    
            // وضعیت نوبت داتیس
            $datisTurn = $firstItem->order->datis_turn ?? '';
    
            if ($datisTurn == 1) {
                $turnLabel = 'نوبت اول';
            } elseif ($datisTurn == 2) {
                $turnLabel = 'نوبت دوم';
            } else {
                $turnLabel = '';
            }
    
            $row = [
                'نام' => $user->name ?? '',
                'نام خانوادگی' => $user->family ?? '',
                'موبایل' => $user->mobile ?? '',
                'وضعیت' => $turnLabel,
            ];
    
            foreach ($this->products as $product) {
    
                // تعداد این محصول برای این کاربر + این نوبت
                $count = $userOrders
                    ->where('product_id', $product->id)
                    ->sum('num');
    
                $row[$product->name] = $count;
    
                // اضافه کردن به مجموع واقعی اکسل
                $this->productTotals[$product->id] += $count;
    
                // قیمت واحد با تخفیف
                $unitPrice = $product->price;
    
                if ($product->off_percent > 0) {
                    $unitPrice = $unitPrice -
                        (($unitPrice * $product->off_percent) / 100);
                }
    
                // مبلغ این محصول
                $this->productAmounts[$product->id] +=
                    $count * $unitPrice;
            }
    
            $data->push($row);
        }
    
        return $data;
    }
    public function headings(): array
    {
        $headings = [
            'نام',
            'نام خانوادگی',
            'موبایل',
            'وضعیت',
        ];

        foreach ($this->products as $product) {
            $headings[] = $product->name;
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $summaryCountRow = $highestRow + 2;
        $summaryAmountRow = $highestRow + 3;
        $highestColumn = $sheet->getHighestColumn();

        // عنوان مجموع تعداد
        $sheet->setCellValue(
            "D{$summaryCountRow}",
            'مجموع تعداد'
        );

        // عنوان مجموع مبلغ
        $sheet->setCellValue(
            "D{$summaryAmountRow}",
            'مجموع مبلغ'
        );

        $sheet->setRightToLeft(true);

        // ستون محصولات از E شروع می‌شود (A,B,C,D = نام، فامیل، موبایل، وضعیت)
        $column = 'E';

        foreach ($this->products as $product) {

            // مجموع تعداد محصول
            $sheet->setCellValue(
                "{$column}{$summaryCountRow}",
                $this->productTotals[$product->id] ?? 0
            );

            // مجموع مبلغ محصول
            $sheet->setCellValue(
                "{$column}{$summaryAmountRow}",
                $this->productAmounts[$product->id] ?? 0
            );

            // فرمت مبلغ
            $sheet->getStyle("{$column}{$summaryAmountRow}")
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            $column++;
        }

        $sheet->getStyle(
            "D{$summaryCountRow}:{$highestColumn}{$summaryAmountRow}"
        )->applyFromArray([
            'font' => [
                'bold' => true,
                'name' => 'Tahoma',
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F3F4F6',
                ],
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

        // وسط‌چین کردن
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // هدر
        $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
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
        ]);

        // بدنه
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

        // فیلتر
        $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");

        // ثابت ماندن هدر
        $sheet->freezePane('A2');

        // ارتفاع هدر
        $sheet->getRowDimension(1)->setRowHeight(30);
    }

    public function columnWidths(): array
    {
        $widths = [];

        // ستون‌های اول
        $widths['A'] = 18;  // نام
        $widths['B'] = 20;  // نام خانوادگی
        $widths['C'] = 18;  // موبایل
        $widths['D'] = 15;  // وضعیت

        // ستون‌های محصولات از E شروع می‌شوند
        $column = 'E';
        for ($i = 0; $i < count($this->products); $i++) {
            $widths[$column] = 18;
            $column++;
        }

        return $widths;
    }
}