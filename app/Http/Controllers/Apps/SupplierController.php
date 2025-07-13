<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get suppliers
        $suppliers = Supplier::when(request()->q, function($suppliers) {
            $suppliers = $suppliers->where('name_company', 'like', '%'. request()->q . '%');
        })->latest()->paginate(10);

        //return inertia
        return Inertia::render('Apps/Suppliers/Index', [
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Apps/Suppliers/Create');
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
            'name_company'      => 'required',
            'no_telp'           => 'required|unique:suppliers',
            'address'           => 'required',
        ]);

        //create customer
        Supplier::create([
            'name_company'      => $request->name_company,
            'no_telp'           => $request->no_telp,
            'address'           => $request->address,
        ]);

        //redirect
        return redirect()->route('apps.suppliers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return Inertia::render('Apps/Suppliers/Edit', [
            'supplier' => $supplier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'name_company'      => 'required',
            'no_telp'           => 'required|unique:suppliers',
            'address'           => 'required',
        ]);

        //update customer
        $supplier->update([
            'name_company'      => $request->name_company,
            'no_telp'           => $request->no_telp,
            'address'           => $request->address,
        ]);

        //redirect
        return redirect()->route('apps.suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find customer by ID
        $supplier = Supplier::findOrFail($id);

        //delete customer
        $supplier->delete();

        //redirect
        return redirect()->route('apps.suppliers.index');
    }

}
