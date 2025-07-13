<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesExport implements FromView
{
    protected $invoice;
    protected $start_date;
    protected $end_date;

    /**
     * __construct
     *
     * @param  mixed $start_date
     * @param  mixed $end_date
     * @return void
     */
    public function __construct($invoice, $start_date, $end_date)
    {
        $this->invoice = $invoice;
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
    }

    /**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        if($this->invoice != null){
            $sales = Transaction::with('cashier', 'customer')->where('invoice', '=', $this->invoice)->get();
            $total = Transaction::where('invoice', '=', $this->invoice)->sum('grand_total');
        }

        if($this->start_date != null && $this->end_date != null){
            $sales = Transaction::with('cashier', 'customer')->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->get();
            $total = Transaction::whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->sum('grand_total');
        }

        return view('exports.sales', [
            'sales' => $sales,
            'total' => $total
        ]);
    }
}
