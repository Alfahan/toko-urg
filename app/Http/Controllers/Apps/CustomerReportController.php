<?php

namespace App\Http\Controllers\Apps;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerReportController extends Controller
{
    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        return Excel::download(new CustomersExport(), 'reports_customer.xlsx');
    }

    /**
     * pdf
     *
     * @param  mixed $request
     * @return void
     */
    public function pdf(Request $request)
    {
        //get purchase by range date
        $customers = Customer::get();

        //load view PDF with data
        $pdf = PDF::loadView('exports.reports_customer', compact('customers'));

        //return PDF for preview / download
        return $pdf->download('reports_customer.pdf');
    }

}
