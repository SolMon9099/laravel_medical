@extends('layouts.master')

@section('title', 'Patient Edit')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">  
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/form-file-uploader.css') }}">   --}}
@endsection

@section('content')
<section class="referralSection">   
    <form method="post" action="{{ route('referral.update', $data->id) }}" enctype="multipart/form-data" id="referralForm">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title text-primary">Patient Edit</h2>
                    </div> 

                    <div class="card-body">
                        <div class="row">
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_name">Date</label>
                                <input type="text" id="referral_date" name="referral_date" value="{{$data->referral_date}}" class="form-control flatpickr_date" />                    
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <h4 class="card-title text-primary">Patient & Accident Info</h4>
                    </div> 
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_name">Patient Name</label>
                                <input type="text" id="patient_name" name="patient_name" class="form-control"  value= "{{ $data->patient->name}}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_email">Patient Email</label>
                                <input type="email" id="patient_email" name="patient_email" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe" value={{$data->patient->email}} />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_phone">Patient phone</label>
                                <input type="text" id="patient_phone" name="patient_phone" class="form-control phone-number-mask" placeholder=""  value="{{$data->patient->phone}}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_date_birth">Date of Birth</label>
                                <input type="email" id="patient_date_birth" name="patient_date_birth" class="form-control flatpickr_dates"  value="{{$data->patient->date_of_birth}}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_street_adderss">Patient Street Address</label>
                                <input type="text" id="patient_street_adderss" name="patient_street_adderss" class="form-control" value="{{$data->patient->address}}"  />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_street_adderss_line2">Patient Street Address Line2</label>
                                <input type="text" id="patient_street_adderss_line2" name="patient_street_adderss_line2" class="form-control" value="{{$data->patient->address_line2}}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_city">Patient City</label>
                                <input type="text" id="patient_city" name="patient_city" class="form-control" value="{{$data->patient->city}}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_city">Patient State/Province</label>
                                <input type="text" id="patient_state" name="patient_state" class="form-control" value="{{$data->patient->state}}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_city">Patient Postal/Zip Code</label>
                                <input type="text" id="patient_postal" name="patient_postal" class="form-control" value="{{$data->patient->postal}}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_date_injury">Date of Injury</label>
                                <input type="text" id="patient_date_injury" name="patient_date_injury" value="{{ $data->patient_date_injury }}" class="form-control flatpickr_dates" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_gender">Gender</label>
                                <div class="demo-inline-spacing">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="genders" id="inlineRadio1" value="male" 
                                        @if ($data->patient->gender == 'male') checked @endif />
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="genders" id="inlineRadio2" value="female" 
                                        @if ($data->patient->gender == 'female') checked @endif />
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
                                </div>
                            </div>
                            @php
                                $reason_referral = explode(',', $data->reason_referral);
                            @endphp
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="patient_date_injury">Reason for Referral</label>
                                <div class="row reason_row">
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason1" name="reason_referral[]" value="Headaches/Migraines" 
                                        @if (in_array('Headaches/Migraines', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason1">Headaches/Migraines</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason2" name="reason_referral[]" value="Memory and/or Concentration Problems" 
                                        @if (in_array('Memory and/or Concentration Problems', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason2">Memory and/or Concentration Problems</label>
                                    </div>
            
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason3" name="reason_referral[]" value="Inability to Focus/Attention Problems" 
                                        @if (in_array('Inability to Focus/Attention Problems', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason3">Inability to Focus/Attention Problems</label>
                                    </div>
            
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason4" name="reason_referral[]" value="Blurry/Double Vision"  
                                        @if (in_array('Blurry/Double Vision', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason4">Blurry/Double Vision</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason5" name="reason_referral[]" value="Depression"  
                                        @if (in_array('Depression', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason5">Depression</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason6" name="reason_referral[]" value="Personality Changes" 
                                        @if (in_array('Personality Changes', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason6">Personality Changes</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason7" name="reason_referral[]" value="Brain Bleed/Swelling" 
                                        @if (in_array('Brain Bleed/Swelling', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason7">Brain Bleed/Swelling</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason8" name="reason_referral[]" value="PTSD"  
                                        @if (in_array('PTSD', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason8">PTSD</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason9" name="reason_referral[]" value="Sensitivity to Light or Noise" 
                                        @if (in_array('Sensitivity to Light or Noise', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason9">Sensitivity to Light or Noise</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason10" name="reason_referral[]" value="Dizziness/Balance Problems/Ringing in Ears" 
                                        @if (in_array('Dizziness/Balance Problems/Ringing in Ears', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason10">Dizziness/Balance Problems/Ringing in Ears</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason11" name="reason_referral[]" value="Alteration of Speech/Abnormal Speech" 
                                        @if (in_array('Alteration of Speech/Abnormal Speech', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason11">Alteration of Speech/Abnormal Speech</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason12" name="reason_referral[]" value="Mental Fogginess"  
                                        @if (in_array('Mental Fogginess', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason12">Mental Fogginess</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason13" name="reason_referral[]" value="Anxiety Disorder" 
                                        @if (in_array('Anxiety Disorder', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason13">Anxiety Disorder</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason14" name="reason_referral[]" value="Mood Swings" 
                                        @if (in_array('Mood Swings', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason14">Mood Swings</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason15" name="reason_referral[]" value="Abnormal CT/MRI of Brain" 
                                        @if (in_array('Abnormal CT/MRI of Brain', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason15">Abnormal CT/MRI of Brain</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason16" name="reason_referral[]" value="Sluggishness/Lethargy.Fatigue" 
                                        @if (in_array('Sluggishness/Lethargy.Fatigue', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason16">Sluggishness/Lethargy.Fatigue</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason17" name="reason_referral[]" value="Neck Pain" 
                                        @if (in_array('Neck Pain', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason16">Neck Pain</label>
                                    </div>
        
                                    <div class="form-check col-md-4 mb-10">
                                        <input class="form-check-input" type="checkbox" id="reason18" name="reason_referral[]" value="Other" 
                                        @if (in_array('Other', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason16">Other</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <h4 class="card-title text-primary">Patient's Insurance Info</h4>
                    </div> 
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_company">Patient's Insurance Company </label>
                                <input type="text" id="patient_insurance_company" name="patient_insurance_company" value="{{ $data->patient_insurance_company }}" class="form-control" />
                            </div>
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_policy">Patient's Policy</label>
                                <input type="text" id="patient_insurance_policy" name="patient_insurance_policy" value="{{ $data->patient_insurance_company }}" class="form-control"  />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_street_adderss">Patient's Insurance Street Address</label>
                                <input type="text" id="patient_insurance_street_adderss" name="patient_insurance_street_adderss" value="{{ $data->patient_insurance_street_adderss }}"  class="form-control" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_street_adderss_line2">Patient's Insurance Street Address Line2</label>
                                <input type="text" id="patient_insurance_street_adderss_line2" name="patient_insurance_street_adderss_line2" value="{{ $data->patient_insurance_street_adderss_line2 }}"  class="form-control" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_city">Patient Insurance City</label>
                                <input type="text" id="patient_insurance_city" name="patient_insurance_city" value="{{ $data->patient_insurance_city }}"  class="form-control" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_state">Patient Insurance State/Province</label>
                                <input type="text" id="patient_insurance_state" name="patient_insurance_state" value="{{ $data->patient_insurance_state }}"  class="form-control" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_postal">Patient Insurance Postal/Zip Code</label>
                                <input type="text" id="patient_insurance_postal" name="patient_insurance_postal" value="{{ $data->patient_insurance_postal }}"  class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <h4 class="card-title text-primary">Defendant's Insurance Info</h4>
                    </div> 
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="vertical-address">Is This A Hit & Run? </label>      
                                <br>                  
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="defendant_hit_yes" name="defendant_insurance_hit" value="Yes"
                                    @if ($data->defendant_insurance_hit == 'Yes') checked @endif />
                                    <label class="form-check-label" for="defendant_hit_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="defendant_hit_no" name="defendant_insurance_hit" value="No" 
                                    @if ($data->defendant_insurance_hit == 'No') checked @endif />
                                    <label class="form-check-label" for="defendant_hit_no">No</label>
                                </div>                                           
                            </div>
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="vertical-address">Is Defendant Insured?</label>
                                <div class="demo-inline-spacing">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="defendant_insure" id="defendant_insure_yes" value="yes" 
                                        @if ($data->defendant_insure == 'yes') checked @endif />
                                        <label class="form-check-label" for="male">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="defendant_insure" id="defendant_insure_no" value="no"
                                        @if ($data->defendant_insure == 'no') checked @endif />
                                        <label class="form-check-label" for="female">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-address">Defendant's Insurance Company</label>
                                <input type="text" id="defendant_insurance_company" name="defendant_insurance_company" class="form-control" value="{{ $data->defendant_insurance_company }}" />
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-address">Claim #</label>
                                <input type="text" id="defendant_insurance_claim" name="defendant_insurance_claim" class="form-control" value="{{ $data->defendant_insurance_claim }}"  />
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-address">Defendant Policy Limit</label>
                                <input type="text" id="defendant_policy_limit" name="defendant_policy_limit" class="form-control" value="{{ $data->defendant_policy_limit }}" />
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_street_adderss">Defendant Insurance Company Address</label>
                                <input type="text" id="defendant_insurance_street_adderss" name="defendant_insurance_street_adderss" value="{{ $data->defendant_insurance_street_adderss }}" class="form-control" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_street_adderss_line2">Defendant Insurance Company Address Line2</label>
                                <input type="text" id="defendant_insurance_street_adderss_line2" name="defendant_insurance_street_adderss_line2" class="form-control" value="{{ $data->defendant_insurance_street_adderss_line2 }}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_city">Defendant Insurance City</label>
                                <input type="text" id="defendant_insurance_city" name="defendant_insurance_city" class="form-control" value="{{ $data->defendant_insurance_city }}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_state">Defendant Insurance State/Province</label>
                                <input type="text" id="defendant_insurance_state" name="defendant_insurance_state" class="form-control" value="{{ $data->defendant_insurance_state }}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_postal">Defendant Insurance Postal/Zip Code</label>
                                <input type="text" id="defendant_insurance_postal" name="defendant_insurance_postal" class="form-control" value="{{ $data->defendant_insurance_postal }}" />
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <h4 class="card-title text-primary">Patient's Attorney Info</h4>
                    </div> 
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="attorney_name">Attorney Name</label>
                                <input type="text" id="attorney_name" name="attorney_name" class="form-control" value="{{ $data->attorney->name }}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="attorney_email">Attorney Email</label>
                                <input type="text" id="attorney_email" name="attorney_email"  class="form-control" value="{{ $data->attorney->email }}"  />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="attorney_email">Attorney Phone</label>
                                <input type="text" id="attorney_phone" name="attorney_phone"  class="form-control phone-number-mask" value="{{ $data->attorney->phone }}"  />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_adderss">Law Firm Address</label>
                                <input type="text" id="law_firm_adderss" name="law_firm_adderss" class="form-control" value="{{ $data->attorney->address }}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_adderss_line2">Law Firm Address Line2</label>
                                <input type="text" id="law_firm_adderss_line2" name="law_firm_adderss_line2" class="form-control" value="{{ $data->attorney->address2 }}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_city">Law Firm City</label>
                                <input type="text" id="law_firm_city" name="law_firm_city" class="form-control" value="{{ $data->attorney->city }}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_state">Law Firm State/Province</label>
                                <input type="text" id="law_firm_state" name="law_firm_state" class="form-control" value="{{ $data->attorney->state }}" />
                            </div>
        
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_postal">Law Firm Postal/Zip Code</label>
                                <input type="text" id="law_firm_postal" name="law_firm_postal" class="form-control" value="{{ $data->attorney->postal }}" />
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <h4 class="card-title text-primary">Physician Info</h4>
                    </div> 
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-twitter">Clinic Name</label>
                                <select class="form-control" name="clinic_name">
                                    @foreach ($clinicData as $key => $val)
                                        <option value="{{$val->id}}" @if ($clinic_id == $val->id) selected @endif>{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>    
                            
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-twitter">Doctor Name</label>
                                <input type="text" id="doctor_name" name="doctor_name" class="form-control" value="{{ $data->doctor->name }}" />
                                <span>If not listen</span>
                            </div>
    
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-twitter">Doctor Email</label>
                                <input type="text" id="doctor_email" name="doctor_email" class="form-control" value="{{ $data->doctor->email }}" />
                            </div>
    
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-twitter">Doctor Phone</label>
                                <input type="text" id="doctor_phone" name="doctor_phone" class="form-control" value="{{ $data->doctor->phone }}" />
                            </div>
    
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="vertical-twitter">Misc Notes</label>
                                <textarea id="doctor_notes" name="doctor_notes" class="form-control" rows="3">{{ $data->doctor_notes }}</textarea>
                            </div>
    
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="vertical-twitter">LOP/Lien/Other Documents</label><br>
                               <input type="file" name="files[]" multiple />
                                <br><br>
                               <label class="form-label" for="vertical-twitter">Uploaded files</label><br>
                               <div class="table-responsive">
                                    <table class="table">
                                    @if($data->files)
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Action</th>                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <a href="{{ asset('storage/uploads/5uXDBSqwETaPpnaSRb5mNnwSiMkcIGRruqA2PXzv.docx') }}" target="_blank">test</a>
                                            {{-- @foreach ($data->files as $val)
                                                <tr>
                                                    <td>
                                                        <a href="{{ asset('storage/') }}"></a>
                                                    </td>
                                                    <td><a href="#" class="text-danger"><i data-feather='trash-2'></i></a></td>
                                                </tr>
                                            @endforeach --}}
                                        </tbody>
                                    @endif 
                                    </table>
                               </div>
                               
                               
                            </div>
                        </div>
                        <div class="mt-1 mb-1">                
                            <button type="submit" class="btn btn-success me-1 waves-effect waves-float waves-light">Update</button>
                            <a href={{route('referral.index')}} class="btn btn-primary waves-effect">Back</a>
                        </div> 
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
    
</section>
@endsection

@section('page-script')
    <script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>    
    <script src="{{ asset('app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>    
    <script src="{{ asset('app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>   
    <script src="{{ asset('app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>

    <script>
    //Referral Form
    $(document).ready(function() {
        //Date
        var flatpickrInstance = flatpickr(".flatpickr_date", {            
            readOnly: false
        });

        
        //Birthday
        var flatpickrInstance = flatpickr(".flatpickr_dates", {
        });
    });

    //Input Mask
    new Cleave($('.phone-number-mask'), {
        phone: true,
        phoneRegionCode: 'US'
    });

    new Cleave($('#attorney_phone'), {
        phone: true,
        phoneRegionCode: 'US'
    });

    new Cleave($('#doctor_phone'), {
        phone: true,
        phoneRegionCode: 'US'
    });
    </script>
@endsection