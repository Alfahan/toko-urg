<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get cart
        $carts = Cart::with('product')->where('cashier_id', auth()->user()->id)->latest()->get();

        // get all product
        $products = Product::latest()->get();

        //get all customers
        $customers = Customer::latest()->get();

        return Inertia::render('Apps/Transactions/Index', [
            'products'       => $products,
            'carts'         => $carts,
            'carts_total'   => $carts->sum('price'),
            'customers'     => $customers
        ]);
    }

    /**
     * searchProduct
     *
     * @param  mixed $request
     * @return void
     */
    public function searchProduct(Request $request)
    {
        //find product by barcode
        $product = Product::where('id', $request->barcode)->first();

        if($product) {
            return response()->json([
                'success' => true,
                'data'    => $product
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => null
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
        //check stock product
        if(Product::whereId($request->product_id)->first()->stock < $request->qty) {

            //redirect
            return redirect()->back()->with('error', 'Out of Stock Product!.');
        }

        //check cart
        $cart = Cart::with('product')
                ->where('product_id', $request->product_id)
                ->where('cashier_id', auth()->user()->id)
                ->first();

        if($cart) {

            //increment qty
            $cart->increment('qty', $request->qty);

            //sum price * quantity
            $cart->price  = $cart->product->sell_price * $cart->qty;

            $cart->save();

        } else {

            //insert cart
            Cart::create([
                'cashier_id'    => auth()->user()->id,
                'product_id'    => $request->product_id,
                'price_per_qty' => $request->sell_price,
                'qty'           => $request->qty,
                'price'         => $request->sell_price * $request->qty,
            ]);

        }

        return redirect()->route('apps.transactions.index')->with('success', 'Product Added Successfully!.');
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
        $cart = Cart::with('product')
            ->whereId($request->cart_id)
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
            /**
            * algorithm generate no invoice
            */
            $length = 10;
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
            }

            //generate no invoice
            $invoice = 'TRX-'.Str::upper($random);

            //insert transaction
            $transaction = Transaction::create([
                'cashier_id'    => auth()->user()->id,
                'customer_id'   => $request->customer_id,
                'invoice'       => $invoice,
                'cash'          => $request->cash,
                'change'        => $request->change,
                'discount'      => $request->discount,
                'grand_total'   => $request->grand_total,
            ]);

            //get carts
            $carts = Cart::where('cashier_id', auth()->user()->id)->get();

            //insert transaction detail
            foreach($carts as $cart) {

                //insert transaction detail
                $transaction->transaction_details()->create([
                    'transaction_id'    => $transaction->id,
                    'product_id'                => $cart->product_id,
                    'price_per_qty'             => $cart->price_per_qty,
                    'qty'                       => $cart->qty,
                    'price'                     => $cart->price,
                ]);

                //get price
                $total_buy_price  = $cart->product->buy_price * $cart->qty;
                $total_sell_price = $cart->product->sell_price * $cart->qty;

                //get profits
                $profits = $total_sell_price - $total_buy_price;

                //insert provits
                $transaction->profits()->create([
                    'transaction_id'    => $transaction->id,
                    'total'                     => $profits,
                ]);

                //update stock product
                $product = Product::find($cart->product_id);
                $product->stock = $product->stock - $cart->qty;
                $product->save();

            }

            //delete carts
            Cart::where('cashier_id', auth()->user()->id)->delete();

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
        $transaction = Transaction::with(['transaction_details.product', 'cashier', 'customer'])->where('invoice', $request->invoice)->firstOrFail();

        //return view
        return view('print.nota_transaction', compact('transaction'));
    }


}
