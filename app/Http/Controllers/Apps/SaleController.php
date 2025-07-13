<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesExport;

class SaleController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {

        return Inertia::render('Apps/Sales/Index');
    }

    /**
     * filter
     *
     * @param  mixed $request
     * @return void
     */
    public function filter(Request $request)
    {

        if($request->start_date != null && $request->end_date != null){
            //get data sales by range date
            $sales = Transaction::with('cashier', 'customer')
                ->whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date)
                // ->orWhere('invoice', '=', $request->invoice)
                ->get();

            //get total sales by range date
            $total = Transaction::whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date)
                // ->orWhere('invoice', '=', $request->invoice)
                ->sum('grand_total');
        }

        if($request->invoice != null){
            //get data sales by range date
            $sales = Transaction::with('cashier', 'customer')
                ->where('invoice', '=', $request->invoice)
                ->get();

            //get total sales by range date
            $total = Transaction::where('invoice', '=', $request->invoice)
                ->sum('grand_total');
        }

        return Inertia::render('Apps/Sales/Index', [
            'sales'    => $sales,
            'total'    => (int) $total
        ]);
    }

    // /**
    //  * search
    //  *
    //  * @param  mixed $request
    //  * @return void
    //  */
    // public function search(Request $request)
    // {
    //     $this->validate($request, [
    //         'invoice'  => 'required',
    //     ]);

    //     //get data sales by range date
    //     $sales = Transaction::with('cashier', 'customer')
    //         ->where('invoice', '=', $request->invoice)
    //         ->get();

    //     //get total sales by range date
    //     $total = Transaction::where('invoice', '=', $request->invoice)
    //         ->sum('grand_total');

    //     return Inertia::render('Apps/Sales/Index', [
    //         'sales'    => $sales,
    //         'total'    => (int) $total
    //     ]);
    // }

    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {

        $result = Excel::download(new SalesExport($request->invoice, $request->start_date, $request->end_date), 'sales : '.$request->start_date.' — '.$request->end_date.'.xlsx');

        return $result;
    }

    /**
     * pdf
     *
     * @param  mixed $request
     * @return void
     */
    public function pdf(Request $request)
    {
        if($request->invoice != null){
            $sales = Transaction::with('cashier', 'customer')->where('invoice', '=', $request->invoice)->get();

            $total = Transaction::where('invoice', '=', $request->invoice)->sum('grand_total');
        }

        if($request->start_date != null && $request->end_date != null){
            //get sales by range date
            $sales = Transaction::with('cashier', 'customer')->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

            //get total sales by range daate
            $total = Transaction::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('grand_total');
        }

        //load view PDF with data
        $pdf = PDF::loadView('exports.sales', compact('sales', 'total'));

        //return PDF for preview / download
        return $pdf->download('sales : '.$request->start_date.' — '.$request->end_date.'.pdf');
    }

    /**
     * detail
     *
     * @param  mixed $request
     * @return void
     */
    public function detail_invoice($detail_invoice)
    {
        $transaction = Transaction::with(['transaction_details.product', 'cashier', 'customer'])->where('invoice', $detail_invoice)->firstOrFail();

        return Inertia::render('Apps/Sales/Detail', [
            'transaction'         => $transaction,
        ]);
    }

}
