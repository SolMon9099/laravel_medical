<?php

namespace App\Service;
use Exception;
use App\Models\PatientTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignedAlertEmail;
use App\Mail\ResultAlertEmail;

class MailService{

    public function __construct(){
    }

    public function sendSignedMail($transaction_id, $filename)
    {
        $transaction_record = PatientTransaction::find($transaction_id);
        if ($transaction_record){
            $patient_user = User::find($transaction_record->patient_id);
            $mailData = [
                'name' => $patient_user->name,
                'email' => $patient_user->email,
                'phone' => $patient_user->phone,
                'filename' => $filename
            ];

            $offic_user = User::find($transaction_record->office_id);
            $attorney_user = User::find($transaction_record->attorney_id);
            $doctor_user = User::find($transaction_record->doctor_id);
            try{
                if ($offic_user && $offic_user->email){
                    Mail::to($offic_user->email)->send(new SignedAlertEmail($mailData));
                }
                if ($attorney_user && $attorney_user->email){
                    Mail::to($attorney_user->email)->send(new SignedAlertEmail($mailData));
                }
                if ($doctor_user && $doctor_user->email){
                    Mail::to($doctor_user->email)->send(new SignedAlertEmail($mailData));
                }
            } catch (Exception $e) {
                // Error occurred
                var_dump($e->getMessage());exit;
                // return response()->json(['error' => $e->getMessage()], 500);
            }
            return true;
        }
    }

    public function sendResultMail($transaction_id, $filename)
    {
        $transaction_record = PatientTransaction::find($transaction_id);
        if ($transaction_record){
            $patient_user = User::find($transaction_record->patient_id);
            $mailData = [
                'name' => $patient_user->name,
                'email' => $patient_user->email,
                'phone' => $patient_user->phone,
                'filename' => $filename
            ];

            $offic_user = User::find($transaction_record->office_id);
            $attorney_user = User::find($transaction_record->attorney_id);
            $doctor_user = User::find($transaction_record->doctor_id);
            try{
                if ($patient_user && $patient_user->email){
                    Mail::to($patient_user->email)->send(new ResultAlertEmail($mailData));
                }
                if ($offic_user && $offic_user->email){
                    Mail::to($offic_user->email)->send(new ResultAlertEmail($mailData));
                }
                if ($attorney_user && $attorney_user->email){
                    Mail::to($attorney_user->email)->send(new ResultAlertEmail($mailData));
                }
                if ($doctor_user && $doctor_user->email){
                    Mail::to($doctor_user->email)->send(new ResultAlertEmail($mailData));
                }
            } catch (Exception $e) {
                // Error occurred
                var_dump($e->getMessage());exit;
                // return response()->json(['error' => $e->getMessage()], 500);
            }
            return true;
        }
    }
}
?>
