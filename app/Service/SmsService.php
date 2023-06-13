<?php

namespace App\Service;
use Twilio\Rest\Client;
use Exception;
use App\Models\PatientTransaction;
use App\Models\User;

class SmsService{
    private $account_sid  = '';
    private $auth_token  = '';
    private $twilio_number   = '';
    public function __construct(){
        $this->account_sid = env('TWILIO_ACCOUNT_SID', '');
        $this->auth_token = env('TWILIO_AUTH_TOKEN', '');
        $this->twilio_number = env('TWILIO_PHONE_NUMBER', '');
    }

    public function sendSMS($message, $recipients){
        try{
            $client = new Client($this->account_sid, $this->auth_token);
            $client->messages->create(
                $recipients,
                ['from' => $this->twilio_number, 'body' => $message]
            );
            return true;
            // return response()->json(['message' => 'SMS sent successfully']);
        } catch (Exception $e) {
            // Error occurred
            return $e->getMessage();
            // return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function makePatientMessage($user, $schedule_data, $type = 'notify'){
        $message = '';
        if (isset($user) && isset($user->name)){
            $message .= "Hi, ". $user->name. "\n";
        }
        switch($type){
            case 'notify':
                $message .= "You have a schedule soon.\n";
                if (isset($schedule_data['start_date']) && isset($schedule_data['end_date'])){
                    $message .= "From ".date('m/d/Y H:i', strtotime($schedule_data['start_date'])). "\nTo ".date('m/d/Y H:i', strtotime($schedule_data['end_date']));
                }
                break;
            case 'add':
                $message .= "Your new schedule is added.\n";
                if (isset($schedule_data['start_date']) && isset($schedule_data['end_date'])){
                    $message .= "From ".date('m/d/Y H:i', strtotime($schedule_data['start_date'])). "\nTo ".date('m/d/Y H:i', strtotime($schedule_data['end_date']));
                }
                break;
            case 'edit':
                $message .= "Your schedule is updated.\n";
                if (isset($schedule_data['start_date']) && isset($schedule_data['end_date'])){
                    $message .= "From ".date('m/d/Y H:i', strtotime($schedule_data['start_date'])). "\nTo ".date('m/d/Y H:i', strtotime($schedule_data['end_date']));
                }
                break;
            case 'delete':
                $message .= "Your schedule is deleted.\n";
                if (isset($schedule_data['start_date']) && isset($schedule_data['end_date'])){
                    $message .= "From ".date('m/d/Y H:i', strtotime($schedule_data['start_date'])). "\nTo ".date('m/d/Y H:i', strtotime($schedule_data['end_date']));
                }
                break;
        }
        return $message;
    }

    public function sendSignedSMS($transaction_id)
    {
        $transaction_record = PatientTransaction::find($transaction_id);
        if ($transaction_record){
            $message = "Medical Lien Form with sign is uploaded\n";
            $patient_user = User::find($transaction_record->patient_id);
            $message .="Patient Name : ". $patient_user->name ."\n";
            $message .="Patient Email : ". $patient_user->email ."\n";
            $message .="Patient Phone : ". $patient_user->phone ."\n";

            $offic_user = User::find($transaction_record->office_id);
            $attorney_user = User::find($transaction_record->attorney_id);
            $doctor_user = User::find($transaction_record->doctor_id);
            try{
                if ($offic_user && $offic_user->phone){
                    $res = $this->sendSMS($message, $offic_user->phone);
                    if ($res !== true){
                        var_dump($res);
                    }
                }
                if ($attorney_user && $attorney_user->phone){
                    $res = $this->sendSMS($message, $attorney_user->phone);
                    if ($res !== true){
                        var_dump($res);
                    }
                }
                if ($doctor_user && $doctor_user->phone){
                    $res = $this->sendSMS($message, $doctor_user->phone);
                    if ($res !== true){
                        var_dump($res);
                    }
                }
            } catch (Exception $e) {
                // Error occurred
                var_dump($e->getMessage());
                // return response()->json(['error' => $e->getMessage()], 500);
            }
            return true;
        }
    }

    public function sendResultSMS($transaction_id, $fileName)
    {
        $transaction_record = PatientTransaction::find($transaction_id);
        if ($transaction_record){
            $message = "Patient Result is is uploaded\n";
            $patient_user = User::find($transaction_record->patient_id);
            $message .="Patient Name : ". $patient_user->name ."\n";
            $message .="Patient Email : ". $patient_user->email ."\n";
            $message .="Patient Phone : ". $patient_user->phone ."\n";
            $message .="Result : ".public_path('uploads/results/'.$fileName);

            $offic_user = User::find($transaction_record->office_id);
            $attorney_user = User::find($transaction_record->attorney_id);
            $doctor_user = User::find($transaction_record->doctor_id);
            try{
                if ($patient_user && $patient_user->phone){
                    $res = $this->sendSMS($message, $patient_user->phone);
                    if ($res !== true){
                        var_dump($res);
                    }
                }
                if ($offic_user && $offic_user->phone){
                    $res = $this->sendSMS($message, $offic_user->phone);
                    if ($res !== true){
                        var_dump($res);
                    }
                }
                if ($attorney_user && $attorney_user->phone){
                    $res = $this->sendSMS($message, $attorney_user->phone);
                    if ($res !== true){
                        var_dump($res);
                    }
                }
                if ($doctor_user && $doctor_user->phone){
                    $res = $this->sendSMS($message, $doctor_user->phone);
                    if ($res !== true){
                        var_dump($res);
                    }
                }
            } catch (Exception $e) {
                // Error occurred
                var_dump($e->getMessage());
                // return response()->json(['error' => $e->getMessage()], 500);
            }
            return true;
        }
    }
}
?>
