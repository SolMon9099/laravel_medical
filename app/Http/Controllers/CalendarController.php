<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientTransaction;
use App\Models\PatientSchedule;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patient_data = PatientTransaction::with(['patient'])
            ->where('office_id', auth()->user()->id)
            // ->where('status', 0)
            ->orderBy('created_at','desc')
            ->get()->all();

        $schedule_data = PatientSchedule::with(['patient'])
            ->where('office_id', auth()->user()->id)
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
                $new_schedule->office_id = auth()->user()->id;
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
                $schedule_record->office_id = auth()->user()->id;
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
                    $new_schedule->office_id = auth()->user()->id;
                    $new_schedule->start_date = date('Y-m-d H:i:s', strtotime($request['start_date']));
                    $new_schedule->end_date = date('Y-m-d H:i:s', strtotime($request['end_date']));
                    $new_schedule->title = $request['title'];
                    if (isset($request['description']) && $request['description'] != ''){
                        $new_schedule->description = $request['description'];
                    }
                    $new_schedule->created_at = date('Y-m-d H:i:s');
                    $new_schedule->updated_at = date('Y-m-d H:i:s');
                    $new_schedule->save();
                    break;
                case 'edit':
                    if (isset($request['id'])){
                        $schedule_record = PatientSchedule::find((int)$request['id']);
                        $schedule_record->patient_id = $request['patient_id'];
                        $schedule_record->office_id = auth()->user()->id;
                        $schedule_record->start_date = date('Y-m-d H:i:s', strtotime($request['start_date']));
                        $schedule_record->end_date = date('Y-m-d H:i:s', strtotime($request['end_date']));
                        $schedule_record->title = $request['title'];
                        if (isset($request['description']) && $request['description'] != ''){
                            $schedule_record->description = $request['description'];
                        }
                        $schedule_record->updated_at = date('Y-m-d H:i:s');
                        $schedule_record->save();
                    }
                    break;
                case 'delete':
                    if (isset($request['id'])){
                        PatientSchedule::query()->where('id', $request['id'])->delete();
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
