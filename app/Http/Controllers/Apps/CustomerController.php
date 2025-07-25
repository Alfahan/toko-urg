<?php

namespace App\Http\Controllers\Apps;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get customers
        $customers = Customer::when(request()->q, function($customers) {
            $customers = $customers->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(10);

        //return inertia
        return Inertia::render('Apps/Customers/Index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Apps/Customers/Create');
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
            'name'              => 'required',
            'no_telp'           => 'required|unique:customers',
            'address'           => 'required',
        ]);

        //create customer
        Customer::create([
            'name'              => $request->name,
            'no_telp'           => $request->no_telp,
            'address'           => $request->address,
        ]);

        //redirect
        return redirect()->route('apps.customers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('Apps/Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'name'              => 'required',
            'no_telp'           => 'required',
            'address'           => 'required',
        ]);

        //update customer
        $customer->update([
            'name'              => $request->name,
            'no_telp'           => $request->no_telp,
            'address'           => $request->address,
        ]);

        //redirect
        return redirect()->route('apps.customers.index');
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
        $customer = Customer::findOrFail($id);

        //delete customer
        $customer->delete();

        //redirect
        return redirect()->route('apps.customers.index');
    }

    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        return Excel::download(new CustomersExport(), 'reports_customer.xlsx');
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
        $customers = Customer::get();

        //load view PDF with data
        $pdf = PDF::loadView('exports.reports_customer', compact('customers'));

        //return PDF for preview / download
        return $pdf->download('reports_customer.pdf');
    }

}
