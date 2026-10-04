<?php

namespace App\Exports;

use App\Models\Product_orders;
use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArdeSaleExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        $products = Products::where('status', 1)
            ->where('type', 2)
            ->get();

        $productOrders = Product_orders::with([
            'order.user',
            'product'
        ])
        ->whereHas('order', function ($query) {
            $query->where('shop_id', 8)
                ->where('status', 11);
        })
        ->orderBy('id', 'desc')
        ->get();

        $grouped = $productOrders->groupBy(function ($item) {
            return $item->order->user_id . '_' . $item->order->datis_turn;
        });

        $rows = [];

        foreach ($grouped as $orders) {

            $firstOrder = $orders->first();

            if (!$firstOrder || !$firstOrder->order || !$firstOrder->order->user) {
                continue;
            }

            $user = $firstOrder->order->user;
            $order = $firstOrder->order;

            $row = [
                $user->name ?? '',
                $user->family ?? '',
                $user->mobile ?? '',
                $order->datis_turn == 1 ? 'نوبت اول' : 'نوبت دوم',
            ];

            foreach ($products as $product) {

                $count = $orders
                    ->where('product_id', $product->id)
                    ->sum('num');

                $row[] = $count;
            }

            $rows[] = $row;
        }

        // ردیف مجموع
        $totalRow = [
            '',
            '',
            '',
            'مجموع',
        ];

        foreach ($products as $product) {

            $total = $productOrders
                ->where('product_id', $product->id)
                ->sum('num');

            $totalRow[] = $total;
        }

        $rows[] = $totalRow;

        // ردیف قیمت
        $priceRow = [
            '',
            '',
            '',
            'قیمت واحد',
        ];

        foreach ($products as $product) {
            $priceRow[] = $product->price;
        }

        $rows[] = $priceRow;

        return collect($rows);
    }

    public function headings(): array
    {
        $products = Products::where('status', 1)
            ->where('type', 2)
            ->get();

        $headings = [
            'نام',
            'نام خانوادگی',
            'موبایل',
            'وضعیت',
        ];

        foreach ($products as $index => $product) {
            $headings[] = 'محصول ' . ($index + 1) . ' - ' . $product->name;
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        // عنوان
        $sheet->getStyle('A1:' . $lastColumn . '1')->getFont()->setBold(true);

        // ردیف مجموع
        $sheet->getStyle('A' . ($lastRow - 1) . ':' . $lastColumn . ($lastRow - 1))
            ->getFont()
            ->setBold(true);

        // ردیف قیمت
        $sheet->getStyle('A' . $lastRow . ':' . $lastColumn . $lastRow)
            ->getFont()
            ->setBold(true);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 20,
            'C' => 18,
            'D' => 15,
        ];
    }
}