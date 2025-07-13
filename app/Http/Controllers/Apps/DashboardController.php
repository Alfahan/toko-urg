<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\CostTransaction;
use App\Models\Profit;
use App\Models\Transaction;
use App\Models\DirectProfit;
use App\Models\DirectTransaction;
use App\Models\KreditProfit;
use App\Models\KreditTransaction;
use App\Models\MandiriProfit;
use App\Models\MandiriTransaction;
use App\Models\Product;
use App\Models\KiostaniProfit;
use App\Models\KiostaniTransaction;
use App\Models\KreditPjlProfit;
use App\Models\KreditPjlTransaction;
use App\Models\PjlProfit;
use App\Models\PjlTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // day
        $day    = Carbon::today();

        // week
        $week = Carbon::now()->subDays(7);

        // month
        $month = Carbon::now()->month;

        // year
        $year = Carbon::now()->year;

        //
        // chart sales 7 days
        $chart_sales_week = DB::table('transactions')
            ->addSelect(DB::raw('DATE(created_at) as date, SUM(grand_total) as grand_total'))
            ->where('created_at', '>=', $week)
            ->groupBy('date')
            ->get();

        if (count($chart_sales_week)) {
            foreach($chart_sales_week as $result) {
                $sales_date[]   = $result->date;
                $grand_total[]  = (int)$result->grand_total;
            }
        } else {
            $sales_date[]   = "";
            $grand_total[]  = "";
        }

        // count sales today
        $count_sales_today = Transaction::whereDate('created_at', '=',  $day)->count();

        // count sales month
        $count_sales_month = Transaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();

        // sum sales today
        $sum_sales_today = Transaction::whereDate('created_at', '=', $day)->sum('grand_total');

        // sum sales month
        $sum_sales_month = Transaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('grand_total');

        // sum discount today
        $sum_sales_discount_today = Transaction::whereDate('created_at', '=', $day)->sum('discount');

        // sum discount month
        $sum_sales_discount_month = Transaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('discount');

        // sum profits today
        $sum_profits_today = Profit::whereDate('created_at', '=', $day)->sum('total');

        // sum profits month
        $sum_profits_month = Profit::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('total');

        // sum profits month fix
        $sum_profits_month_fix = $sum_profits_month - $sum_sales_discount_month;

        // chart best selling product
        $chart_best_products = DB::table('transaction_details')
            ->addSelect(DB::raw('products.title as title, SUM(transaction_details.qty) as total'))
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->groupBy('transaction_details.product_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get();

        if (count($chart_best_products)) {
            foreach ($chart_best_products as $data) {
                $product[] = $data->title;
                $total[]   = (int)$data->total;
            }
        } else {
            $product[]  = "";
            $total[]    = "";
        }

        // get product limit stock
        $products_limit_stock = Product::whereColumn('stock', '<=', 'stock_minimal')
            ->with('unit_of_measurement')
            ->get();

        $assets = null;

        $products = Product::get();

        foreach($products as $product){
            $asset = $product->buy_price * $product->stock;
            $assets += $asset;
        }

        // sum sales all transaction month
        $sales_all_transaction_month    = $sum_sales_month;

        // sum profit transaction month
        $profit_all_transaction_month   = $sum_profits_month_fix;

        // sum cost transaction month
        $cost_month = CostTransaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('grand_total');

        // sum profit net month
        $profit_net_month = $profit_all_transaction_month - $cost_month;


        return Inertia::render('Apps/Dashboard/Index', [
            //
            'sales_date'            => $sales_date,
            'grand_total'           => $grand_total,
            'count_sales_today'     => (int) $count_sales_today,
            'count_sales_month'     => (int) $count_sales_month,
            'sum_sales_today'       => (int) $sum_sales_today,
            'sum_sales_month'       => (int) $sum_sales_month,
            'sum_profits_today'     => (int) $sum_profits_today - $sum_sales_discount_today,
            'sum_profits_month'     => (int) $sum_profits_month_fix,
            'product'               => $product,
            'total'                 => $total,

            'products_limit_stock'          => $products_limit_stock,
            'sales_all_transaction_month'   => (int) $sales_all_transaction_month,
            'profit_all_transaction_month'  => (int) $profit_all_transaction_month,
            'cost_month'                    => (int) $cost_month,
            'profit_net_month'              => (int) $profit_net_month,
            'assets'                        => $assets
        ]);



    }
}
