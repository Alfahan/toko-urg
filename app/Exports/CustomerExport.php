<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    protected $start_date;
    protected $end_date;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct($start_date, $end_date)
    {
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
        // Ambil data transaksi untuk customer dengan filter tanggal di setiap transaksi
        $transactions = DB::select(
            DB::raw('
                SELECT
                    c.id AS customer_id,
                    c.name AS customer_name,
                    (SELECT COALESCE(SUM(grand_total), 0)
                        FROM transactions et
                        WHERE et.customer_id = c.id
                        AND et.created_at BETWEEN ? AND ?) AS total_transactions,
                    (
                        (SELECT COALESCE(SUM(grand_total), 0)
                            FROM transactions et
                            WHERE et.customer_id = c.id
                            AND et.created_at BETWEEN ? AND ?)
                    ) AS all_transactions
                FROM
                    customers c
                ORDER BY
                    all_transactions DESC;
            '),
            [
                // Parameter untuk setiap subquery transaksi
                $this->start_date, $this->end_date, // transactions
                
                // Parameter untuk total transaksi
                $this->start_date, $this->end_date, // transactions
            ]
        );

        return view('exports.report_customer', [
            'transactions' => $transactions,
        ]);
    }
}
