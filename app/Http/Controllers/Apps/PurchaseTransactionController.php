<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseCart;
use App\Models\PurchaseTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PurchaseTransactionController extends Controller
{
    //
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get cart
        $carts = PurchaseCart::with('product')->where('admin_id', auth()->user()->id)->latest()->get();

        // get all product
        $products = Product::latest()->get();

        //get all customers
        $suppliers = Supplier::latest()->get();

        return Inertia::render('Apps/PurchaseTransactions/Index', [
            'products'      => $products,
            'carts'         => $carts,
            'carts_total'   => $carts->sum('price'),
            'suppliers'     => $suppliers
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
        //check cart
        $cart = PurchaseCart::with('product')
                ->where('product_id', $request->product_id)
                ->where('admin_id', auth()->user()->id)
                ->first();

        if($cart) {

            //increment qty
            $cart->increment('qty', $request->qty);

            //sum price * quantity
            $cart->price  = $cart->product->buy_price * $cart->qty;

            $cart->save();

        } else {

            //insert cart
            PurchaseCart::create([
                'admin_id'      => auth()->user()->id,
                'product_id'    => $request->product_id,
                'price_per_qty' => $request->buy_price,
                'qty'           => $request->qty,
                'price'         => $request->buy_price * $request->qty,
            ]);

        }

        return redirect()->route('apps.purchase_transactions.index')->with('success', 'Product Added Successfully!.');
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
        $cart = PurchaseCart::with('product')
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
            $invoice = 'PO-TRX-'.Str::upper($random);

            //insert transaction
            $transaction = PurchaseTransaction::create([
                'admin_id'      => auth()->user()->id,
                'supplier_id'   => $request->supplier_id,
                'invoice'       => $invoice,
                'cash'          => $request->cash,
                'change'        => $request->change,
                'grand_total'   => $request->grand_total,
            ]);

            //get carts
            $carts = PurchaseCart::where('admin_id', auth()->user()->id)->get();

            //insert transaction detail
            foreach($carts as $cart) {

                //insert transaction detail
                $transaction->purchase_transaction_details()->create([
                    'purchase_transaction_id'    => $transaction->id,
                    'product_id'                => $cart->product_id,
                    'price_per_qty'             => $cart->price_per_qty,
                    'qty'                       => $cart->qty,
                    'price'                     => $cart->price,
                ]);

                //update stock product
                $product = Product::find($cart->product_id);
                $product->stock = $cart->qty + $product->stock;
                $product->save();

            }

            //delete carts
            PurchaseCart::where('admin_id', auth()->user()->id)->delete();

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
        $transaction = PurchaseTransaction::with(['purchase_transaction_details.product', 'admin', 'supplier'])->where('invoice', $request->invoice)->firstOrFail();

        //return view
        return view('print.nota_purchase', compact('transaction'));
    }
}
