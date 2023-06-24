<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PatientSchedule;
use Illuminate\Support\Facades\Log;
use App\Service\SmsService;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookAlertEmail;

class BookNotify extends Command
{
    protected $signature = 'book_alert';

    protected $description = 'Book alert for patiets. Alert via SMS/Email 1 day ago';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('start cron for book alert');
        $tomorrow = date("Y-m-d 23:59:59", strtotime('tomorrow'));
        $sch_data = PatientSchedule::query()
            ->where('start_date', '<', $tomorrow)
            ->where('start_date', '>=', date('Y-m-d'))
            ->orderBy('created_at','desc')
            ->get()->all();

        $sms_service = new SmsService();
        foreach($sch_data as $item){
            $message = $sms_service->makePatientMessage(
                $item->patient,
                ['start_date' => $item->start_date, 'end_date' => $item->end_date, 'transaction' => $item->patient_transaction],
                'notify'
            );
            $sms_service->sendSMS($message, $item->patient->phone);

            $mailData = [
                'name'   => $item->patient->name,
                'start_date' => date('m-d-Y H:i', strtotime($item->start_date)),
                'end_date' => date('m-d-Y H:i', strtotime($item->end_date)),
                'clinic_phone' => $item->patient_transaction->doctor->phone
            ];
            Mail::to($item->patient->email)->send(new BookAlertEmail($mailData));
        }
        return 0;
    }
}
