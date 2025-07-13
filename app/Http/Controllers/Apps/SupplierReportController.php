<?php

namespace App\Http\Controllers\Apps;

use App\Exports\SuppliersExport;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierReportController extends Controller
{
    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        return Excel::download(new SuppliersExport(), 'reports_supplier.xlsx');
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
        $suppliers = Supplier::get();

        //load view PDF with data
        $pdf = PDF::loadView('exports.reports_supplier', compact('suppliers'));

        //return PDF for preview / download
        return $pdf->download('reports_supplier.pdf');
    }
}
