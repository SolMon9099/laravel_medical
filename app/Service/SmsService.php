<?php

namespace App\Service;
use Twilio\Rest\Client;
use Exception;
class SmsService{
    private $account_sid  = 'AC239d97aee1e786d1bccbb8082cff2f9b';
    private $auth_token  = '5d3ccd2a345d21fd9eafcc05233c90bd';
    private $twilio_number   = '+18667166761';
    public function __construct(){
        $this->account_sid = env('TWILIO_ACCOUNT_SID', 'AC239d97aee1e786d1bccbb8082cff2f9b');
        $this->auth_token = env('TWILIO_AUTH_TOKEN', '5d3ccd2a345d21fd9eafcc05233c90bd');
        $this->twilio_number = env('TWILIO_PHONE_NUMBER', '+18667166761');
    }

    public function sendSMS($message, $recipients){
        try{
            $client = new Client($this->account_sid, $this->auth_token);
            $client->messages->create(
                $recipients,
                ['from' => $this->twilio_number, 'body' => $message]
            );
            return response()->json(['message' => 'SMS sent successfully']);
        } catch (Exception $e) {
            // Error occurred
            return response()->json(['error' => $e->getMessage()], 500);
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

}
?>
