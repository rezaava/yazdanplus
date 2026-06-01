<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use App\Models\Transaction;

class TransactionsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $fromDate;
    protected $toDate;
    protected $dataCount = 0;  // تعداد ردیف‌ها را اینجا ذخیره می‌کنیم
    protected $userId;

    public function __construct($fromDate, $toDate, $userId)
{
    $this->fromDate = $fromDate;
    $this->toDate = $toDate;
    $this->userId = $userId;
}

    public function collection()
    {
        
    $ordersId=Order::where('user_id' ,$this->userId)->whereIn('status',['2','8'])->pluck('id');


$transactions = Transaction::with('user', 'shop')
->whereIn('order_id', $ordersId)
->whereBetween('tarikh_ghest', [$this->fromDate, $this->toDate])
->orderBy('tarikh_ghest','asc')
->get();

$transactions_sum = Transaction::with('user', 'shop')
->whereIn('order_id', $ordersId)
->whereBetween('tarikh_ghest', [$this->fromDate, $this->toDate])
->orderBy('tarikh_ghest','asc')
->sum('value'); // این مقدار جمع کل است


$data = $transactions->map(function($t){
return [
    'فروشگاه' => $t->shop->name ?? '',
    'بستانکار' => number_format($t->value),
    'بدهکار' => $t->order->price ?? '', // اگر بدهکار هم داری و می‌خوای نمایش بدی
    'تاریخ' => \Morilog\Jalali\Jalalian::fromDateTime($t->tarikh_ghest)->format('Y/m/d'), 
    'شماره سفارش' => $t->id ?? '',
];
});

// حالا ردیف جمع را اضافه می‌کنیم
$data->push([ // از push استفاده می‌کنیم که در انتهای مجموعه قرار بگیرد
'فروشگاه' => 'جمع کل', // یا هر متن دلخواه برای این ستون
'بستانکار' => number_format($transactions_sum), // نمایش جمع کل با فرمت دلخواه
'بدهکار' => '', // خالی می‌گذاریم یا اگر جمع بدهکار هم داری اینجا اضافه کن
'تاریخ' => '',
'شماره سفارش' => '',
]);


$this->dataCount = $data->count(); // تعداد کل ردیف‌ها (با احتساب ردیف جمع)

return new Collection($data); // Collection را برمی‌گردانیم

        
    }

    public function headings(): array
    {
        return ['فروشگاه','بستانکار','بدهکار', 'تاریخ','شناسه'];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->dataCount + 1; // +1 برای سرفصل‌ها

        return [
            // استایل ردیف عنوان (سرستون‌ها)
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4CAF50']],
                'alignment' => ['horizontal' => 'center'],
            ],

            // تراز وسط برای کل ستون‌ها (A تا D)
            'A:E' => [
                'alignment' => ['horizontal' => 'center'],
                'font' => ['size' => 12],
            ],

            // خطوط دور جدول
            "A1:E{$lastRow}" => [
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
                // تنظیم عرض ستون‌ها به صورت دلخواه
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20); // فروشگاه
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50); // بستانکار
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15); // بدهکار
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(18); //  تاریخ
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(18); //  شناسه
            },
        ];
    }
}
