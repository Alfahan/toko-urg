<?php

namespace App\Http\Controllers\Apps;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class ProductReportController extends Controller
{
    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        return Excel::download(new ProductsExport(), 'reports_product.xlsx');
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
        $products = Product::with('unit_of_measurement')->get();

        //load view PDF with data
        $pdf = PDF::loadView('exports.reports_product', compact('products'));

        //return PDF for preview / download
        return $pdf->download('reports_product.pdf');
    }


}
