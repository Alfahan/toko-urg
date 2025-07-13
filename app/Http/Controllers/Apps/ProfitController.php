<?php

namespace App\Http\Controllers\Apps;

use App\Exports\ProfitsExport;
use App\Http\Controllers\Controller;
use App\Models\Profit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class ProfitController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Apps/Profits/Index');
    }

    /**
     * filter
     *
     * @param  mixed $request
     * @return void
     */
    public function filter(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // Gunakan query reusable
        $profitQuery = Profit::whereBetween('created_at', [$startDate, $endDate]);

        // Ambil data profits + relasi (eager loading minimal tapi lengkap)
        $profits = $profitQuery->with([
            'transaction.transaction_details',
            'transaction.customer',
            'transaction.cashier'
        ])->get();

        // Clone query untuk sum total profit
        $total = (clone $profitQuery)->sum('total');

        // Hitung discount dari transaksi
        $discount = Transaction::whereBetween('created_at', [$startDate, $endDate])
                                    ->sum('discount');

        return Inertia::render('Apps/Profits/Index', [
            'profits'  => $profits,
            'discount' => (int) $discount,
            'total'    => (int) $total
        ]);
    }

    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        return Excel::download(new ProfitsExport($request->start_date, $request->end_date), 'profits : '.$request->start_date.' — '.$request->end_date.'.xlsx');
    }

    /**
     * pdf
     *
     * @param  mixed $request
     * @return void
     */
    public function pdf(Request $request)
    {
        //get data proftis by range date
        $profits = Profit::with([
                        'transaction' => function($q) {
                            $q->with([
                                'transaction_details',
                                'customer',
                                'cashier',
                            ]);
                        }
                    ])->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total profit by range date
        $total = Profit::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('total');

        $discount = Transaction::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('discount');

        //load view PDF with data
        $pdf = PDF::loadView('exports.profits', compact('profits', 'discount', 'total'));

        //download PDF
        return $pdf->download('profits : '.$request->start_date.' — '.$request->end_date.'.pdf');
    }

}
