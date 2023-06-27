@extends('layouts.master')

@section('title', 'Patient Edit')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/form-file-uploader.css') }}">   --}}
@endsection
<?php
    $is_pending = ($data->status == config('const.status_code.Pending') || $data->status == config('const.status_code.Draft'));
    $is_booked_status = ($data->status == config('const.status_code.Booked'));

    $attorneies = [];
    $doctors = [];
    foreach($attorneys as $item){
        $attorneies[$item->id] = [
            'id' => $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'phone' => $item->phone,
            'date_of_birth' => $item->date_of_birth,
            'address' => $item->address,
            'address_line2' => $item->address_line2,
            'city' => $item->city,
            'state' => $item->state,
            'postal' => $item->postal,
        ];
    };

    foreach($doctorData as $item){
        $doctors[$item->id] = [
            'id' => $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'phone' => $item->phone,
            'date_of_birth' => $item->date_of_birth,
            'address' => $item->address,
            'address_line2' => $item->address_line2,
            'city' => $item->city,
            'state' => $item->state,
            'postal' => $item->postal,
        ];
    };
?>
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
                                @if($is_pending)
                                    <input type="text" id="referral_date" name="referral_date" value="{{$data->referral_date}}" class="form-control flatpickr_date" />
                                @else
                                    <input disabled type="text" id="referral_date" name="referral_date" value="{{$data->referral_date}}" class="form-control flatpickr_date" />
                                @endif
                            </div>
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="patient_name">Status</label>
                                <div class="stepper-wrapper">
                                    @foreach (config('const.status') as $key => $val)
                                        <div class="<?php echo ($data->status >= $key ? "completed" : ($data->status + 1 == $key ? "active" :""))." stepper-item" ?>">
                                            <div class="step-counter" style="color:white;">{{$key + 1}}</div>
                                            <div class="step-name">{{$val}}</div>
                                        </div>
                                    @endforeach
                                </div>
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
                                @if($is_pending)
                                    <input type="text" id="patient_name----" name="patient_name" class="form-control"  value= "{{ $data->patient->name}}" />
                                @else
                                    <input disabled type="text" id="patient_name" name="patient_name" class="form-control"  value= "{{ $data->patient->name}}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_email">Patient Email</label>
                                @if($is_pending)
                                    <input type="email" id="patient_email" name="patient_email" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe" value={{$data->patient->email}} />
                                @else
                                    <input disabled type="email" id="patient_email" name="patient_email" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe" value={{$data->patient->email}} />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_phone">Patient phone</label>
                                @if($is_pending)
                                    <input type="text" id="patient_phone" name="patient_phone" class="form-control phone-number-mask" placeholder=""  value="{{$data->patient->phone}}" />
                                @else
                                    <input disabled type="text" id="patient_phone" name="patient_phone" class="form-control phone-number-mask" placeholder=""  value="{{$data->patient->phone}}" />
                                @endif
                            </div>
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_date_birth">Date of Birth</label>
                                @if($is_pending)
                                    <input type="text" id="patient_date_birth" name="patient_date_birth" class="form-control flatpickr_dates"  value="{{$data->patient->date_of_birth}}" />
                                @else
                                    <input disabled type="text" id="patient_date_birth" name="patient_date_birth" class="form-control flatpickr_dates"  value="{{$data->patient->date_of_birth}}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_street_adderss">Patient Street Address</label>
                                @if($is_pending)
                                    <input type="text" id="patient_street_adderss" name="patient_street_adderss" class="form-control" value="{{$data->patient->address}}"  />
                                @else
                                    <input disabled type="text" id="patient_street_adderss" name="patient_street_adderss" class="form-control" value="{{$data->patient->address}}"  />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_street_adderss_line2">Patient Street Address Line2</label>
                                @if($is_pending)
                                    <input type="text" id="patient_street_adderss_line2" name="patient_street_adderss_line2" class="form-control" value="{{$data->patient->address_line2}}" />
                                @else
                                    <input disabled type="text" id="patient_street_adderss_line2" name="patient_street_adderss_line2" class="form-control" value="{{$data->patient->address_line2}}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_city">Patient City</label>
                                @if($is_pending)
                                    <input type="text" id="patient_city" name="patient_city" class="form-control" value="{{$data->patient->city}}" />
                                @else
                                    <input disabled type="text" id="patient_city" name="patient_city" class="form-control" value="{{$data->patient->city}}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_city">Patient State/Province</label>
                                @if($is_pending)
                                    <input type="text" id="patient_state" name="patient_state" class="form-control" value="{{$data->patient->state}}" />
                                @else
                                    <input disabled type="text" id="patient_state" name="patient_state" class="form-control" value="{{$data->patient->state}}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_city">Patient Postal/Zip Code</label>
                                @if($is_pending)
                                    <input type="text" id="patient_postal" name="patient_postal" class="form-control" value="{{$data->patient->postal}}" />
                                @else
                                    <input disabled type="text" id="patient_postal" name="patient_postal" class="form-control" value="{{$data->patient->postal}}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_date_injury">Date of Injury</label>
                                @if($is_pending)
                                    <input type="text" id="patient_date_injury" name="patient_date_injury" value="{{ $data->patient_date_injury }}" class="form-control flatpickr_dates" />
                                @else
                                    <input disabled type="text" id="patient_date_injury" name="patient_date_injury" value="{{ $data->patient_date_injury }}" class="form-control flatpickr_dates" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_gender">Gender</label>
                                <div class="demo-inline-spacing">
                                    <div class="form-check form-check-inline">
                                        @if($is_pending)
                                            <input class="form-check-input" type="radio" name="genders" id="inlineRadio1" value="male"
                                        @else
                                            <input disabled class="form-check-input" type="radio" name="genders" id="inlineRadio1" value="male"
                                        @endif
                                        @if ($data->patient->gender == 'male') checked @endif />
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        @if($is_pending)
                                            <input class="form-check-input" type="radio" name="genders" id="inlineRadio2" value="female"
                                        @else
                                            <input disabled class="form-check-input" type="radio" name="genders" id="inlineRadio2" value="female"
                                        @endif
                                        @if ($data->patient->gender == 'female') checked @endif />
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
                                </div>
                            </div>
                            @php
                                $reason_referral = explode(',', $data->reason_referral);
                            @endphp
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="reason">Reason for Referral</label>
                                <div class="row reason_row">
                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason1" name="reason_referral[]" value="Headaches/Migraines"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason1" name="reason_referral[]" value="Headaches/Migraines"
                                        @endif
                                        @if (in_array('Headaches/Migraines', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason1">Headaches/Migraines</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason2" name="reason_referral[]" value="Memory and/or Concentration Problems"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason2" name="reason_referral[]" value="Memory and/or Concentration Problems"
                                        @endif

                                        @if (in_array('Memory and/or Concentration Problems', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason2">Memory and/or Concentration Problems</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason3" name="reason_referral[]" value="Inability to Focus/Attention Problems"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason3" name="reason_referral[]" value="Inability to Focus/Attention Problems"
                                        @endif
                                        @if (in_array('Inability to Focus/Attention Problems', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason3">Inability to Focus/Attention Problems</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason4" name="reason_referral[]" value="Blurry/Double Vision"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason4" name="reason_referral[]" value="Blurry/Double Vision"
                                        @endif
                                        @if (in_array('Blurry/Double Vision', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason4">Blurry/Double Vision</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason5" name="reason_referral[]" value="Depression"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason5" name="reason_referral[]" value="Depression"
                                        @endif
                                        @if (in_array('Depression', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason5">Depression</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason6" name="reason_referral[]" value="Personality Changes"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason6" name="reason_referral[]" value="Personality Changes"
                                        @endif
                                        @if (in_array('Personality Changes', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason6">Personality Changes</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason7" name="reason_referral[]" value="Brain Bleed/Swelling"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason7" name="reason_referral[]" value="Brain Bleed/Swelling"
                                        @endif
                                        @if (in_array('Brain Bleed/Swelling', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason7">Brain Bleed/Swelling</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason8" name="reason_referral[]" value="PTSD"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason8" name="reason_referral[]" value="PTSD"
                                        @endif
                                        @if (in_array('PTSD', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason8">PTSD</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason9" name="reason_referral[]" value="Sensitivity to Light or Noise"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason9" name="reason_referral[]" value="Sensitivity to Light or Noise"
                                        @endif
                                        @if (in_array('Sensitivity to Light or Noise', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason9">Sensitivity to Light or Noise</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason10" name="reason_referral[]" value="Dizziness/Balance Problems/Ringing in Ears"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason10" name="reason_referral[]" value="Dizziness/Balance Problems/Ringing in Ears"
                                        @endif
                                        @if (in_array('Dizziness/Balance Problems/Ringing in Ears', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason10">Dizziness/Balance Problems/Ringing in Ears</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason11" name="reason_referral[]" value="Alteration of Speech/Abnormal Speech"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason11" name="reason_referral[]" value="Alteration of Speech/Abnormal Speech"
                                        @endif
                                        @if (in_array('Alteration of Speech/Abnormal Speech', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason11">Alteration of Speech/Abnormal Speech</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason12" name="reason_referral[]" value="Mental Fogginess"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason12" name="reason_referral[]" value="Mental Fogginess"
                                        @endif
                                        @if (in_array('Mental Fogginess', $reason_referral, true)) checked @endif />
                                        <label class="form-check-label" for="reason12">Mental Fogginess</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason13" name="reason_referral[]" value="Anxiety Disorder"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason13" name="reason_referral[]" value="Anxiety Disorder"
                                        @endif
                                        @if (in_array('Anxiety Disorder', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason13">Anxiety Disorder</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason14" name="reason_referral[]" value="Mood Swings"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason14" name="reason_referral[]" value="Mood Swings"
                                        @endif
                                        @if (in_array('Mood Swings', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason14">Mood Swings</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason15" name="reason_referral[]" value="Abnormal CT/MRI of Brain"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason15" name="reason_referral[]" value="Abnormal CT/MRI of Brain"
                                        @endif
                                        @if (in_array('Abnormal CT/MRI of Brain', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason15">Abnormal CT/MRI of Brain</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason16" name="reason_referral[]" value="Sluggishness/Lethargy.Fatigue"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason16" name="reason_referral[]" value="Sluggishness/Lethargy.Fatigue"
                                        @endif
                                        @if (in_array('Sluggishness/Lethargy.Fatigue', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason16">Sluggishness/Lethargy.Fatigue</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason17" name="reason_referral[]" value="Neck Pain"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason17" name="reason_referral[]" value="Neck Pain"
                                        @endif
                                        @if (in_array('Neck Pain', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason17">Neck Pain</label>
                                    </div>

                                    <div class="form-check col-md-4 mb-10">
                                        @if($is_pending)
                                            <input class="form-check-input" type="checkbox" id="reason18" name="reason_referral[]" value="Other"
                                        @else
                                            <input disabled class="form-check-input" type="checkbox" id="reason18" name="reason_referral[]" value="Other"
                                        @endif
                                        @if (in_array('Other', $reason_referral, true)) checked @endif  />
                                        <label class="form-check-label" for="reason18">Other</label>
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
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="vertical-address">Is Patient Insured?</label>
                                <div class="demo-inline-spacing">
                                    <div class="form-check form-check-inline">
                                        @if($is_pending)
                                            <input class="form-check-input" type="radio" name="patient_insurance" id="patient_insure_yes" value="yes"
                                        @else
                                            <input disabled class="form-check-input" type="radio" name="patient_insurance" id="patient_insure_yes" value="yes"
                                        @endif
                                        @if ($data->patient_insurance == 'yes') checked @endif />
                                        <label class="form-check-label" for="male">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        @if($is_pending)
                                            <input class="form-check-input" type="radio" name="patient_insurance" id="patient_insure_no" value="no"
                                        @else
                                            <input disabled class="form-check-input" type="radio" name="patient_insurance" id="patient_insure_no" value="no"
                                        @endif
                                        @if ($data->patient_insurance == 'no') checked @endif />
                                        <label class="form-check-label" for="female">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_company">Patient's Insurance Company </label>
                                @if($is_pending && $data->patient_insurance == 'yes')
                                    <input type="text" id="patient_insurance_company" name="patient_insurance_company" value="{{ $data->patient_insurance_company }}" class="form-control" />
                                @else
                                    <input disabled type="text" id="patient_insurance_company" name="patient_insurance_company" value="{{ $data->patient_insurance_company }}" class="form-control" />
                                @endif
                            </div>
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_policy">Patient Policy Number</label>
                                @if($is_pending && $data->patient_insurance == 'yes')
                                    <input type="text" id="patient_insurance_policy" name="patient_insurance_policy" value="{{ $data->patient_insurance_policy }}" class="form-control"  />
                                @else
                                    <input disabled type="text" id="patient_insurance_policy" name="patient_insurance_policy" value="{{ $data->patient_insurance_policy }}" class="form-control"  />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_policy_limit">Patient Policy Limit</label>
                                @if($is_pending && $data->patient_insurance == 'yes')
                                    <input type="number" id="patient_policy_limit" name="patient_policy_limit" class="form-control"
                                        value="{{ $data->patient_policy_limit }}" placeholder="N/A" />
                                @else
                                    <input disabled type="number" id="patient_policy_limit" name="patient_policy_limit" class="form-control"
                                        value="{{ $data->patient_policy_limit }}" placeholder="N/A" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_street_adderss">Patient's Insurance Street Address</label>
                                @if($is_pending && $data->patient_insurance == 'yes')
                                    <input type="text" id="patient_insurance_street_adderss" name="patient_insurance_street_adderss" value="{{ $data->patient_insurance_street_adderss }}"  class="form-control" />
                                @else
                                    <input disabled type="text" id="patient_insurance_street_adderss" name="patient_insurance_street_adderss" value="{{ $data->patient_insurance_street_adderss }}"  class="form-control" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_street_adderss_line2">Street Address Line2</label>
                                @if($is_pending && $data->patient_insurance == 'yes')
                                    <input type="text" id="patient_insurance_street_adderss_line2" name="patient_insurance_street_adderss_line2" value="{{ $data->patient_insurance_street_adderss_line2 }}"  class="form-control" />
                                @else
                                    <input disabled type="text" id="patient_insurance_street_adderss_line2" name="patient_insurance_street_adderss_line2" value="{{ $data->patient_insurance_street_adderss_line2 }}"  class="form-control" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_city">Patient Insurance City</label>
                                @if($is_pending && $data->patient_insurance == 'yes')
                                    <input type="text" id="patient_insurance_city" name="patient_insurance_city" value="{{ $data->patient_insurance_city }}"  class="form-control" />
                                @else
                                    <input disabled type="text" id="patient_insurance_city" name="patient_insurance_city" value="{{ $data->patient_insurance_city }}"  class="form-control" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_state">Patient Insurance State/Province</label>
                                @if($is_pending && $data->patient_insurance == 'yes')
                                    <input type="text" id="patient_insurance_state" name="patient_insurance_state" value="{{ $data->patient_insurance_state }}"  class="form-control" />
                                @else
                                    <input disabled type="text" id="patient_insurance_state" name="patient_insurance_state" value="{{ $data->patient_insurance_state }}"  class="form-control" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_postal">Patient Insurance Postal/Zip Code</label>
                                @if($is_pending && $data->patient_insurance == 'yes')
                                    <input type="text" id="patient_insurance_postal" name="patient_insurance_postal" value="{{ $data->patient_insurance_postal }}"  class="form-control" />
                                @else
                                    <input disabled type="text" id="patient_insurance_postal" name="patient_insurance_postal" value="{{ $data->patient_insurance_postal }}"  class="form-control" />
                                @endif
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
                                    @if($is_pending)
                                        <input class="form-check-input" type="radio" id="defendant_hit_yes" name="defendant_insurance_hit" value="Yes"
                                    @else
                                        <input disabled class="form-check-input" type="radio" id="defendant_hit_yes" name="defendant_insurance_hit" value="Yes"
                                    @endif
                                    @if ($data->defendant_insurance_hit == 'Yes') checked @endif />
                                    <label class="form-check-label" for="defendant_hit_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    @if($is_pending)
                                        <input class="form-check-input" type="radio" id="defendant_hit_no" name="defendant_insurance_hit" value="No"
                                    @else
                                        <input disabled class="form-check-input" type="radio" id="defendant_hit_no" name="defendant_insurance_hit" value="No"
                                    @endif
                                    @if ($data->defendant_insurance_hit == 'No') checked @endif />
                                    <label class="form-check-label" for="defendant_hit_no">No</label>
                                </div>
                            </div>
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="vertical-address">Is Defendant Insured?</label>
                                <div class="demo-inline-spacing">
                                    <div class="form-check form-check-inline">
                                        @if($is_pending)
                                            <input class="form-check-input" type="radio" name="defendant_insure" id="defendant_insure_yes" value="yes"
                                        @else
                                            <input disabled class="form-check-input" type="radio" name="defendant_insure" id="defendant_insure_yes" value="yes"
                                        @endif
                                        @if ($data->defendant_insure == 'yes') checked @endif />
                                        <label class="form-check-label" for="male">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        @if($is_pending)
                                            <input class="form-check-input" type="radio" name="defendant_insure" id="defendant_insure_no" value="no"
                                        @else
                                            <input disabled class="form-check-input" type="radio" name="defendant_insure" id="defendant_insure_no" value="no"
                                        @endif
                                        @if ($data->defendant_insure == 'no') checked @endif />
                                        <label class="form-check-label" for="female">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-address">Defendant's Insurance Company</label>
                                @if($is_pending && $data->defendant_insure == 'yes')
                                    <input type="text" id="defendant_insurance_company" name="defendant_insurance_company" class="form-control" value="{{ $data->defendant_insurance_company }}" />
                                @else
                                    <input disabled type="text" id="defendant_insurance_company" name="defendant_insurance_company" class="form-control" value="{{ $data->defendant_insurance_company }}" />
                                @endif
                            </div>
                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-address">Claim #</label>
                                @if($is_pending && $data->defendant_insure == 'yes')
                                    <input type="text" id="defendant_insurance_claim" name="defendant_insurance_claim" class="form-control" value="{{ $data->defendant_insurance_claim }}"  />
                                @else
                                    <input disabled type="text" id="defendant_insurance_claim" name="defendant_insurance_claim" class="form-control" value="{{ $data->defendant_insurance_claim }}"  />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-address">Defendant Policy Limit</label>
                                @if($is_pending && $data->defendant_insure == 'yes')
                                    <input type="number" id="defendant_policy_limit" name="defendant_policy_limit" class="form-control"
                                        value="{{ $data->defendant_policy_limit }}" placeholder="N/A"/>
                                @else
                                    <input disabled type="number" id="defendant_policy_limit" name="defendant_policy_limit" class="form-control"
                                        value="{{ $data->defendant_policy_limit }}" placeholder="N/A"/>
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_street_adderss">Defendant Insurance Address</label>
                                @if($is_pending && $data->defendant_insure == 'yes')
                                    <input type="text" id="defendant_insurance_street_adderss" name="defendant_insurance_street_adderss" value="{{ $data->defendant_insurance_street_adderss }}" class="form-control" />
                                @else
                                    <input disabled type="text" id="defendant_insurance_street_adderss" name="defendant_insurance_street_adderss" value="{{ $data->defendant_insurance_street_adderss }}" class="form-control" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_street_adderss_line2">Defendant Insurance Address Line2</label>
                                @if($is_pending && $data->defendant_insure == 'yes')
                                    <input type="text" id="defendant_insurance_street_adderss_line2" name="defendant_insurance_street_adderss_line2" class="form-control" value="{{ $data->defendant_insurance_street_adderss_line2 }}" />
                                @else
                                    <input disabled type="text" id="defendant_insurance_street_adderss_line2" name="defendant_insurance_street_adderss_line2" class="form-control" value="{{ $data->defendant_insurance_street_adderss_line2 }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_city">Defendant Insurance City</label>
                                @if($is_pending && $data->defendant_insure == 'yes')
                                    <input type="text" id="defendant_insurance_city" name="defendant_insurance_city" class="form-control" value="{{ $data->defendant_insurance_city }}" />
                                @else
                                    <input disabled type="text" id="defendant_insurance_city" name="defendant_insurance_city" class="form-control" value="{{ $data->defendant_insurance_city }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_state">Defendant Insurance State/Province</label>
                                @if($is_pending && $data->defendant_insure == 'yes')
                                    <input type="text" id="defendant_insurance_state" name="defendant_insurance_state" class="form-control" value="{{ $data->defendant_insurance_state }}" />
                                @else
                                    <input disabled type="text" id="defendant_insurance_state" name="defendant_insurance_state" class="form-control" value="{{ $data->defendant_insurance_state }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="patient_insurance_postal">Defendant Insurance Postal/Zip Code</label>
                                @if($is_pending && $data->defendant_insure == 'yes')
                                    <input type="text" id="defendant_insurance_postal" name="defendant_insurance_postal" class="form-control" value="{{ $data->defendant_insurance_postal }}" />
                                @else
                                    <input disabled type="text" id="defendant_insurance_postal" name="defendant_insurance_postal" class="form-control" value="{{ $data->defendant_insurance_postal }}" />
                                @endif
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
                                @if($is_pending)
                                    @if(count($attorneys) > 0)
                                        <select id="attorney_name" name="attorney_name" class="form-control">
                                            <option value=""></option>
                                            @foreach ($attorneys as $item)
                                                @if($item->id == $data->attorney->id)
                                                    <option selected id={{"attorney_".$item->id}} value="{{$item->name}}">{{$item->name}}</option>
                                                @else
                                                    <option id={{"attorney_".$item->id}} value="{{$item->name}}">{{$item->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input style="display: none;" disabled type="text" id="attorney_name_inputbox" name="attorney_name" class="form-control" value="{{ $data->attorney->name }}" />
                                        <input class="form-check-input" type="checkbox" id="attorney_switch_check" />
                                    @else
                                        <input id="attorney_name_inputbox" name="attorney_name" class="form-control" value="{{ $data->attorney->name }}" />
                                        <input disabled class="form-check-input" type="checkbox" id="attorney_switch_check" />
                                    @endif
                                    <label class="form-check-label" for="attorney_switch_check">If Not Listed</label>
                                @else
                                    <input disabled type="text" id="attorney_name" name="attorney_name" class="form-control" value="{{ $data->attorney->name }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="attorney_email">Attorney Email</label>
                                @if($is_pending)
                                    <input type="text" id="attorney_email" name="attorney_email"  class="form-control" value="{{ $data->attorney->email }}" />
                                @else
                                    <input disabled type="text" id="attorney_email" name="attorney_email"  class="form-control" value="{{ $data->attorney->email }}"  />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="attorney_email">Attorney Phone</label>
                                @if($is_pending)
                                    <input type="text" id="attorney_phone" name="attorney_phone"  class="form-control phone-number-mask" value="{{ $data->attorney->phone }}"  />
                                @else
                                    <input disabled type="text" id="attorney_phone" name="attorney_phone"  class="form-control phone-number-mask" value="{{ $data->attorney->phone }}"  />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_adderss">Law Firm Address</label>
                                @if($is_pending)
                                    <input type="text" id="law_firm_adderss" name="law_firm_adderss" class="form-control" value="{{ $data->attorney->address }}" />
                                @else
                                    <input disabled type="text" id="law_firm_adderss" name="law_firm_adderss" class="form-control" value="{{ $data->attorney->address }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_adderss_line2">Law Firm Address Line2</label>
                                @if($is_pending)
                                    <input type="text" id="law_firm_adderss_line2" name="law_firm_adderss_line2" class="form-control" value="{{ $data->attorney->address2 }}" />
                                @else
                                    <input disabled type="text" id="law_firm_adderss_line2" name="law_firm_adderss_line2" class="form-control" value="{{ $data->attorney->address2 }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_city">Law Firm City</label>
                                @if($is_pending)
                                    <input type="text" id="law_firm_city" name="law_firm_city" class="form-control" value="{{ $data->attorney->city }}" />
                                @else
                                    <input disabled type="text" id="law_firm_city" name="law_firm_city" class="form-control" value="{{ $data->attorney->city }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_state">Law Firm State/Province</label>
                                @if($is_pending)
                                    <input type="text" id="law_firm_state" name="law_firm_state" class="form-control" value="{{ $data->attorney->state }}" />
                                @else
                                    <input disabled type="text" id="law_firm_state" name="law_firm_state" class="form-control" value="{{ $data->attorney->state }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="law_firm_postal">Law Firm Postal/Zip Code</label>
                                @if($is_pending)
                                    <input type="text" id="law_firm_postal" name="law_firm_postal" class="form-control" value="{{ $data->attorney->postal }}" />
                                @else
                                    <input disabled type="text" id="law_firm_postal" name="law_firm_postal" class="form-control" value="{{ $data->attorney->postal }}" />
                                @endif
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
                                @if($is_pending)
                                    <select class="form-control" name="clinic_name" readonly>
                                @else
                                    <select class="form-control" name="clinic_name" disabled>
                                @endif
                                    @foreach ($clinicData as $key => $val)
                                        <option value="{{$val->id}}" @if ($clinic_id == $val->id) selected @endif>{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-twitter">Doctor Name</label>
                                @if($is_pending)
                                    @if(count($doctorData) > 0)
                                        <select class="form-control" name="doctor_name" id="doctor_name">
                                            <option value=""></option>
                                            @foreach ($doctorData as $doctor_item)
                                                @if($data->doctor->id == $doctor_item->id)
                                                    <option selected id={{"doctor_".$doctor_item->id}} value="{{$doctor_item->name}}">{{$doctor_item->name}}</option>
                                                @else
                                                    <option id={{"doctor_".$doctor_item->id}} value="{{$doctor_item->name}}">{{$doctor_item->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input style="display: none;" disabled type="text" id="doctor_name_inputbox" name="doctor_name" class="form-control" value="{{ $data->doctor->name }}" />
                                        <input class="form-check-input" type="checkbox" id="doctor_switch_check" />
                                    @else
                                        <input type="text" id="doctor_name_inputbox" name="doctor_name" class="form-control" value="{{ $data->doctor->name }}" />
                                        <input disabled class="form-check-input" type="checkbox" id="doctor_switch_check" />
                                    @endif
                                    <label class="form-check-label" for="doctor_switch_check">If Not Listed</label>
                                @else
                                    <input disabled type="text" id="doctor_name" name="doctor_name" class="form-control" value="{{ $data->doctor->name }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-twitter">Doctor Email</label>
                                @if($is_pending)
                                    <input type="text" id="doctor_email" name="doctor_email" class="form-control" value="{{ $data->doctor->email }}" />
                                @else
                                    <input disabled type="text" id="doctor_email" name="doctor_email" class="form-control" value="{{ $data->doctor->email }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-3">
                                <label class="form-label" for="vertical-twitter">Doctor Phone</label>
                                @if($is_pending)
                                    <input type="text" id="doctor_phone" name="doctor_phone" class="form-control" value="{{ $data->doctor->phone }}" />
                                @else
                                    <input disabled type="text" id="doctor_phone" name="doctor_phone" class="form-control" value="{{ $data->doctor->phone }}" />
                                @endif
                            </div>

                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="vertical-twitter">Misc Notes</label>
                                @if($is_pending)
                                    <textarea id="doctor_notes" name="doctor_notes" class="form-control" rows="3">{{ $data->doctor_notes }}</textarea>
                                @else
                                    <textarea disabled id="doctor_notes" name="doctor_notes" class="form-control" rows="3">{{ $data->doctor_notes }}</textarea>
                                @endif
                            </div>
                            @if($is_booked_status)
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="vertical-twitter">LOP/Lien/Other Documents</label><br>
                                <input type="file" name="files[]" accept="application/pdf"/>
                                <br><br>
                                <label class="form-label" for="vertical-twitter">Uploaded files</label><br>
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>File Name</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($data->referral_files)
                                        @foreach ($data->referral_files as $val)
                                            <tr>
                                                <td>Referral Doc</td>
                                                <td>
                                                    <a target="_blank" href="{{ asset('storage/referral/'.$val->referral_file) }}">{{$val->referral_file}}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if($data->files)
                                        @foreach ($data->files as $val)
                                            <tr>
                                                <td>Sign Doc</td>
                                                <td>
                                                    <a target="_blank" href="{{ asset('uploads/sign/'.$val->files) }}">{{$val->files}}</a>
                                                </td>
                                                {{-- <td>
                                                    <a href="#" class="text-danger btn_trash_file" id="trash_{{$val->id}}"><i data-feather='trash-2'></i></a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if($data->invoice_files)
                                        @foreach ($data->invoice_files as $val)
                                            <tr>
                                                <td>Invoice Doc</td>
                                                <td>
                                                    <a target="_blank" href="{{ asset('storage/invoice/'.$val->invoice_file) }}">{{$val->invoice_file}}</a>
                                                </td>
                                                {{-- <td>
                                                    <a href="#" class="text-danger btn_trash_file" id="trash_{{$val->id}}"><i data-feather='trash-2'></i></a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if($data->result_files)
                                        @foreach ($data->result_files as $val)
                                            <tr>
                                                <td>Result Doc</td>
                                                <td>
                                                    <a target="_blank" href="{{ asset('uploads/results/'.$val->result_file) }}">{{$val->result_file}}</a>
                                                </td>
                                                {{-- <td>
                                                    <a href="#" class="text-danger btn_trash_file" id="trash_{{$val->id}}"><i data-feather='trash-2'></i></a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-1 mb-1">
                            <input id="draft_flag" type="hidden" value="" name="draft_flag"/>
                            @if($data->status == config('const.status_code.Draft'))
                                <button onclick="saveDraft()" class="btn btn-info me-1">Save Draft</button>
                            @endif
                            <button onclick="save()" class="btn btn-success me-1 waves-effect waves-float waves-light">Update</button>
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
        function saveDraft(){
            $('#draft_flag').val('draft');
            var referralForm = $('#referralForm');
            referralForm.validate().destroy();
            referralForm.validate({
                errorClass: 'error',
                rules:{
                    'patient_name': {
                        required: true
                    },
                    'patient_email': {
                        required:true,
                        email: true
                    },
                    'attorney_name': {
                        required: true
                    },
                    'attorney_email': {
                        required:true,
                        email:true
                    },
                    'doctor_name':{
                        required:true,
                    },
                    'doctor_email': {
                        required:true,
                        email:true
                    },
                    'patient_date_birth': {
                        date: true
                    },
                    'patient_date_injury': {
                        date: true
                    },
                }
            });
            $('#referralForm').submit();
        }

        function save(){
            $('#draft_flag').val('');
            var referralForm = $('#referralForm');
            referralForm.validate().destroy();
            referralForm.validate({
                errorClass: 'error',
                rules: {
                    'patient_name': {
                    required: true
                    },
                    'patient_email': {
                    required: true,
                    email: true
                    },
                    'patient_phone': {
                    required: true
                    },
                    'patient_date_birth': {
                    required: true,
                    date: true
                    },
                    'patient_street_adderss': {
                    required: true
                    },
                    'patient_city': {
                    required: true
                    },
                    'patient_state': {
                    required: true
                    },
                    'patient_postal': {
                    required: true
                    },
                    'patient_date_injury': {
                    required: true,
                    date: true
                    },
                    'reason_referral': {
                    required: true
                    },
                    'patient_insurance_company': {
                    required: true
                    },
                    'patient_insurance_policy': {
                    required: true
                    },
                    'patient_policy_limit': {
                    required: false
                    },
                    'patient_insurance_street_adderss': {
                    required: true
                    },
                    'patient_insurance_city': {
                    required: true
                    },
                    'patient_insurance_state': {
                    required: true
                    },
                    'patient_insurance_postal': {
                    required: true
                    },
                    'defendant_insurance_company': {
                    required: true
                    },
                    'defendant_insurance_claim': {
                    required: true
                    },
                    'defendant_policy_limit': {
                    required: false
                    },
                    'defendant_insurance_street_adderss': {
                    required: true
                    },
                    'defendant_insurance_city': {
                    required: true
                    },
                    'defendant_insurance_state': {
                    required: true
                    },
                    'defendant_insurance_postal': {
                    required: true
                    },
                    'attorney_name': {
                    required: true
                    },
                    'attorney_email': {
                    required: true,
                    email:true
                    },
                    'attorney_phone': {
                    required: true
                    },
                    'law_firm_adderss': {
                    required: true
                    },
                    'law_firm_city': {
                    required: true
                    },
                    'law_firm_state': {
                    required: true
                    },
                    'law_firm_postal': {
                    required: true
                    },
                    'doctor_name':{
                    required:true,
                    },
                    'doctor_email': {
                    required: true,
                    email:true
                    },
                    'doctor_phone': {
                    required: true
                    }
                }
            });
            $('#referralForm').submit();
        }

    var attorney_numbers = "<?php echo count($attorneies);?>";
    var doctor_numbers = "<?php echo count($doctors);?>";

    var attorneies = <?php echo json_encode($attorneies);?>;
    var doctors = <?php echo json_encode($doctors);?>;

    //Referral Form
    $(document).ready(function() {
        //Date
        var flatpickrInstance = flatpickr(".flatpickr_date", {
            readOnly: false
        });


        //Birthday
        var flatpickrInstance = flatpickr(".flatpickr_dates", {
        });

        $('#doctor_name').change(function(){
            if (parseInt(doctor_numbers) > 0){
                if ($(this).val() != ''){
                    var id = $(this).children(":selected").attr("id");
                    id = id.replace("doctor_", "");
                    id = parseInt(id);
                    $('#doctor_email').val(doctors[id].email);
                    $('#doctor_phone').val(doctors[id].phone);
                } else {
                    $('#doctor_email').val('');
                    $('#doctor_phone').val('');
                }
            }
        });

        $('#doctor_switch_check').change(function(){
                $('#doctor_name').val('');
                $('#doctor_email').val('');
                $('#doctor_phone').val('');
                $('#doctor_name_inputbox').val('');
                if (this.checked){
                    $('#doctor_name').hide();
                    $('#doctor_name').attr('disabled', true);
                    $('#doctor_name_inputbox').show();
                    $('#doctor_name_inputbox').attr('disabled', false);
                } else {
                    $('#doctor_name').show();
                    $('#doctor_name').attr('disabled', false);
                    $('#doctor_name_inputbox').hide();
                    $('#doctor_name_inputbox').attr('disabled', true);
                }
            });

        $('#attorney_name').change(function(){
            if (parseInt(attorney_numbers) > 0){
                if ($(this).val() != ''){
                    var id = $(this).children(":selected").attr("id");
                    id = id.replace("attorney_", "");
                    id = parseInt(id);

                    $('#attorney_email').val(attorneies[id].email);
                    $('#attorney_phone').val(attorneies[id].phone);
                    $('#law_firm_adderss').val(attorneies[id].address);
                    $('#law_firm_adderss_line2').val(attorneies[id].address_line2);
                    $('#law_firm_city').val(attorneies[id].city);
                    $('#law_firm_state').val(attorneies[id].state);
                    $('#law_firm_postal').val(attorneies[id].postal);
                } else {
                    $('#attorney_email').val('');
                    $('#attorney_phone').val('');
                    $('#law_firm_adderss').val('');
                    $('#law_firm_adderss_line2').val('');
                    $('#law_firm_city').val('');
                    $('#law_firm_state').val('');
                    $('#law_firm_postal').val('');
                }
            }
        });

        $('#attorney_switch_check').change(function(){
            $('#attorney_name').val('');
            $('#attorney_name_inputbox').val('');
            $('#attorney_email').val('');
            $('#attorney_phone').val('');
            $('#law_firm_adderss').val('');
            $('#law_firm_adderss_line2').val('');
            $('#law_firm_city').val('');
            $('#law_firm_state').val('');
            $('#law_firm_postal').val('');
            if (this.checked){
                $('#attorney_name').hide();
                $('#attorney_name').attr('disabled', true);
                $('#attorney_name_inputbox').show();
                $('#attorney_name_inputbox').attr('disabled', false);
            } else {
                $('#attorney_name').show();
                $('#attorney_name').attr('disabled', false);
                $('#attorney_name_inputbox').hide();
                $('#attorney_name_inputbox').attr('disabled', true);
            }
        });

        $('input[type=radio][name=patient_insurance]').change(function() {
            if (this.value == 'yes') {
                $('#patient_insurance_company').attr('disabled', false);
                $('#patient_insurance_policy').attr('disabled', false);
                $('#patient_policy_limit').attr('disabled', false);
                $('#patient_insurance_street_adderss').attr('disabled', false);
                $('#patient_insurance_street_adderss_line2').attr('disabled', false);
                $('#patient_insurance_city').attr('disabled', false);
                $('#patient_insurance_state').attr('disabled', false);
                $('#patient_insurance_postal').attr('disabled', false);
            }
            else if (this.value == 'no') {
                $('#patient_insurance_company').attr('disabled', true);
                $('#patient_insurance_policy').attr('disabled', true);
                $('#patient_policy_limit').attr('disabled', true);
                $('#patient_insurance_street_adderss').attr('disabled', true);
                $('#patient_insurance_street_adderss_line2').attr('disabled', true);
                $('#patient_insurance_city').attr('disabled', true);
                $('#patient_insurance_state').attr('disabled', true);
                $('#patient_insurance_postal').attr('disabled', true);

                $('#patient_insurance_company').val('');
                $('#patient_insurance_policy').val('');
                $('#patient_policy_limit').val('');
                $('#patient_insurance_street_adderss').val('');
                $('#patient_insurance_street_adderss_line2').val('');
                $('#patient_insurance_city').val('');
                $('#patient_insurance_state').val('');
                $('#patient_insurance_postal').val('');
            }
        });

        $('input[type=radio][name=defendant_insure]').change(function() {
            if (this.value == 'yes') {
                $('#defendant_insurance_company').attr('disabled', false);
                $('#defendant_insurance_claim').attr('disabled', false);
                $('#defendant_policy_limit').attr('disabled', false);
                $('#defendant_insurance_street_adderss').attr('disabled', false);
                $('#defendant_insurance_street_adderss_line2').attr('disabled', false);
                $('#defendant_insurance_city').attr('disabled', false);
                $('#defendant_insurance_state').attr('disabled', false);
                $('#defendant_insurance_postal').attr('disabled', false);
            }
            else if (this.value == 'no') {
                $('#defendant_insurance_company').attr('disabled', true);
                $('#defendant_insurance_claim').attr('disabled', true);
                $('#defendant_policy_limit').attr('disabled', true);
                $('#defendant_insurance_street_adderss').attr('disabled', true);
                $('#defendant_insurance_street_adderss_line2').attr('disabled', true);
                $('#defendant_insurance_city').attr('disabled', true);
                $('#defendant_insurance_state').attr('disabled', true);
                $('#defendant_insurance_postal').attr('disabled', true);

                $('#defendant_insurance_company').val('');
                $('#defendant_insurance_claim').val('');
                $('#defendant_policy_limit').val('');
                $('#defendant_insurance_street_adderss').val('');
                $('#defendant_insurance_street_adderss_line2').val('');
                $('#defendant_insurance_city').val('');
                $('#defendant_insurance_state').val('');
                $('#defendant_insurance_postal').val('');
            }
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

    $('.btn_trash_file').click(function (){
        let trash_id = $(this).attr('id').split('_')[1];
        $.ajax({
            type: "DELETE",
            url: "/delete-referral-file/"+trash_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if(response.msg == 'success'){
                    toastr.success( 'flash_success', 'Success!', { "showDuration": 500, positionClass: 'toast-top-right' });
                    $(this).parent().remove();
                }
            }
        });
    })
    </script>
@endsection
