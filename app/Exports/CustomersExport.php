<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomersExport implements FromView
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
        return view('exports.reports_customer', [
            'customers' => Customer::get()
        ]);
    }
}
