<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Morilog\Jalali\Jalalian;

class UserInstallmentsExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles
{
    protected $userId;
    protected $from;
    protected $to;

    public function __construct($userId, $from, $to)
    {
        $this->userId = $userId;
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $transactions = Transaction::leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
            ->where('transactions.user_id', $this->userId)
            ->whereBetween('transactions.tarikh_ghest', [$this->from, $this->to])
            ->select('transactions.*', 'orders.price as order_price')
            ->get();


        return $transactions->map(function ($trx) {
            return [
                'نام' => $trx->user->name ?? '---',
                'فامیل' => $trx->user->family ?? '---',
                'موبایل' => $trx->user->mobile ?? '---',
                // مبلغ به صورت عددی خالص بدون ریال برای فرمت کردن در اکسل
                'مبلغ' => $trx->order_price ?? 0,
                'تاریخ قسط' => Jalalian::fromDateTime($trx->tarikh_ghest)->format('Y/m/d'),
                'وضعیت' => $this->getTypeName($trx->type),
            ];
        });
    }

    public function headings(): array
    {
        return ['نام', 'فامیل', 'موبایل', 'مبلغ (ریال)', 'تاریخ قسط', 'وضعیت'];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // فرمت عدد با جداکننده هزارگان برای ستون مبلغ
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // تنظیم راست‌چین برای کل ستون‌ها (A تا F)
        $sheet->getStyle('A:F')->getAlignment()->setHorizontal('right');

        // تنظیم عرض ستون‌ها (اختیاری)
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // عنوان را بولد کنیم
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        return [];
    }

    private function getTypeName($type)
    {
        return match ($type) {
            15 => 'قسط',
            17 => 'رد قسط',
            18 => 'قسط پرداخت شده',
            19 => 'قسط پرداخت نشده',
            default => '---',
        };
    }
}
