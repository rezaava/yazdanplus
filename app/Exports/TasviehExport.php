<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class TasviehExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $fromDate;
    protected $toDate;

    public function __construct($fromDate,$toDate){
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function view():View
    {
        $transactions = Transaction::whereBetween('date',[$this->fromDate,$this->toDate])->where('type',5)->get();
        return view('admin.excel', compact('transactions'));
    }
}
