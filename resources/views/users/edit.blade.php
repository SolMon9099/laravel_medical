@extends('layouts.master')

@section('title', 'User Edit')

@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">  
@endsection

@section('content')
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Editing</h4>
                </div>

                <div class="card-body">                    
                    {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id], 'class' => 'user_edit_form']) !!}
                    <div class="row">

                        <div class="col-md-12">
                            @include('layouts.error')
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="name">Name</label>
                                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'required')) !!}                                
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="email">Email</label>
                                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="email">phone</label>
                            {!! Form::text('phone', null, array('placeholder' => 'phone','class' => 'form-control phone-number-mask')) !!}                            
                        </div>
    
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="email">Date of Birth</label>
                            {!! Form::text('date_of_birth', null, array('placeholder' => '','class' => 'form-control flatpickr_dates')) !!}    
                        </div>
     
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="password">Password</label>
                            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                        </div>
    
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="confirm_password">Confirm Password</label>
                            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                        </div>
    
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="adderss">Address</label>
                            {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control')) !!}    
                        </div>
    
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="adderss_line2">Address Line2</label>
                            {!! Form::text('address_line2', null, array('placeholder' => 'Address','class' => 'form-control')) !!}    
                        </div>
    
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="city">City</label>
                            {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control')) !!}  
                        </div>
    
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="patient_state">State/Province</label>
                            {!! Form::text('state', null, array('placeholder' => 'State','class' => 'form-control')) !!}                              
                        </div>
    
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="patient_insurance_postal">Patient Postal/Zip Code</label>
                            {!! Form::text('postal', null, array('placeholder' => 'Postal','class' => 'form-control')) !!}    
                        </div>
    
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label" for="patient_gender">Gender</label>
                            <div class="demo-inline-spacing">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="male" 
                                     @if($user->gender == 'male') checked @endif />
                                    <label class="form-check-label" for="inlineRadio1">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="female" 
                                    @if($user->gender == 'female') checked @endif/>
                                    <label class="form-check-label" for="inlineRadio2">Female</label>
                                </div>
                            </div>
                        </div>                     
                        <div class="col-12 mb-1 mb-md-0">
                            <label class="form-label" for="disabledInput">Roles</label>
                            {!! Form::select('roles[]', $roles, $userRole, array('class' => 'form-control','multiple')) !!}
                        </div>      
                        
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Save</button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary waves-effect">Back</a>
                        </div>
                        
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('page-script')
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>   
<script src="{{ asset('app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>    
<script src="{{ asset('app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>  

<script>
    new Cleave($('.phone-number-mask'), {
        phone: true,
        phoneRegionCode: 'US'
    });

     //Birthday
     var flatpickrInstance = flatpickr(".flatpickr_dates", {
    });

</script>
@endsection