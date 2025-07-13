<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitOfMeasurementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get Unit Of Measurement
        $unitOfMeasurements = UnitOfMeasurement::when(request()->q, function($unitOfMeasurements) {
            $unitOfMeasurements = $unitOfMeasurements->where('name', 'like', '%'.  request()->q .'%');
        })->latest()->paginate(10);

        //return inertia
        return Inertia::render('Apps/UnitOfMeasurement/Index', [
            'unit_of_measurements' => $unitOfMeasurements,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Apps/UnitOfMeasurement/Create');
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
            'name'          => 'required',
        ]);

        //create category
        UnitOfMeasurement::create([
            'name'          => $request->name,
            'description'   => $request->description
        ]);

        //redirect
        return redirect()->route('apps.unit_of_measurement.index');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UnitOfMeasurement $unitOfMeasurement)
    {
        return Inertia::render('Apps/UnitOfMeasurement/Edit', [
            'unit_of_measurement' => $unitOfMeasurement,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnitOfMeasurement $unitOfMeasurement)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'name'          => 'required',
            'description'   => 'required'
        ]);


        //update category without image
        $unitOfMeasurement->update([
            'name'          => $request->name,
            'description'   => $request->description
        ]);

        //redirect
        return redirect()->route('apps.unit_of_measurement.index');
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
        $unitOfMeasurement = UnitOfMeasurement::findOrFail($id);

        //delete
        $unitOfMeasurement->delete();

        //redirect
        return redirect()->route('apps.unit_of_measurement.index');
    }
}
