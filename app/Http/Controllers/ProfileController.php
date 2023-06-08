<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PatientSchedule;
use App\Models\PatientTransaction;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaction_data = PatientTransaction::with(['files', 'attorney', 'doctor'])->where('patient_id', auth()->user()->id)->get()->all();
        $schedule_data = PatientSchedule::query()->where('patient_id', auth()->user()->id)->get()->all();
        return view('profile.index')->with([
            'transaction_data' => $transaction_data,
            'schedule_data' => $schedule_data
        ]);
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
         // Verify the old password
        $user = Auth::user();
        $isPasswordValid = Hash::check($request->current_password, $user->password);

        if (!$isPasswordValid) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is invalid.']);
        }

        // Hash the new password
        $hashedPassword = Hash::make($request->new_password);

        // Update the user's password
        $user = Auth::user();
        $user->password = $hashedPassword;
        $user->save();

        // Redirect the user to the dashboard or profile page
        return redirect()->back()->with('flash_success', 'Your password has been updated.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
