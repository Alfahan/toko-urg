<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsExport implements FromView
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.reports_product', [
            'products' => Product::with('unit_of_measurement')->get()
        ]);
    }
}
