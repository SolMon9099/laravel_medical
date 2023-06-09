<?php

namespace App\Service;
use Twilio\Rest\Client;
use Exception;
use App\Models\PatientTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailService{

    public function __construct(){
    }

    public function sendSignedMail($transaction_id)
    {
        $transaction_record = PatientTransaction::find($transaction_id);
        if ($transaction_record){

            $offic_user = User::find($transaction_record->office_id);
            $attorney_user = User::find($transaction_record->attorney_id);
            $doctor_user = User::find($transaction_record->doctor_id);
            try{
                if ($offic_user && $offic_user->phone){

                }
                if ($attorney_user && $attorney_user->phone){

                }
                if ($doctor_user && $doctor_user->phone){

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
