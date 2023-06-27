<?php

namespace App\Http\Controllers;

use App\Models\PatientSchedule;
use App\Models\PatientTransaction;
use App\Models\User;
use App\Models\Clinic;
use App\Models\ClinicDoctor;
use App\Models\ClinicManager;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public  function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->user()->roles[0]->name == 'patient'){
            return redirect(route('profiles.patient_transaction'));
        } else {
            switch(auth()->user()->roles[0] ->id){
                case config('const.role_codes')['office manager']:
                    $clinic_ids = ClinicManager::query()->where('manager_id', auth()->user()->id)->pluck('clinic_id');
                    $manager_ids = ClinicManager::query()->whereIn('clinic_id', $clinic_ids)->pluck('manager_id');
                    $data = PatientTransaction::query()
                        ->whereIn('office_id', $manager_ids)
                        // ->where('office_id', auth()->user()->id)
                        // ->where('status', '!=', config('const.status_code')['Draft'])
                        ->get()->all();
                    break;
                case config('const.role_codes')['doctor']:
                    $data = PatientTransaction::query()->where('doctor_id', auth()->user()->id)
                        // ->where('status', '!=', config('const.status_code')['Draft'])
                        ->get()->all();
                    break;
                case config('const.role_codes')['attorney']:
                    $data = PatientTransaction::query()->where('attorney_id', auth()->user()->id)
                        // ->where('status', '!=', config('const.status_code')['Draft'])
                        ->get()->all();
                    break;
                case config('const.role_codes')['technician']:
                    $clinics = Clinic::query()->where('technician_id', auth()->user()->id)->pluck('id');
                    $doctors = ClinicDoctor::query()->whereIn('clinic_id', $clinics)->pluck('doctor_id');
                    $data = PatientTransaction::query()
                        // ->where('status', '!=', config('const.status_code')['Draft'])
                        ->whereIn('doctor_id', $doctors)
                        ->get()->all();
                    break;
                default:
                    $data = PatientTransaction::query()
                        // ->where('status', '!=', config('const.status_code')['Draft'])
                        ->get()->all();
                    break;
            }
            $all_transaction_ids = [];
            foreach($data as $item){
                $all_transaction_ids[] = $item->id;
            }

            $technicians = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'technician');
                }
            )->get()->all();
            $doctors = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'doctor');
                }
            )->get()->all();
            $schedules = PatientSchedule::query()->where('start_date', '>=', date('Y-m-d'))
                ->whereIn('patient_transaction_id', $all_transaction_ids)->orderBy('start_date')->get()->all();
            return view('home', compact('data', 'technicians', 'doctors', 'schedules'));
        }

    }
}
