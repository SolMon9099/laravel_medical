<?php

namespace App\Http\Controllers;

use App\Models\PatientSchedule;
use App\Models\PatientTransaction;
use App\Models\User;
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
        // if (auth()->user()->roles[0]->name == 'patient'){
        //     return redirect(route('profiles.patient_transaction'));

        // } else {
            $data = PatientTransaction::all();
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
            $schedules = PatientSchedule::query()->where('start_date', '>=', date('Y-m-d'))->get()->all();
            return view('home', compact('data', 'technicians', 'doctors', 'schedules'));
        // }

    }
}
