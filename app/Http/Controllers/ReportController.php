<?php

namespace App\Http\Controllers;

use App\Exports\UsersReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //

public function export()
{
    $fromDate = request('from_date'); // تاریخ شمسی
    $toDate = request('to_date');     // تاریخ شمسی
        return Excel::download(
            new UsersReportExport($fromDate, $toDate), 'users_report.xlsx'
        );
    // return Excel::Excell(new UsersReportExport($fromDate, $toDate), 'users_report.xlsx');
}
}
