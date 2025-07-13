<?php

namespace App\Exports;

use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SuppliersExport implements FromView
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
        return view('exports.reports_supplier', [
            'suppliers' => Supplier::get()
        ]);
    }
}
