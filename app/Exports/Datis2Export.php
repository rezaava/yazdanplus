<?php

namespace App\Exports;

use App\Models\Datis2Order;
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

class Datis2Export implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths
{
    private $products;

    public function __construct()
    {
        $this->products = Products::where('status', 1)->get();
    }

    public function collection()
    {
        // گرفتن همه سفارش‌ها با محصولات
        $orders = Datis2Order::with('products.product')
            ->where('status', 10)
            ->orderBy('id', 'desc')
            ->get();

        $data = collect();

        foreach ($orders as $order) {
            $row = [
                'نام' => $order->name ?? '',
                'نام خانوادگی' => $order->family ?? '',
                'موبایل' => $order->mobile ?? '',
                'کد ملی' => $order->meli_code ?? '',
            ];

            foreach ($this->products as $product) {
                $productOrder = $order->products->firstWhere('product_id', $product->id);
                $count = $productOrder ? $productOrder->num : 0;
                $row[$product->name] = $count;
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
            'کد ملی',
        ];

        foreach ($this->products as $product) {
            $headings[] = $product->name;
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->setRightToLeft(true);

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
                    'rgb' => 'DC2626', // قرمز
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
        $count = count($this->products) + 4; // 4 ستون اول (نام، نام خانوادگی، موبایل، کد ملی)
        
        $widths = [];
        
        // ستون‌های اول
        $widths['A'] = 18; // نام
        $widths['B'] = 20; // نام خانوادگی
        $widths['C'] = 18; // موبایل
        $widths['D'] = 18; // کد ملی
        
        // ستون‌های محصولات
        $column = 'E';
        for ($i = 0; $i < count($this->products); $i++) {
            $widths[$column] = 18;
            $column++;
        }
        
        return $widths;
    }
}