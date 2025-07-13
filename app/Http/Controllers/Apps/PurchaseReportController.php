<?php

namespace App\Http\Controllers\Apps;

use App\Exports\PurchaseReportExport;
use App\Http\Controllers\Controller;
use App\Models\PurchaseTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;


class PurchaseReportController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Apps/PurchaseReports/Index');
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
            'start_date'  => 'required',
            'end_date'    => 'required',
        ]);

        //get data purchase by range date
        $purchases = PurchaseTransaction::with('admin', 'supplier')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->get();

        //get total purchase by range date
        $total = PurchaseTransaction::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->sum('grand_total');

        return Inertia::render('Apps/PurchaseReports/Index', [
            'purchases'      => $purchases,
            'total'         => (int) $total
        ]);
    }

    /**
     * search
     *
     * @param  mixed $request
     * @return void
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'invoice'  => 'required',
        ]);

        //get data sales by range date
        $purchases = PurchaseTransaction::with('admin', 'supplier')
            ->where('invoice', '=', $request->invoice)
            ->get();

        //get total sales by range date
        $total = PurchaseTransaction::where('invoice', '=', $request->invoice)
            ->sum('grand_total');

        return Inertia::render('Apps/PurchaseReports/Index', [
            'purchases'     => $purchases,
            'total'         => (int) $total
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
        return Excel::download(new PurchaseReportExport($request->start_date, $request->end_date), 'reports_purchase : '.$request->start_date.' — '.$request->end_date.'.xlsx');
    }

    /**
     * pdf
     *
     * @param  mixed $request
     * @return void
     */
    public function pdf(Request $request)
    {
        //get purchase by range date
        $purchases = PurchaseTransaction::with('admin', 'supplier')->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total purchase by range daate
        $total = PurchaseTransaction::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('grand_total');

        //load view PDF with data
        $pdf = PDF::loadView('exports.reports_purchase', compact('purchases', 'total'));

        //return PDF for preview / download
        return $pdf->download('reports_purchase : '.$request->start_date.' — '.$request->end_date.'.pdf');
    }


    /**
     * detail
     *
     * @param  mixed $request
     * @return void
     */
    public function detail_invoice($detail_invoice)
    {
        $transaction = PurchaseTransaction::with(['purchase_transaction_details.product', 'admin', 'supplier'])->where('invoice', $detail_invoice)->firstOrFail();

        return Inertia::render('Apps/PurchaseReports/Detail', [
            'transaction'         => $transaction,
        ]);
    }
}
