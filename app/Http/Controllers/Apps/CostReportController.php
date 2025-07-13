<?php

namespace App\Http\Controllers\Apps;

use App\Exports\CostReportExport;
use App\Http\Controllers\Controller;
use App\Models\CostTransaction;
use App\Models\CostTransactionDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CostReportController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Apps/CostReports/Index');
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

        //get data cost by range date
        $cost = CostTransactionDetail::with([
            'cost_transaction' => function($q) {
                $q->with(['admin']);
            }
        ])
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->get();

        //get total cost by range date
        $total = CostTransaction::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->sum('grand_total');

        return Inertia::render('Apps/CostReports/Index', [
            'cost'          => $cost,
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


        //get data cost by range date
        $cost = CostTransactionDetail::with([
            'cost_transaction' => function($q) {
                $q->with(['admin']);
            }
        ])
        ->when($request->has('invoice'), function($q) use ($request) {
            $q->whereHas('cost_transaction', function($q) use ($request){
                $q->where('invoice', '=', $request->invoice);
            });
        })
        ->get();

        //get total sales by range date
        $total = CostTransaction::where('invoice', '=', $request->invoice)
            ->sum('grand_total');

        return Inertia::render('Apps/CostReports/Index', [
            'cost'          => $cost,
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
        return Excel::download(new CostReportExport($request->start_date, $request->end_date), 'reports_cost : '.$request->start_date.' — '.$request->end_date.'.xlsx');
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
        $cost = CostTransactionDetail::with([
                    'cost_transaction' => function($q) {
                        $q->with(['admin']);
                    }
                ])
                ->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total purchase by range daate
        $total = CostTransaction::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('grand_total');

        //load view PDF with data
        $pdf = PDF::loadView('exports.reports_cost', compact('cost', 'total'));

        //return PDF for preview / download
        return $pdf->download('reports_cost : '.$request->start_date.' — '.$request->end_date.'.pdf');
    }


    /**
     * detail
     *
     * @param  mixed $request
     * @return void
     */
    public function detail_invoice($detail_invoice)
    {
        $transaction = CostTransaction::with(['cost_transaction_details', 'admin'])->where('invoice', $detail_invoice)->firstOrFail();

        return Inertia::render('Apps/CostReports/Detail', [
            'transaction'         => $transaction,
        ]);
    }
}
