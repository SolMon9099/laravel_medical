<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientTransaction;
use App\Models\PatientSchedule;
use App\Models\User;
use App\Service\SmsService;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookAlertEmail;
use App\Models\Clinic;
use App\Models\ClinicDoctor;
use App\Models\ClinicManager;
use Exception;
class CalendarController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:calendar-list|calendar-create|calendar-edit|calendar-delete', ['only' => ['index','store']]);
        $this->middleware('permission:calendar-create', ['only' => ['create','store']]);
        $this->middleware('permission:calendar-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:calendar-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaction_ids = [];
        if (auth()->user()->roles[0]->name == 'office manager'){
            $clinic_ids = ClinicManager::query()->where('manager_id', auth()->user()->id)->pluck('clinic_id');
            $manager_ids = ClinicManager::query()->whereIn('clinic_id', $clinic_ids)->pluck('manager_id');
            $patient_data = PatientTransaction::with(['patient'])
                // ->where('office_id', auth()->user()->id)
                ->whereIn('office_id', $manager_ids)
                ->where('status', '!=', config('const.status_code.Draft'))
                ->orderBy('created_at','desc')
                ->get()->all();
        } else {     //technician
            $clinic_ids = Clinic::query()->where('technician_id', auth()->user()->id)->pluck('id');
            $doctor_ids = ClinicDoctor::query()->whereIn('clinic_id', $clinic_ids)->pluck('doctor_id');
            $patient_data = PatientTransaction::with(['patient'])
                ->whereIn('doctor_id', $doctor_ids)
                ->where('status', '!=', config('const.status_code.Draft'))
                ->orderBy('created_at','desc')
                ->get()->all();
        }
        
        foreach($patient_data as $item){
            $transaction_ids[] = $item->id;
        }

        $schedule_data = PatientSchedule::with(['patient', 'patient_transaction'])
            // ->where('office_id', auth()->user()->id)
            ->whereIn('patient_transaction_id', $transaction_ids)
            ->orderBy('created_at','desc')
            ->get()->all();
        return view('calendar.index')->with([
            'patient_data' => $patient_data,
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

        $deleted_schedules = array();
        if (!empty($request['deleted_schedules'])){
            $deleted_schedules = json_decode($request['deleted_schedules']);
        }
        PatientSchedule::query()->whereIn('id', $deleted_schedules)->delete();

        $booked_schedules = array();
        if (!empty($request['booked_schedules'])){
            $booked_schedules = json_decode($request['booked_schedules']);
        }
        foreach($booked_schedules as $schedule_item){
            $schedule_item = (array)$schedule_item;
            $this->validate(Request::create('/', 'POST', $schedule_item), [
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'patient_id' => 'required',
            ]);
            if (isset($schedule_item['is_new']) && $schedule_item['is_new'] == true){
                $new_schedule = new PatientSchedule();
                $new_schedule->patient_id = $schedule_item['patient_id'];
                // $new_schedule->office_id = auth()->user()->id;
                $new_schedule->start_date = date('Y-m-d H:i:s', strtotime($schedule_item['start_date']));
                $new_schedule->end_date = date('Y-m-d H:i:s', strtotime($schedule_item['end_date']));
                $new_schedule->title = $schedule_item['title'];
                if (isset($schedule_item['description']) && $schedule_item['description'] != ''){
                    $new_schedule->description = $schedule_item['description'];
                }
                $new_schedule->created_at = date('Y-m-d H:i:s');
                $new_schedule->updated_at = date('Y-m-d H:i:s');
                $new_schedule->save();
            } else {
                $schedule_record = PatientSchedule::find((int)$schedule_item['id']);
                $schedule_record->patient_id = $schedule_item['patient_id'];
                // $schedule_record->office_id = auth()->user()->id;
                $schedule_record->start_date = date('Y-m-d H:i:s', strtotime($schedule_item['start_date']));
                $schedule_record->end_date = date('Y-m-d H:i:s', strtotime($schedule_item['end_date']));
                $schedule_record->title = $schedule_item['title'];
                if (isset($schedule_item['description']) && $schedule_item['description'] != ''){
                    $schedule_record->description = $schedule_item['description'];
                }
                $schedule_record->updated_at = date('Y-m-d H:i:s');
                $schedule_record->save();
            }
        }
        return redirect(route('calendar.index'))->with('flash_success','Schedule saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function action(Request $request)
    {
        if (isset($request['type'])){
            switch($request['type']){
                case 'add':
                    $new_schedule = new PatientSchedule();
                    $new_schedule->patient_id = $request['patient_id'];
                    $new_schedule->patient_transaction_id = $request['patient_transaction_id'];
                    // $new_schedule->office_id = auth()->user()->id;
                    $new_schedule->start_date = date('Y-m-d H:i:s', strtotime($request['start_date']));
                    $new_schedule->end_date = date('Y-m-d H:i:s', strtotime($request['end_date']));
                    $new_schedule->title = $request['title'];
                    if (isset($request['description']) && $request['description'] != ''){
                        $new_schedule->description = $request['description'];
                    }
                    $new_schedule->created_at = date('Y-m-d H:i:s');
                    $new_schedule->updated_at = date('Y-m-d H:i:s');
                    $new_schedule->save();

                    PatientTransaction::query()->where('id', $request['patient_transaction_id'])->update(['status' => config('const.status_code.Booked')]);

                    //SMS----------
                    $sms_service = new SmsService();
                    $user = User::find($request['patient_id']);
                    $message = $sms_service->makePatientMessage($user, $request, 'add');
                    $res = $sms_service->sendSMS($message, $user->phone);
                    if ($res !== true){
                        return json_encode(['error' => $res]);
                    }

                    //mail--------------
                    $mailData = [
                        'name'   => $user->name,
                        'start_date' => date('m-d-Y H:i', strtotime($request['start_date'])),
                        'end_date' => date('m-d-Y H:i', strtotime($request['end_date'])),
                        'clinic_phone' => auth()->user()->phone
                    ];
                    Mail::to($user->email)->send(new BookAlertEmail($mailData));

                    break;
                case 'edit':
                    if (isset($request['id'])){
                        $schedule_record = PatientSchedule::find((int)$request['id']);
                        if (isset($request['patient_id']) && $request['patient_id'] > 0){
                            $schedule_record->patient_id = $request['patient_id'];
                            $schedule_record->patient_transaction_id = $request['patient_transaction_id'];
                            PatientTransaction::query()->where('id', $schedule_record->patient_transaction_id)->update(['status' => config('const.status_code.Pending')]);
                            PatientTransaction::query()->where('id', $request['patient_transaction_id'])->update(['status' => config('const.status_code.Booked')]);
                        }
                        // $schedule_record->office_id = auth()->user()->id;
                        $schedule_record->start_date = date('Y-m-d H:i:s', strtotime($request['start_date']));
                        $schedule_record->end_date = date('Y-m-d H:i:s', strtotime($request['end_date']));
                        $schedule_record->title = $request['title'];
                        if (isset($request['description']) && $request['description'] != ''){
                            $schedule_record->description = $request['description'];
                        }
                        $schedule_record->updated_at = date('Y-m-d H:i:s');
                        $schedule_record->save();


                        $sms_service = new SmsService();
                        $user = User::find($schedule_record->patient_id);
                        $message = $sms_service->makePatientMessage($user, $request, 'edit');
                        $sms_service->sendSMS($message, $user->phone);

                        //mail--------------
                        $mailData = [
                            'name'   => $user->name,
                            'start_date' => date('m-d-Y H:i', strtotime($request['start_date'])),
                            'end_date' => date('m-d-Y H:i', strtotime($request['end_date'])),
                            'clinic_phone' => auth()->user()->phone
                        ];
                        try{
                            Mail::to($user->email)->send(new BookAlertEmail($mailData));
                        } catch (Exception $e) {
                            // Error occurred
                            var_dump($e->getMessage());exit;
                        }

                    }
                    break;
                case 'delete':
                    if (isset($request['id'])){
                        $schedule_record = PatientSchedule::query()->where('id', (int)$request['id'])->get()->first();
                        $schedule_data = [
                            'start_date' => $schedule_record->start_date,
                            'end_date' => $schedule_record->end_date,
                            'id' => $schedule_record->id
                        ];
                        PatientSchedule::query()->where('id', $request['id'])->delete();
                        PatientTransaction::query()->where('id', $schedule_record->patient_transaction_id)->update(['status' => config('const.status_code.Pending')]);

                        $sms_service = new SmsService();
                        $user = User::find($schedule_record->patient_id);
                        $message = $sms_service->makePatientMessage($user, $schedule_data, 'delete');
                        $sms_service->sendSMS($message, $user->phone);

                        //mail--------------
                        $mailData = [
                            'name'   => $user->name,
                            'start_date' => date('m-d-Y H:i', strtotime($schedule_data['start_date'])),
                            'end_date' => date('m-d-Y H:i', strtotime($schedule_data['end_date'])),
                            'type' => 'delete',
                            'clinic_phone' => auth()->user()->phone
                        ];
                        Mail::to($user->email)->send(new BookAlertEmail($mailData));
                    }
                    break;
            }
            return json_encode(['message' => 'added successfully']);
        } else {
            return json_encode(['error' => 'no type selected']);
        }
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
