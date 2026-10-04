<?php

namespace App\Exports;

use App\Models\Product_orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DatisExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths
{
    public function collection()
    {
        $productOrders = Product_orders::with([
            'order.user',
            'product'
        ])
        ->whereHas('order', function ($query) {
            $query->where('datis_turn', 2);
        })
        ->whereHas('product', function ($query) {
            $query->where('type', 1);
        })
        ->get();

        $users = $productOrders
            ->filter(function ($item) {
                return $item->order && $item->order->user;
            })
            ->groupBy(function ($item) {
                return $item->order->user_id;
            });

        $data = collect();

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

            $products = $userOrders
                ->filter(function ($item) {
                    return $item->product && $item->num > 0;
                })
                ->groupBy('product_id');

            $productList = [];

            foreach ($products as $productOrders) {

                $product = $productOrders->first()->product;

                $count = $productOrders->sum('num');

                $productList[] =
                    '☐ ' . $count . ' عدد ' . $product->name;
            }

            $data->push([
                'نام' => $user->name ?? '',
                'نام خانوادگی' => $user->family ?? '',
                'موبایل' => $user->mobile ?? '',
                'محصولات خریداری شده' => implode("\n", $productList),
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'نام',
            'نام خانوادگی',
            'موبایل',
            'محصولات خریداری شده',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->setRightToLeft(true);

        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

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
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'DDDDDD',
                    ],
                ],
            ],
        ]);

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

            $sheet->getStyle("D2:D{$highestRow}")
                ->getAlignment()
                ->setWrapText(true)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }

        $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");

        $sheet->freezePane('A2');

        $sheet->getRowDimension(1)->setRowHeight(30);

        for ($row = 2; $row <= $highestRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(45);
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 20,
            'C' => 18,
            'D' => 60,
        ];
    }
}