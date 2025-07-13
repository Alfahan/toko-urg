<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ProductReport implements FromView
{
    protected $start_date;
    protected $end_date;

    /**
     * Constructor
     *
     * @param string $start_date
     * @param string $end_date
     */
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Generate the view for export
     *
     * @return View
     */
    public function view(): View
    {
        // Query utama dengan subquery untuk setiap transaksi
        $transactions = DB::table('products as p')
            ->select(
                'p.id as product_id',
                'p.title as product_title',
                'p.stock as stock',
                DB::raw('
                    COALESCE(dt.total_qty, 0) +
                    COALESCE(et.total_qty, 0) +
                    COALESCE(mt.total_qty, 0) +
                    COALESCE(kr.total_qty, 0) +
                    COALESCE(kt.total_qty, 0) +
                    COALESCE(rt.total_qty, 0) AS all_qty
                '),
                DB::raw('
                    COALESCE(dt.total_price, 0) +
                    COALESCE(et.total_price, 0) +
                    COALESCE(mt.total_price, 0) +
                    COALESCE(kr.total_price, 0) +
                    COALESCE(kt.total_price, 0) +
                    COALESCE(rt.total_price, 0) AS all_total_price
                ')
            )
            // LEFT JOIN Subquery untuk transaction_details
            ->leftJoinSub(
                DB::table('transaction_details')
                    ->select('product_id',
                        DB::raw('SUM(qty) AS total_qty'),
                        DB::raw('SUM(price) AS total_price'))
                    ->whereBetween('created_at', [$this->start_date, $this->end_date])
                    ->groupBy('product_id'),
                'et', 'p.id', '=', 'et.product_id'
            )
            ->groupBy('p.id', 'p.title')
            ->orderBy('p.id')
            ->get();

        // Return view with transactions
        return view('exports.report_product', [
            'transactions' => $transactions,
        ]);
    }
}
