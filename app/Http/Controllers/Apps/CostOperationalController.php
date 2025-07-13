<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\CostCart;
use App\Models\CostTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CostOperationalController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get cart
        $carts = CostCart::where('admin_id', auth()->user()->id)->latest()->get();

        return Inertia::render('Apps/CostTransactions/Index', [
            'carts'         => $carts,
            'carts_total'   => $carts->sum('price')
        ]);
    }

    /**
     * addToCart
     *
     * @param  mixed $request
     * @return void
     */
    public function addToCart(Request $request)
    {

        //insert cart
        CostCart::create([
            'admin_id'      => auth()->user()->id,
            'name_cost'     => $request->name_cost,
            'price_per_qty' => $request->price_per_qty,
            'qty'           => $request->qty,
            'price'         => $request->price_per_qty * $request->qty,
        ]);

        return redirect()->route('apps.cost_transactions.index')->with('success', 'Product Added Successfully!.');
    }


    /**
     * destroyCart
     *
     * @param  mixed $request
     * @return void
     */
    public function destroyCart(Request $request)
    {
        //find cart by ID
        $cart = CostCart::whereId($request->cart_id)
            ->first();

        //delete cart
        $cart->delete();

        return redirect()->back()->with('success', 'Product Removed Successfully!.');
    }

        /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            /**
            * algorithm generate no invoice
            */
            $length = 10;
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
            }

            //generate no invoice
            $invoice = 'COST-TRX-'.Str::upper($random);

            //insert transaction
            $transaction = CostTransaction::create([
                'admin_id'    => auth()->user()->id,
                'invoice'       => $invoice,
                'grand_total'   => $request->grand_total,
            ]);

            //get carts
            $carts = CostCart::where('admin_id', auth()->user()->id)->get();

            //insert transaction detail
            foreach($carts as $cart) {

                //insert transaction detail
                $transaction->cost_transaction_details()->create([
                    'cost_transaction_id'       => $transaction->id,
                    'name_cost'                 => $cart->name_cost,
                    'price_per_qty'             => $cart->price_per_qty,
                    'qty'                       => $cart->qty,
                    'price'                     => $cart->price,
                ]);

            }

            //delete carts
            CostCart::where('admin_id', auth()->user()->id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'data'    => $transaction
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaction failed',
                'error'   => $e->getMessage()
            ], 500);
        }

    }


    /**
     * print
     *
     * @param  mixed $request
     * @return void
     */
    public function print(Request $request)
    {
        //get transaction
        $transaction = CostTransaction::with(['cost_transaction_details', 'admin'])->where('invoice', $request->invoice)->firstOrFail();

        //return view
        return view('print.nota_cost', compact('transaction'));
    }

}
