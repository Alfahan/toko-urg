<?php

namespace App\Http\Controllers\Apps;

use App\Exports\ProductInReportExport;
use App\Exports\ProductOutReportExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Models\Product;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get products
        $products = Product::when(request()->q, function($products) {
            $products = $products->where('title', 'like', '%'. request()->q . '%');
        })->with('unit_of_measurement')->latest()->paginate(10);

        //return inertia
        return Inertia::render('Apps/Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get unit of measurement
        $unitofmeasurements = UnitOfMeasurement::all();

        //return inertia
        return Inertia::render('Apps/Products/Create', [
            'unit_of_measurements' => $unitofmeasurements
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'barcode'               => 'required|unique:products',
            'title'                 => 'required',
            'description'           => 'required',
            'unit_of_measurement_id'=> 'required',
            'buy_price'             => 'required',
            'sell_price'    => 'required',
            'stock'                 => 'required',
        ]);

        //create product
        Product::create([
            'barcode'                   => $request->barcode,
            'title'                     => $request->title,
            'description'               => $request->description,
            'unit_of_measurement_id'    => $request->unit_of_measurement_id,
            'buy_price'                 => $request->buy_price,
            'sell_price'        => $request->sell_price,
            'stock'                     => $request->stock,
            'stock_minimal'             => $request->stock_minimal
        ]);

        //redirect
        return redirect()->route('apps.products.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //get categories
        $unitofmeasurements = UnitOfMeasurement::all();

        return Inertia::render('Apps/Products/Edit', [
            'product' => $product,
            'unit_of_measurements' => $unitofmeasurements
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'barcode'               => 'required|unique:products,barcode,'.$product->id,
            'title'                 => 'required',
            'description'           => 'required',
            'unit_of_measurement_id'=> 'required',
            'buy_price'             => 'required',
            'sell_price'    => 'required',
            'stock'                 => 'required',
        ]);

        //update product
        $product->update([
            'barcode'                   => $request->barcode,
            'title'                     => $request->title,
            'description'               => $request->description,
            'unit_of_measurement_id'    => $request->unit_of_measurement_id,
            'buy_price'                 => $request->buy_price,
            'sell_price'        => $request->sell_price,
            'stock'                     => $request->stock,
            'stock_minimal'             => $request->stock_minimal,
        ]);

        //redirect
        return redirect()->route('apps.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find by ID
        $product = Product::findOrFail($id);

        //delete
        $product->delete();

        //redirect
        return redirect()->route('apps.products.index');
    }

    /**
     * import
     *
     * @return void
     */
    public function import()
    {
        return inertia('Apps/Products/Import');
    }

    /**
     * storeImport
     *
     * @param  mixed $request
     * @return void
     */
    Public function storeImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        Excel::import(new ProductImport(), $request->file('file'));

        return redirect()->route('apps.products.index');
    }

}
