<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerOutExport implements FromView
{
    protected $id;
    protected $start_date;
    protected $end_date;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct($id, $start_date, $end_date)
    {
        $this->id = $id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        // Query transaksi
        $transactions = DB::table('customers as c')
        ->select(
            'c.id as customer_id',
            'c.name as customer_name',
            'p.title as product_name',
            't.price_per_qty',
            DB::raw('SUM(t.qty) as total_quantity'),
            DB::raw('SUM(t.price) as total_price'),
            't.transaction_type',
            't.created_at as transaction_date'
        )
        ->join(DB::raw("(
            SELECT et.customer_id, etd.product_id, etd.price_per_qty, etd.qty, etd.price, et.created_at, 'transactions' AS transaction_type
            FROM transactions et
            JOIN transaction_details etd ON et.id = etd.transaction_id
        ) as t"), 'c.id', '=', 't.customer_id')
        ->join('products as p', 't.product_id', '=', 'p.id')
        ->whereBetween(DB::raw('DATE(t.created_at)'), [$this->start_date, $this->end_date])
        ->where('c.id', $this->id)
        ->groupBy('c.id', 'c.name', 'p.title', 't.price_per_qty', 't.transaction_type', 't.created_at')
        ->orderBy('t.created_at', 'asc')
        ->get();

        // Query total diskon dengan filter tanggal
        $totalDiscount = DB::table(DB::raw("(

        SELECT customer_id, discount FROM transactions
        WHERE DATE(created_at) BETWEEN '$this->start_date' AND '$this->end_date'
        
        ) AS t"))
        ->where('t.customer_id', $this->id)
        ->sum('t.discount');

        // Hitung total price keseluruhan
        $totalPrice = DB::table(DB::raw("(
        
        SELECT etd.price FROM transactions et
        JOIN transaction_details etd ON et.id = etd.transaction_id
        WHERE et.customer_id = $this->id AND DATE(et.created_at) BETWEEN '$this->start_date' AND '$this->end_date'
        ) as prices"))->sum('price');



        return view('exports.report_customer_out', [
            'customer_id'    => $this->id,
            'transactions'   => $transactions,
            'total_price'    => $totalPrice,
            'total_discount' => $totalDiscount,
        ]);
    }
}
