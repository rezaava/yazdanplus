<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Morilog\Jalali\Jalalian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $fromDate;
    protected $toDate;
    protected $dataCount = 0;
    protected $user;

    public function __construct($fromDate, $toDate, $user)
    {
        $this->fromDate = $fromDate;
        $this->toDate   = $toDate;
        $this->user     = $user;
    }

    public function collection()
    {
        $query = Order::where('status',2)->with('shop','user')
            ->whereBetween('created_at', [
                $this->fromDate,
                $this->toDate
            ]);
    
        // اگر ادمین فروشگاه بود
        if ($this->user->hasRole('shop_admin')) {
    
            $shop = \App\Models\Shop::where('user_id', $this->user->id)->first();
    
            if ($shop) {
                $query->where('shop_id', $shop->id);
            } else {
                return new Collection([]); // اگر فروشگاه نداشت
            }
        }
    
        $orders = $query->get();
    
        $data = $orders->map(function ($order) {
            return [
                'شناسه' => $order->id,
                'فروشگاه' => $order->shop->name ?? '',
                'خریدار' => $order->user->name ?? '',
                'مبلغ' => number_format($order->price),
                'وضعیت' => $order->getStatus(),
                'وضعیت تسویه' => $order->getStatus_tasvie(),
                'تاریخ' => \Morilog\Jalali\Jalalian::fromDateTime($order->created_at)->format('Y/m/d'),
                'ساعت' => \Morilog\Jalali\Jalalian::fromDateTime($order->created_at)->format('H:i'),
            ];
        });
    
        $this->dataCount = $data->count();
    
        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'خریدار',
            'فروشگاه',
            'مبلغ',
            'وضعیت',
            'وضعیت تسویه',
            'تاریخ',
            'ساعت',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->dataCount + 1;

        return [

            // استایل هدر
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 13
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['argb' => 'FF4CAF50']
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ],
            ],

            // وسط چین کل جدول
            'A:H' => [
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ],
                'font' => [
                    'size' => 12
                ],
            ],

            // بوردر کل جدول
            "A1:H{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFCCCCCC'],
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(18);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(12);

            },
        ];
    }
}