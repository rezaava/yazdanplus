<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Collection;
use Morilog\Jalali\Jalalian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ShopRizOrderExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $fromDate;
    protected $toDate;
    protected $shopId;
    protected $shopName;
    protected $dataCount = 0;

    public function __construct($fromDate, $toDate, $shopId, $shopName)
    {
        $this->fromDate = $fromDate;
        $this->toDate   = $toDate;
        $this->shopId   = $shopId;
        $this->shopName = $shopName;
    }

    public function collection()
    {
        $orders = Order::where('status', 2)
            ->where('shop_id', $this->shopId)
            ->whereBetween('created_at', [
                $this->fromDate,
                $this->toDate
            ])
            ->orderBy('created_at', 'desc')
            ->get();

            foreach($orders as $order){
                $order['user']=User::where('id',$order->user_id)->first();
                $order['shop']=Shop::where('id',$order->shop_id)->first();
            }

        $data = $orders->map(function ($order) {
            return [
                'شناسه' => $order->id,
                'نام فروشگاه' => $order->shop->name ?? 'کاربر مهمان',
                'نام خریدار' => $order->user->name . $order->user->family ?? 'کاربر مهمان',
                'تلفن خریدار' => $order->user->mobile ?? '---',
                'مبلغ (تومان)' => number_format($order->price),
                'وضعیت سفارش' => $order->getStatus(),
                'وضعیت تسویه' => $order->getStatus_tasvie(),
                'تاریخ ثبت' => Jalalian::fromDateTime($order->created_at)->format('Y/m/d'),
                'ساعت ثبت' => Jalalian::fromDateTime($order->created_at)->format('H:i'),
            ];
        });

        $this->dataCount = $data->count();
        
        // اگر داده‌ای وجود نداشت، یک ردیف پیام نمایش بده
        if ($this->dataCount == 0) {
            return new Collection([
                [
                    '---',
                    '---',
                    '---',
                    '---',
                    '---',
                    '---',
                    '---',
                    'هیچ سفارشی یافت نشد',
                    '---',
                ]
            ]);
        }

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'شناسه سفارش',
            'نام فروشگاه',
            'نام خریدار',
            'تلفن خریدار',
            'مبلغ (تومان)',
            'وضعیت سفارش',
            'وضعیت تسویه',
            'تاریخ ثبت',
            'ساعت ثبت',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->dataCount + 1;
        
        // اگر داده نداشت، فقط 2 ردیف داریم
        if ($this->dataCount == 0) {
            $lastRow = 2;
        }

        return [
            // استایل هدر (ردیف اول)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 12
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

            // تنظیم ارتفاع ردیف هدر
            1 => [
                'row_height' => 25,
            ],

            // وسط چین و فونت تمام سلول‌ها
            'A1:I' . $lastRow => [
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ],
                'font' => [
                    'size' => 11,
                    'name' => 'B Nazanin'
                ],
            ],

            // بوردر کل جدول
            'A1:I' . $lastRow => [
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
                
                // تنظیم عرض ستون‌ها
                $sheet->getColumnDimension('A')->setWidth(12); // شناسه
                $sheet->getColumnDimension('B')->setWidth(25); // نام خریدار
                $sheet->getColumnDimension('C')->setWidth(15); // تلفن
                $sheet->getColumnDimension('D')->setWidth(18); // مبلغ
                $sheet->getColumnDimension('E')->setWidth(18); // وضعیت سفارش
                $sheet->getColumnDimension('F')->setWidth(18); // وضعیت تسویه
                $sheet->getColumnDimension('G')->setWidth(15); // تاریخ
                $sheet->getColumnDimension('H')->setWidth(12); // ساعت
                $sheet->getColumnDimension('I')->setWidth(12); // ساعت
                
                // ستون مبلغ را به سمت چپ ببر
                $sheet->getStyle('D2:D' . ($this->dataCount + 1))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                
                // اضافه کردن عنوان بالای جدول
                $shopName = $this->shopName;
                $fromDate = Jalalian::fromDateTime($this->fromDate)->format('Y/m/d');
                $toDate = Jalalian::fromDateTime($this->toDate)->format('Y/m/d');
                
                $sheet->insertNewRowBefore(1, 1);
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', "گزارش ریز خریدهای فروشگاه: {$shopName} - بازه: {$fromDate} تا {$toDate}");
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
                // تغییر رنگ پس زمینه هدر
                $sheet->getStyle('A2:I2')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF2196F3');
            },
        ];
    }
}