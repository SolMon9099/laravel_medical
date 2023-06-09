<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PatientSchedule;
use App\Models\PatientTransactionUploadedFiles;
use App\Models\PatientTransaction;
use App\Service\SmsService;
use App\Service\MailService;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('profile.index');
    }

    public function patient_transaction(){
        $transaction_data = PatientTransaction::with(['files', 'attorney', 'doctor'])->where('patient_id', auth()->user()->id)->get()->all();
        $schedule_data = PatientSchedule::query()->where('patient_id', auth()->user()->id)->get()->all();
        return view('profile.patient_transaction')->with([
            'transaction_data' => $transaction_data,
            'schedule_data' => $schedule_data
        ]);
    }

    public function change_password(){
        return view('profile.change_password');
    }

    public function upload_sign_docs(Request $request)
    {
        if ($request->hasFile('files')) {
            $uploadedFiles = $request->file('files');
            $transaction_id = $request->transaction_id;
            foreach ($uploadedFiles as $file) {
                $patientTransactionUploadedFilesObj = new PatientTransactionUploadedFiles();

                //set the file name
                $fileName = $file->getClientOriginalName();

                //move the file into the desired folder
                $file->move(public_path('uploads'), $fileName);

                // Save the upload result into the database
                $patientTransactionUploadedFilesObj->transaction_id = $transaction_id;
                $patientTransactionUploadedFilesObj->files = $fileName;
                $patientTransactionUploadedFilesObj->save();

                PatientTransaction::query()->where('id', $transaction_id)->update(['status' => config('const.status_code.Signed')]);

                $sms_service = new SmsService();
                $sms_service->sendSignedSMS($transaction_id);

                // $mail_service = new MailService();
                // $mail_service->sendSignedMail($transaction_id);
            }
            return redirect()->back()->with('flash_success', 'Your have uploaded docs successfully.');
        } else {
            return redirect()->back()->with('flash_error', 'Please choose files');
        }


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
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->date_of_birth =date('Y-m-d', strtotime($request->date_of_birth));
        $user->address = $request->address;
        $user->address_line2 = isset($request->address_line2) ? $request->address_line2 : null;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->postal = $request->postal;
        $user->save();
        return redirect()->back()->with('flash_success', 'Your profile has been updated.');
    }

    public function store_password(Request $request)
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
