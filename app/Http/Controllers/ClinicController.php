<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;

class ClinicController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:clinic-list|clinic-create|clinic-edit|clinic-delete', ['only' => ['index','store']]);
        $this->middleware('permission:clinic-create', ['only' => ['create','store']]);
        $this->middleware('permission:clinic-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:clinic-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Clinic::get();
        $technicians = User::whereHas(
            'roles', function($q){
                $q->where('name', 'technician');
            }
        )->get()->all();
        return view('clinic.index', compact('data', 'technicians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'clinic_adderss' => 'required',
            'clinic_city' => 'required',
            'clinic_state' => 'required',
            'clinic_postal' => 'required'
        ]);

        $input = $request->all();

        $user = Clinic::create($input);

        return redirect()->route('clinics.index')
                        ->with('flash_success','Clinic created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData  = $this->validate($request, [
            'name' => 'required',
            'clinic_adderss' => 'required',
            'clinic_adderss_line2' => '',
            'clinic_city' => 'required',
            'clinic_state' => 'required',
            'clinic_postal' => 'required',
            'technician_id' => '',
        ]);

        // Find the resource to be updated
        $data = Clinic::findorFail($id);

        // Update the resource with the validated data
        $data->fill($validatedData);
        $data->save();

        return redirect()->route('clinics.index')
                        ->with('flash_success','Clinic updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Clinic::find($id)->delete();
        return redirect()->route('clinics.index')
        ->with('flash_success','Clinic deleted successfully');
    }

    /*
    *  When Edit button is clicked, get the Clinic data of modal
    */
    public function getClinicData(Request $request)
    {
        $itemId = $request->input('itemId');

        // Retrieve the item data based on the ID
        $item = Clinic::find($itemId);

        return response()->json($item);

    }
}
