<?php

namespace App\Exports;

use App\Models\CostTransaction;
use App\Models\CostTransactionDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CostReportExport implements FromView
{
    protected $start_date;
    protected $end_date;

    /**
     * __construct
     *
     * @param  mixed $start_date
     * @param  mixed $end_date
     * @return void
     */
    public function __construct($start_date, $end_date)
    {
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
        return view('exports.reports_cost', [
            'cost' => CostTransactionDetail::with([
                'cost_transaction' => function($q) {
                    $q->with(['admin']);
                }
            ])->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->get(),
            'total' => CostTransaction::whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->sum('grand_total')
        ]);
    }
}
