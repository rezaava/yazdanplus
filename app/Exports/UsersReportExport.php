<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Morilog\Jalali\Jalalian;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class UsersReportExport implements FromView,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
//    public function collection()
//    {
//        return User::all();
//    }

    /**
     * @return View
     */


   protected $fromDateShamsi;
    protected $toDateShamsi;

    public function __construct($fromDateShamsi = null, $toDateShamsi = null)
    {
        $this->fromDateShamsi = $fromDateShamsi;
        $this->toDateShamsi = $toDateShamsi;
    }


    public function view(): View
    {
         if ($this->fromDateShamsi && $this->toDateShamsi) {
            $fromMiladi = Jalalian::fromFormat('Y/m/d', $this->fromDateShamsi)->toCarbon()->startOfDay();
            $toMiladi = Jalalian::fromFormat('Y/m/d', $this->toDateShamsi)->toCarbon()->endOfDay();
        } else {
            // اگر تاریخ داده نشده، کل داده‌ها رو بگیر
            $fromMiladi = null;
            $toMiladi = null;
        }

      
        $users = User::get();
        foreach($users as $user){
            $transactions = Transaction::where('user_id',$user->id)
            ->where('tarikh_ghest','>=',$fromMiladi)
            ->where('tarikh_ghest','<=',$toMiladi)->
            sum('value')
            ;
            $user['jam']=$transactions;
        }
        return view('admin.gozareshuser',compact('users'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
return [];
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getStyle("A1:Z20")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getRowDimension("1")->setRowHeight(50);
                $event->sheet->getRowDimension("2")->setRowHeight(25);
                $event->sheet->getRowDimension("3")->setRowHeight(25);
                $event->sheet->getRowDimension("4")->setRowHeight(25);
                $event->sheet->getRowDimension("5")->setRowHeight(20);
                $event->sheet->getColumnDimension('A')->setWidth(24);
                $event->sheet->getColumnDimension('b')->setWidth(24);
                $event->sheet->getColumnDimension('c')->setWidth(24);
                $event->sheet->getColumnDimension('d')->setWidth(24);
                $event->sheet->getColumnDimension('e')->setWidth(24);
                $event->sheet->getColumnDimension('f')->setWidth(24);
                $event->sheet->getColumnDimension('g')->setWidth(24);
                $event->sheet->getColumnDimension('h')->setWidth(24);
                $event->sheet->getColumnDimension('i')->setWidth(24);
                $event->sheet->getColumnDimension('j')->setWidth(24);
                $event->sheet->getColumnDimension('k')->setWidth(24);
                $event->sheet->getColumnDimension('l')->setWidth(24);
                $event->sheet->getColumnDimension('m')->setWidth(24);
                $event->sheet->getColumnDimension('n')->setWidth(24);
                $event->sheet->getStyle("A1")->getFont()->setName("Ventilate");
                $event->sheet->getStyle("A2")->getFont()->setName("CG Times");
                $event->sheet->getStyle("A1:Z20")->getFont()->setSize(14);
                $event->sheet->getStyle("A2")->getFont()->setSize(10);
                // $event->sheet->getStyle("A1:N1")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("A1:N1")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);;
                // $event->sheet->getStyle("A1")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("A1")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("A2:A3")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("I2")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("N2")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("I3")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("N3")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("N1")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("A4")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("C4")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("F4")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("A4:B4")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("A4:B4")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("C4:E4")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("C4:E4")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("F4:H4")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("F4:H4")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("I4")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("I4")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("I4")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("J4")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("J4")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("K4:N4")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("K4:N4")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $event->sheet->getStyle("N4")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                // $yellow = 6 + session()->get('packing_count') + 1;
                // session()->forget('packing_count');
                // $event->sheet->getStyle("A6:N".($yellow-1))->getFont()->setName("Arial");
                // $event->sheet->getStyle("A6:N6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                // $event->sheet->getStyle("A6:N6")->getFill()
                    // ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    // ->getStartColor()->setARGB('FFFFCC00');
                // $event->sheet->getStyle("A$yellow:N$yellow")->getFill()
                //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //     ->getStartColor()->setARGB('FFFFCC00');
                // $event->sheet->getStyle("A".($yellow+2).":N".($yellow+2))->getFill()
                //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //     ->getStartColor()->setARGB('FFFFCC00');
                // $event->sheet->getStyle("A1")->getFont()->setSize(36);
                // $event->sheet->getStyle("A6:N".($yellow-1))->getFont()->setSize(9);
                $event->sheet->getStyle("A2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle("A5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle("A6:N9")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle("A11:N100")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:Z1000')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle("A2")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle("I2")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle("I3")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle("A4:N4")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle("A6:N6")->getFont()->setBold(true);
                $event->sheet->getStyle("A1:z500")->getFont()->setBold(true);
                // $event->sheet->getStyle("A".($yellow+2).":N".($yellow+2))->getFont()->setBold(true);
                // $event->sheet->getStyle("A".($yellow).":N".($yellow))->getFont()->setBold(true);

                //sum
                if($this->sahm)
                {
                    $event->sheet->getStyle("A1:i2")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                $event->sheet->getStyle("A1:i2")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                $event->sheet->getStyle("A1:i2")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                $event->sheet->getStyle("A1:i2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                }
                else{
                    $event->sheet->getStyle("A1:j2")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                $event->sheet->getStyle("A1:j2")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                $event->sheet->getStyle("A1:j2")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                $event->sheet->getStyle("A1:j2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
             
                }
            },
        ];
  
    }

    function convert($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
}
