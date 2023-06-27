<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\Clinic;
use App\Models\ClinicDoctor;
use App\Models\PatientTransaction;
use App\Models\PatientTransactionUploadedFiles;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Service\SmsService;
use App\Service\MailService;
use App\Http\Controllers\PdfController;
use App\Models\ClinicManager;

class ReferralController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:referral-list|referral-create|referral-edit|referral-delete', ['only' => ['index','store']]);
        $this->middleware('permission:referral-create', ['only' => ['create','store']]);
        $this->middleware('permission:referral-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:referral-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinic_ids = ClinicManager::query()->where('manager_id', auth()->user()->id)->pluck('clinic_id');
        $manager_ids = ClinicManager::query()->whereIn('clinic_id', $clinic_ids)->pluck('manager_id');
        $data = PatientTransaction::with(['patient', 'attorney', 'doctor'])
            // ->where('office_id', auth()->user()->id)
            ->whereIn('office_id', $manager_ids)
            ->orderBy('created_at','desc')
            ->get();
        return view('referral.index', compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clinicData = Clinic::get();
        $doctorData = array();
        if (auth()->user()->roles[0]->name == 'office manager'){
            $clinic_ids = ClinicManager::query()->where('manager_id', auth()->user()->id)->pluck('clinic_id');
            $clinicData = Clinic::query()->whereIn('id', $clinic_ids)->get()->all();
            $doctor_ids = ClinicDoctor::query()->whereIn('clinic_id', $clinic_ids)->pluck('doctor_id');
            $doctorData = User::query()->whereIn('id', $doctor_ids)
                ->whereHas(
                    'roles', function($q){
                        $q->where('name', 'doctor');
                    }
                )->get()->all();
        }

        $attorneys = User::whereHas(
            'roles', function($q){
                $q->where('name', 'attorney');
            }
        )->get()->all();

        return view('referral.create', compact('clinicData', 'attorneys', 'doctorData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $patient_id = $attorney_id = $doctor_id = '';
        $welcomeMailData = [];

        $draft = $request->input('draft_flag', '');

        $referral_date = $request->input('referral_date');
        //patient info
        $patient_name = $request->input('patient_name');
        $patient_email = $request->input('patient_email');
        $patient_phone = $request->input('patient_phone');
        $patient_date_birth = $request->input('patient_date_birth');
        $patient_street_adderss = $request->input('patient_street_adderss');
        $patient_street_adderss_line2 = $request->input('patient_street_adderss_line2');
        $patient_city = $request->input('patient_city');
        $patient_state = $request->input('patient_state');
        $patient_postal = $request->input('patient_postal');
        $patient_date_injury = $request->input('patient_date_injury');
        $genders = $request->input('genders');
        $reason_referral = $request->input('reason_referral', array());
        $reason_referral = implode(',', $reason_referral);

        //patient insurance
        $patient_insurance = $request->input('patient_insurance');
        $patient_insurance_company = $request->input('patient_insurance_company');
        $patient_insurance_policy = $request->input('patient_insurance_policy');
        $patient_policy_limit = $request->input('patient_policy_limit');
        $patient_insurance_street_adderss = $request->input('patient_insurance_street_adderss');
        $patient_insurance_street_adderss_line2 = $request->input('patient_insurance_street_adderss_line2');
        $patient_insurance_city = $request->input('patient_insurance_city');
        $patient_insurance_state = $request->input('patient_insurance_state');
        $patient_insurance_postal = $request->input('patient_insurance_postal');

        //defendant insuance
        $defendant_insurance_hit = $request->input('defendant_insurance_hit');
        $defendant_insure = $request->input('defendant_insure');
        $defendant_insurance_company = $request->input('defendant_insurance_company');
        $defendant_insurance_claim = $request->input('defendant_insurance_claim');
        $defendant_policy_limit = $request->input('defendant_policy_limit');
        $defendant_insurance_street_adderss = $request->input('defendant_insurance_street_adderss');
        $defendant_insurance_street_adderss_line2 = $request->input('defendant_insurance_street_adderss_line2');
        $defendant_insurance_city = $request->input('defendant_insurance_city');
        $defendant_insurance_state = $request->input('defendant_insurance_state');
        $defendant_insurance_postal = $request->input('defendant_insurance_postal');

        //Attorney
        $attorney_name = $request->input('attorney_name');
        $attorney_email = $request->input('attorney_email');
        $attorney_phone = $request->input('attorney_phone');
        $law_firm_adderss = $request->input('law_firm_adderss');
        $law_firm_adderss_line2 = $request->input('law_firm_adderss_line2');
        $law_firm_city = $request->input('law_firm_city');
        $law_firm_state = $request->input('law_firm_state');
        $law_firm_postal = $request->input('law_firm_postal');


        //clinic info
        $clinic_name = $request->input('clinic_name');
        $doctor_name = $request->input('doctor_name');
        $doctor_email = $request->input('doctor_email');
        $doctor_phone = $request->input('doctor_phone');
        $doctor_notes = $request->input('doctor_notes');
        if ($draft == 'draft'){
            if ($patient_email == '' || $patient_name == ''){
                return back()->with('flash_error', 'No patient information');
            }
            if ($attorney_email == '' || $attorney_name == ''){
                return back()->with('flash_error', 'No Attorney information');
            }
            if ($doctor_email == '' || $doctor_name == ''){
                return back()->with('flash_error', 'No Doctor information');
            }
            $patient_id = '';
            $attorney_id = '';
            $doctor_id = '';

            $isPatientExist = User::where('email', $patient_email)->first();
            if ($isPatientExist && $isPatientExist->roles[0]->name == 'patient') {
                $patient_id = User::where('email', $patient_email)->value('id');
            }else{
                $patientObj = new User();
                $patientObj->name = $patient_name;
                $patientObj->email = $patient_email;
                $patientObj->password = Hash::make('password');
                $patientObj->phone = $patient_phone;
                $patientObj->date_of_birth = $patient_date_birth;
                $patientObj->address = $patient_street_adderss;
                $patientObj->address_line2 = $patient_street_adderss_line2;
                $patientObj->city = $patient_city;
                $patientObj->state = $patient_state;
                $patientObj->postal = $patient_postal;
                $patientObj->gender = $genders;
                $patientObj->save();
                $patient_id = $patientObj->id;
                //assign the patient role for the user
                $patientObj->assignRole(['patient']);
            }

            $isAttorneyExist = User::where('email', $attorney_email)->first();
            if ($isAttorneyExist && $isAttorneyExist->roles[0]->name == 'attorney') {
                $attorney_id = User::where('email', $attorney_email)->value('id');
            }else{
                $attorneyObj = new User();
                $attorneyObj->name = $attorney_name;
                $attorneyObj->email = $attorney_email;
                $attorneyObj->password = Hash::make('password');
                $attorneyObj->phone = $attorney_phone;
                $attorneyObj->address = $law_firm_adderss;
                $attorneyObj->address_line2 = $law_firm_adderss_line2;
                $attorneyObj->city = $law_firm_city;
                $attorneyObj->state = $law_firm_state;
                $attorneyObj->postal = $law_firm_postal;
                $attorneyObj->save();
                $attorney_id = $attorneyObj->id;

                //assign the attorney role for the user
                $attorneyObj->assignRole(['attorney']);
            }

            $isDoctorExist = User::where('email', $doctor_email)->first();
            if ($isDoctorExist && $isDoctorExist->roles[0]->name == 'doctor') {
                $doctor_id = User::where('email', $doctor_email)->value('id');
            } else {
                $doctorObj = new User();
                $doctorObj->name = $doctor_name;
                $doctorObj->email = $doctor_email;
                $doctorObj->password = Hash::make('password');
                $doctorObj->phone = $doctor_phone;
                $doctorObj->save();
                $doctor_id = $doctorObj->id;

                //assign the doctor role for the user
                $doctorObj->assignRole(['doctor']);

                //assign clinic and doctor
                $clinicDoctorObj = new ClinicDoctor();
                $clinicDoctorObj -> clinic_id = $clinic_name;
                $clinicDoctorObj -> doctor_id = $doctor_id;
                $clinicDoctorObj->save();
            }

            DB::beginTransaction();
            try{
                //create patient referral history.
                $patientTransactionObj = new PatientTransaction();
                $patientTransactionObj->referral_date = $referral_date;
                $patientTransactionObj->office_id = auth()->user()->id;  //current logged in ID
                $patientTransactionObj->patient_id = $patient_id;
                $patientTransactionObj->patient_date_injury = $patient_date_injury;
                $patientTransactionObj->reason_referral = $reason_referral;
                $patientTransactionObj->patient_insurance = $patient_insurance;
                $patientTransactionObj->patient_insurance_company = $patient_insurance_company;
                $patientTransactionObj->patient_insurance_policy = $patient_insurance_policy;
                $patientTransactionObj->patient_policy_limit = $patient_policy_limit;
                $patientTransactionObj->patient_insurance_street_adderss = $patient_insurance_street_adderss;
                $patientTransactionObj->patient_insurance_street_adderss_line2 = $patient_insurance_street_adderss_line2;
                $patientTransactionObj->patient_insurance_city = $patient_insurance_city;
                $patientTransactionObj->patient_insurance_state = $patient_insurance_state;
                $patientTransactionObj->patient_insurance_postal = $patient_insurance_postal;
                $patientTransactionObj->defendant_insurance_hit = $defendant_insurance_hit;
                $patientTransactionObj->defendant_insure = $defendant_insure;
                $patientTransactionObj->defendant_insurance_company = $defendant_insurance_company;
                $patientTransactionObj->defendant_insurance_claim = $defendant_insurance_claim;
                $patientTransactionObj->defendant_policy_limit = $defendant_policy_limit;
                $patientTransactionObj->defendant_insurance_street_adderss = $defendant_insurance_street_adderss;
                $patientTransactionObj->defendant_insurance_street_adderss_line2 = $defendant_insurance_street_adderss_line2;
                $patientTransactionObj->defendant_insurance_city = $defendant_insurance_city;
                $patientTransactionObj->defendant_insurance_state = $defendant_insurance_state;
                $patientTransactionObj->defendant_insurance_postal = $defendant_insurance_postal;
                $patientTransactionObj->attorney_id = $attorney_id;
                $patientTransactionObj->doctor_id = $doctor_id;
                $patientTransactionObj->doctor_notes = $doctor_notes;
                $patientTransactionObj->status = config('const.status_code')['Draft'];
                $patientTransactionObj->save();
                $patientTransactionLatestID = $patientTransactionObj->id;

                DB::commit();

            }catch(Exception $ex){
                DB::rollBack();
                return back()->with('flash_error', $ex->getMessage());
            }

            return redirect(route('referral.index'))->with('flash_success','The form sent successfully');
        } else {
            // if there isn't patient data, create a new patient with patient information and get ID.
            $isPatientExist = User::where('email', $patient_email)->first();
            if ($isPatientExist && $isPatientExist->roles[0]->name == 'patient') {
                $patient_id = User::where('email', $patient_email)->value('id');
            }else{
                $patientObj = new User();
                $patientObj->name = $patient_name;
                $patientObj->email = $patient_email;
                $patientObj->password = Hash::make('password');
                $patientObj->phone = $patient_phone;
                $patientObj->date_of_birth = $patient_date_birth;
                $patientObj->address = $patient_street_adderss;
                $patientObj->address_line2 = $patient_street_adderss_line2;
                $patientObj->city = $patient_city;
                $patientObj->state = $patient_state;
                $patientObj->postal = $patient_postal;
                $patientObj->gender = $genders;
                $patientObj->save();
                $patient_id = $patientObj->id;

                //assign the patient role for the user
                $patientObj->assignRole(['patient']);

                //send email with patient data info  (password)
                $welcomeMailData = [
                    'user_name'   => $patient_name,
                    'password' => 'password'
                ];

                Mail::to($patient_email)->send(new WelcomeEmail($welcomeMailData));
            }


            //if there isn't attorney data, create a new attorney with attorney information and get ID.
            $isAttorneyExist = User::where('email', $attorney_email)->first();
            if ($isAttorneyExist && $isAttorneyExist->roles[0]->name == 'attorney') {
                $attorney_id = User::where('email', $attorney_email)->value('id');
            }else{
                $attorneyObj = new User();
                $attorneyObj->name = $attorney_name;
                $attorneyObj->email = $attorney_email;
                $attorneyObj->password = Hash::make('password');
                $attorneyObj->phone = $attorney_phone;
                $attorneyObj->address = $law_firm_adderss;
                $attorneyObj->address_line2 = $law_firm_adderss_line2;
                $attorneyObj->city = $law_firm_city;
                $attorneyObj->state = $law_firm_state;
                $attorneyObj->postal = $law_firm_postal;
                $attorneyObj->save();
                $attorney_id = $attorneyObj->id;

                //assign the attorney role for the user
                $attorneyObj->assignRole(['attorney']);

                //send email with Attorney data info (password)
                $welcomeMailData = [
                    'user_name'   => $attorney_name,
                    'password' => 'password'
                ];

                Mail::to($doctor_email)->send(new WelcomeEmail($welcomeMailData));
            }

            //if there isn't doctor data, create a new doctor with doctor information and get ID.
            $isDoctorExist = User::where('email', $doctor_email)->first();
            if ($isDoctorExist && $isDoctorExist->roles[0]->name == 'doctor') {
                $doctor_id = User::where('email', $doctor_email)->value('id');
            }else{
                $doctorObj = new User();
                $doctorObj->name = $doctor_name;
                $doctorObj->email = $doctor_email;
                $doctorObj->password = Hash::make('password');
                $doctorObj->phone = $doctor_phone;
                $doctorObj->save();
                $doctor_id = $doctorObj->id;

                //assign the doctor role for the user
                $doctorObj->assignRole(['doctor']);

                //assign clinic and doctor
                $clinicDoctorObj = new ClinicDoctor();
                $clinicDoctorObj -> clinic_id = $clinic_name;
                $clinicDoctorObj -> doctor_id = $doctor_id;
                $clinicDoctorObj->save();

                //send email with Doctor data info (password)
                $welcomeMailData = [
                    'user_name'   => $doctor_name,
                    'password' => 'password'
                ];

                Mail::to($doctor_email)->send(new WelcomeEmail($welcomeMailData));
            }

            DB::beginTransaction();

            try{
                //create patient referral history.
                $patientTransactionObj = new PatientTransaction();
                $patientTransactionObj->referral_date = $referral_date;
                $patientTransactionObj->office_id = auth()->user()->id;  //current logged in ID
                $patientTransactionObj->patient_id = $patient_id;
                $patientTransactionObj->patient_date_injury = $patient_date_injury;
                $patientTransactionObj->reason_referral = $reason_referral;
                $patientTransactionObj->patient_insurance = $patient_insurance;
                $patientTransactionObj->patient_insurance_company = $patient_insurance_company;
                $patientTransactionObj->patient_insurance_policy = $patient_insurance_policy;
                $patientTransactionObj->patient_policy_limit = $patient_policy_limit;
                $patientTransactionObj->patient_insurance_street_adderss = $patient_insurance_street_adderss;
                $patientTransactionObj->patient_insurance_street_adderss_line2 = $patient_insurance_street_adderss_line2;
                $patientTransactionObj->patient_insurance_city = $patient_insurance_city;
                $patientTransactionObj->patient_insurance_state = $patient_insurance_state;
                $patientTransactionObj->patient_insurance_postal = $patient_insurance_postal;
                $patientTransactionObj->defendant_insurance_hit = $defendant_insurance_hit;
                $patientTransactionObj->defendant_insure = $defendant_insure;
                $patientTransactionObj->defendant_insurance_company = $defendant_insurance_company;
                $patientTransactionObj->defendant_insurance_claim = $defendant_insurance_claim;
                $patientTransactionObj->defendant_policy_limit = $defendant_policy_limit;
                $patientTransactionObj->defendant_insurance_street_adderss = $defendant_insurance_street_adderss;
                $patientTransactionObj->defendant_insurance_street_adderss_line2 = $defendant_insurance_street_adderss_line2;
                $patientTransactionObj->defendant_insurance_city = $defendant_insurance_city;
                $patientTransactionObj->defendant_insurance_state = $defendant_insurance_state;
                $patientTransactionObj->defendant_insurance_postal = $defendant_insurance_postal;
                $patientTransactionObj->attorney_id = $attorney_id;
                $patientTransactionObj->doctor_id = $doctor_id;
                $patientTransactionObj->doctor_notes = $doctor_notes;
                $patientTransactionObj->save();
                $patientTransactionLatestID = $patientTransactionObj->id;

                DB::commit();

            }catch(Exception $ex){
                DB::rollBack();
                return back()->with('flash_error', $ex->getMessage());
            }

            $pdf_controller = new PdfController();
            $pdf_data = [
                'referral_date' => $request->input('referral_date'),
                'patient_name' => $request->input('patient_name'),
                'patient_email' => $request->input('patient_email'),
                'patient_phone' => $request->input('patient_phone'),
                'patient_date_birth' => $request->input('patient_date_birth'),
                'patient_street_adderss' => $request->input('patient_street_adderss'),
                'patient_street_adderss_line2' => $request->input('patient_street_adderss_line2'),
                'patient_city' => $request->input('patient_city'),
                'patient_state' => $request->input('patient_state'),
                'patient_postal' => $request->input('patient_postal'),
                'patient_date_injury' => $request->input('patient_date_injury'),
                'genders' => $request->input('genders'),
                'reason_referral' => implode(',', $request->input('reason_referral')),

                'patient_insurance' => $request->input('patient_insurance'),
                'patient_insurance_company' => $request->input('patient_insurance_company'),
                'patient_insurance_policy' => $request->input('patient_insurance_policy'),
                'patient_policy_limit' => $request->input('patient_policy_limit'),
                'patient_insurance_street_adderss' => $request->input('patient_insurance_street_adderss'),
                'patient_insurance_street_adderss_line2' => $request->input('patient_insurance_street_adderss_line2'),
                'patient_insurance_city' => $request->input('patient_insurance_city'),
                'patient_insurance_state' => $request->input('patient_insurance_state'),
                'patient_insurance_postal' => $request->input('patient_insurance_postal'),

                'defendant_insurance_hit' => $request->input('defendant_insurance_hit'),
                'defendant_insure' => $request->input('defendant_insure'),
                'defendant_insurance_company' => $request->input('defendant_insurance_company'),
                'defendant_insurance_claim' => $request->input('defendant_insurance_claim'),
                'defendant_policy_limit' => $request->input('defendant_policy_limit'),
                'defendant_insurance_street_adderss' => $request->input('defendant_insurance_street_adderss'),
                'defendant_insurance_street_adderss_line2' => $request->input('defendant_insurance_street_adderss_line2'),
                'defendant_insurance_city' => $request->input('defendant_insurance_city'),
                'defendant_insurance_state' => $request->input('defendant_insurance_state'),
                'defendant_insurance_postal' => $request->input('defendant_insurance_postal'),

                'attorney_name' => $request->input('attorney_name'),
                'attorney_email' => $request->input('attorney_email'),
                'attorney_phone' => $request->input('attorney_phone'),
                'law_firm_adderss' => $request->input('law_firm_adderss'),
                'law_firm_adderss_line2' => $request->input('law_firm_adderss_line2'),
                'law_firm_city' => $request->input('law_firm_city'),
                'law_firm_state' => $request->input('law_firm_state'),
                'law_firm_postal' => $request->input('law_firm_postal'),

                'clinic_data' => auth()->user()->clinic_by_manager->clinic,
                'doctor_name' => $request->input('doctor_name'),
                'doctor_email' => $request->input('doctor_email'),
                'doctor_phone' => $request->input('doctor_phone'),
                'doctor_notes' => $request->input('doctor_notes'),
            ];
            $pdf_data['id'] = $patientTransactionLatestID;
            $pdf_data['patient_id'] = $patient_id;
            $pdf_controller->generateReferralPdf($pdf_data);
            return redirect(route('referral.index'))->with('flash_success','The form sent successfully');
        }
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
        $data = PatientTransaction::with(['patient', 'attorney', 'doctor', 'files', 'result_files', 'invoice_files'])->find($id);
        $clinicData = Clinic::get();
        $doctorData = array();
        if (auth()->user()->roles[0]->name == 'office manager'){
            $clinic_ids = ClinicManager::query()->where('manager_id', auth()->user()->id)->pluck('clinic_id');
            $clinicData = Clinic::query()->whereIn('id', $clinic_ids)->get()->all();
            $doctor_ids = ClinicDoctor::query()->whereIn('clinic_id', $clinic_ids)->pluck('doctor_id');
            $doctorData = User::query()->whereIn('id', $doctor_ids)
                ->whereHas(
                    'roles', function($q){
                        $q->where('name', 'doctor');
                    }
                )->get()->all();
        }
        $clinic_id = ClinicDoctor::where('doctor_id', $data->doctor_id)->value('clinic_id');
        $attorneys = User::whereHas(
            'roles', function($q){
                $q->where('name', 'attorney');
            }
        )->get()->all();

        return view ('referral.edit', compact('data', 'clinicData', 'clinic_id', 'attorneys', 'doctorData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patientTransactionObj = PatientTransaction::findorFail($id);
        switch($patientTransactionObj -> status){
            case config('const.status_code.Pending'):
            case config('const.status_code.Draft'):
                $referral_date = $request->input('referral_date');
                //patient info
                $patient_name = $request->input('patient_name');
                $patient_email = $request->input('patient_email');
                $patient_phone = $request->input('patient_phone');
                $patient_date_birth = $request->input('patient_date_birth');
                $patient_street_adderss = $request->input('patient_street_adderss');
                $patient_street_adderss_line2 = $request->input('patient_street_adderss_line2');
                $patient_city = $request->input('patient_city');
                $patient_state = $request->input('patient_state');
                $patient_postal = $request->input('patient_postal');
                $patient_date_injury = $request->input('patient_date_injury');
                $genders = $request->input('genders');
                $reason_referral = $request->input('reason_referral', array());
                $reason_referral = implode(',', $reason_referral);

                //patient insurance
                $patient_insurance = $request->input('patient_insurance');
                $patient_insurance_company = $request->input('patient_insurance_company');
                $patient_insurance_policy = $request->input('patient_insurance_policy');
                $patient_policy_limit = $request->input('patient_policy_limit');
                $patient_insurance_street_adderss = $request->input('patient_insurance_street_adderss');
                $patient_insurance_street_adderss_line2 = $request->input('patient_insurance_street_adderss_line2');
                $patient_insurance_city = $request->input('patient_insurance_city');
                $patient_insurance_state = $request->input('patient_insurance_state');
                $patient_insurance_postal = $request->input('patient_insurance_postal');

                //defendant insuance
                $defendant_insurance_hit = $request->input('defendant_insurance_hit');
                $defendant_insure = $request->input('defendant_insure');
                $defendant_insurance_company = $request->input('defendant_insurance_company');
                $defendant_insurance_claim = $request->input('defendant_insurance_claim');
                $defendant_policy_limit = $request->input('defendant_policy_limit');
                $defendant_insurance_street_adderss = $request->input('defendant_insurance_street_adderss');
                $defendant_insurance_street_adderss_line2 = $request->input('defendant_insurance_street_adderss_line2');
                $defendant_insurance_city = $request->input('defendant_insurance_city');
                $defendant_insurance_state = $request->input('defendant_insurance_state');
                $defendant_insurance_postal = $request->input('defendant_insurance_postal');

                //Attorney
                $attorney_name = $request->input('attorney_name');
                $attorney_email = $request->input('attorney_email');
                $attorney_phone = $request->input('attorney_phone');
                $law_firm_adderss = $request->input('law_firm_adderss');
                $law_firm_adderss_line2 = $request->input('law_firm_adderss_line2');
                $law_firm_city = $request->input('law_firm_city');
                $law_firm_state = $request->input('law_firm_state');
                $law_firm_postal = $request->input('law_firm_postal');


                //clinic info
                $clinic_name = $request->input('clinic_name');
                $doctor_name = $request->input('doctor_name');
                $doctor_email = $request->input('doctor_email');
                $doctor_phone = $request->input('doctor_phone');
                $doctor_notes = $request->input('doctor_notes');

                $draft = $request->input('draft_flag', '');


                $patient_user = User::where('id', $patientTransactionObj->patient_id)->first();
                if ($patient_user){
                    $patient_user->name = $patient_name;
                    $patient_user->email = $patient_email;
                    $patient_user->phone = $patient_phone;
                    $patient_user->date_of_birth = $patient_date_birth;
                    $patient_user->address = $patient_street_adderss;
                    $patient_user->address_line2 = $patient_street_adderss_line2;
                    $patient_user->city = $patient_city;
                    $patient_user->state = $patient_state;
                    $patient_user->postal = $patient_postal;
                    $patient_user->gender = $genders;
                    $patient_user->save();
                }

                $attorney_user = User::where('email', $attorney_email)->first();
                // if ($attorney_user == null){
                //     $attorney_user = User::where('id', $patientTransactionObj->attorney_id)->first();
                // }

                if ($attorney_user){
                    $attorney_user->name = $attorney_name;
                    $attorney_user->email = $attorney_email;
                    $attorney_user->phone = $attorney_phone;
                    $attorney_user->address = $law_firm_adderss;
                    $attorney_user->address_line2 = $law_firm_adderss_line2;
                    $attorney_user->city = $law_firm_city;
                    $attorney_user->state = $law_firm_state;
                    $attorney_user->postal = $law_firm_postal;
                    $attorney_user->save();
                } else {
                    $attorney_user = new User();
                    $attorney_user->name = $attorney_name;
                    $attorney_user->email = $attorney_email;
                    $attorney_user->password = Hash::make('password');
                    $attorney_user->phone = $attorney_phone;
                    $attorney_user->address = $law_firm_adderss;
                    $attorney_user->address_line2 = $law_firm_adderss_line2;
                    $attorney_user->city = $law_firm_city;
                    $attorney_user->state = $law_firm_state;
                    $attorney_user->postal = $law_firm_postal;
                    $attorney_user->save();
                    $attorney_user->assignRole(['attorney']);
                }

                $doctor_user = User::where('email', $doctor_email)->first();
                // if ($doctor_user == null){
                //     $doctor_user = User::where('id', $patientTransactionObj->doctor_id)->first();
                // }
                if ($doctor_user){
                    $doctor_user->name = $doctor_name;
                    $doctor_user->email = $doctor_email;
                    $doctor_user->phone = $doctor_phone;
                    $doctor_user->save();
                } else {
                    $doctor_user = new User();
                    $doctor_user->name = $doctor_name;
                    $doctor_user->email = $doctor_email;
                    $doctor_user->password = Hash::make('password');
                    $doctor_user->phone = $doctor_phone;
                    $doctor_user->save();
                    $doctor_user->assignRole(['doctor']);

                    $clinicDoctorObj = new ClinicDoctor();
                    $clinicDoctorObj -> clinic_id = $clinic_name;
                    $clinicDoctorObj -> doctor_id = $doctor_user->id;
                    $clinicDoctorObj->save();
                }

                $patientTransactionObj->referral_date = $referral_date;
                $patientTransactionObj->doctor_id = $doctor_user->id;
                $patientTransactionObj->attorney_id = $attorney_user->id;
                $patientTransactionObj->office_id = auth()->user()->id;  //current logged in ID
                $patientTransactionObj->patient_date_injury = $patient_date_injury;
                $patientTransactionObj->reason_referral = $reason_referral;
                $patientTransactionObj->patient_insurance = $patient_insurance;
                $patientTransactionObj->patient_insurance_company = $patient_insurance_company;
                $patientTransactionObj->patient_insurance_policy = $patient_insurance_policy;
                $patientTransactionObj->patient_policy_limit = $patient_policy_limit;
                $patientTransactionObj->patient_insurance_street_adderss = $patient_insurance_street_adderss;
                $patientTransactionObj->patient_insurance_street_adderss_line2 = $patient_insurance_street_adderss_line2;
                $patientTransactionObj->patient_insurance_city = $patient_insurance_city;
                $patientTransactionObj->patient_insurance_state = $patient_insurance_state;
                $patientTransactionObj->patient_insurance_postal = $patient_insurance_postal;
                $patientTransactionObj->defendant_insurance_hit = $defendant_insurance_hit;
                $patientTransactionObj->defendant_insure = $defendant_insure;
                $patientTransactionObj->defendant_insurance_company = $defendant_insurance_company;
                $patientTransactionObj->defendant_insurance_claim = $defendant_insurance_claim;
                $patientTransactionObj->defendant_policy_limit = $defendant_policy_limit;
                $patientTransactionObj->defendant_insurance_street_adderss = $defendant_insurance_street_adderss;
                $patientTransactionObj->defendant_insurance_street_adderss_line2 = $defendant_insurance_street_adderss_line2;
                $patientTransactionObj->defendant_insurance_city = $defendant_insurance_city;
                $patientTransactionObj->defendant_insurance_state = $defendant_insurance_state;
                $patientTransactionObj->defendant_insurance_postal = $defendant_insurance_postal;
                $patientTransactionObj->doctor_notes = $doctor_notes;
                $patientTransactionObj->status = ($draft != 'draft') ? config('const.status_code.Pending') : config('const.status_code.Draft');
                $patientTransactionObj->save();

                if ($draft != 'draft'){
                    $pdf_controller = new PdfController();
                    $pdf_data = [
                        'id' => $id,
                        'patient_id' => $patientTransactionObj->patient_id,
                        'referral_date' => $request->input('referral_date'),
                        'patient_name' => $request->input('patient_name'),
                        'patient_email' => $request->input('patient_email'),
                        'patient_phone' => $request->input('patient_phone'),
                        'patient_date_birth' => $request->input('patient_date_birth'),
                        'patient_street_adderss' => $request->input('patient_street_adderss'),
                        'patient_street_adderss_line2' => $request->input('patient_street_adderss_line2'),
                        'patient_city' => $request->input('patient_city'),
                        'patient_state' => $request->input('patient_state'),
                        'patient_postal' => $request->input('patient_postal'),
                        'patient_date_injury' => $request->input('patient_date_injury'),
                        'genders' => $request->input('genders'),
                        'reason_referral' => implode(',', $request->input('reason_referral', array())),

                        'patient_insurance' => $request->input('patient_insurance'),
                        'patient_insurance_company' => $request->input('patient_insurance_company'),
                        'patient_insurance_policy' => $request->input('patient_insurance_policy'),
                        'patient_policy_limit' => $request->input('patient_policy_limit'),
                        'patient_insurance_street_adderss' => $request->input('patient_insurance_street_adderss'),
                        'patient_insurance_street_adderss_line2' => $request->input('patient_insurance_street_adderss_line2'),
                        'patient_insurance_city' => $request->input('patient_insurance_city'),
                        'patient_insurance_state' => $request->input('patient_insurance_state'),
                        'patient_insurance_postal' => $request->input('patient_insurance_postal'),

                        'defendant_insurance_hit' => $request->input('defendant_insurance_hit'),
                        'defendant_insure' => $request->input('defendant_insure'),
                        'defendant_insurance_company' => $request->input('defendant_insurance_company'),
                        'defendant_insurance_claim' => $request->input('defendant_insurance_claim'),
                        'defendant_policy_limit' => $request->input('defendant_policy_limit'),
                        'defendant_insurance_street_adderss' => $request->input('defendant_insurance_street_adderss'),
                        'defendant_insurance_street_adderss_line2' => $request->input('defendant_insurance_street_adderss_line2'),
                        'defendant_insurance_city' => $request->input('defendant_insurance_city'),
                        'defendant_insurance_state' => $request->input('defendant_insurance_state'),
                        'defendant_insurance_postal' => $request->input('defendant_insurance_postal'),

                        'attorney_name' => $request->input('attorney_name'),
                        'attorney_email' => $request->input('attorney_email'),
                        'attorney_phone' => $request->input('attorney_phone'),
                        'law_firm_adderss' => $request->input('law_firm_adderss'),
                        'law_firm_adderss_line2' => $request->input('law_firm_adderss_line2'),
                        'law_firm_city' => $request->input('law_firm_city'),
                        'law_firm_state' => $request->input('law_firm_state'),
                        'law_firm_postal' => $request->input('law_firm_postal'),

                        'clinic_data' => auth()->user()->clinic_by_manager->clinic,
                        'doctor_name' => $request->input('doctor_name'),
                        'doctor_email' => $request->input('doctor_email'),
                        'doctor_phone' => $request->input('doctor_phone'),
                        'doctor_notes' => $request->input('doctor_notes'),
                    ];
                    $pdf_controller->generateReferralPdf($pdf_data);
                }

                return back()->with('flash_success', 'The patient is updated successfully');
                break;
            case config('const.status_code.Booked'):
                //file upload
                if ($request->hasFile('files')) {
                    $patient_name = $request->input('patient_name');
                    PatientTransactionUploadedFiles::query()->where('transaction_id', $id)->delete();
                    $uploadedFiles = $request->file('files');
                    foreach ($uploadedFiles as $file) {
                        $patientTransactionUploadedFilesObj = new PatientTransactionUploadedFiles();

                        //set the file name
                        // $fileName = $file->getClientOriginalName();
                        $fileName = $patient_name.'-Singed Lien-'.date('m-d-Y').'.pdf';

                        //move the file into the desired folder
                        $file->move(public_path('uploads/sign'), $fileName);

                        // Save the upload result into the database
                        $patientTransactionUploadedFilesObj->transaction_id = $id;
                        $patientTransactionUploadedFilesObj->files = $fileName;
                        $patientTransactionUploadedFilesObj->save();

                        PatientTransaction::query()->where('id', $id)->update(['status' => config('const.status_code.Signed')]);
                        $sms_service = new SmsService();
                        $sms_service->sendSignedSMS($id);

                        $mail_service = new MailService();
                        $mail_service->sendSignedMail($id, $fileName);
                    }
                }
                return back()->with('flash_success', 'The sign document is uploaded successfully');
                break;
            default:
                return back()->with('flash_success', 'The form sent successfully');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PatientTransaction::find($id)->delete();
        return response()->json(['message' => 'Record deleted successfully.']);
    }

    /**
     *
     */

     public function deleteReferralFile(Request $request, $id)
     {
        $obj = PatientTransactionUploadedFiles::findorFail($id);
        $fileName = $obj->files;

        if(File::exists(public_path('uploads/sign/'.$fileName))){
            File::delete(public_path('uploads/sign/'.$fileName));
        }

        $obj->delete();

        return response()->json(['msg' => 'success' ]);
     }
}

