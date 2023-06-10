@extends('layouts.master')

@section('title', 'Profile')

@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/form-file-uploader.css') }}">   --}}
@endsection

@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Profile Details</h4>
                </div>
                <div class="card-body py-2 my-25">
                    <!-- form -->
                    <form class="validate-form mt-2 pt-50" action="{{ route('profiles.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name"
                                    value="{{Auth::user()->name}}" data-msg="Please enter  your name" />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="gender">Gender</label>
                                <div class="demo-inline-spacing">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="male"
                                        @if (Auth::user()->gender == 'male') checked @endif />
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="female"
                                        @if (Auth::user()->gender == 'female') checked @endif />
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="date_of_birth">Date of Birth</label>
                                <input type="text" class="form-control flatpickr_dates" id="date_of_birth" name="date_of_birth" placeholder=""
                                    value="{{Auth::user()->date_of_birth}}" data-msg="Please enter your birthday"/>
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountEmail">Email</label>
                                <input type="email" class="form-control" id="accountEmail" name="email" placeholder="Email"
                                    value="{{Auth::user()->email}}" />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountPhoneNumber">Phone Number</label>
                                <input type="text" class="form-control account-number-mask" id="accountPhoneNumber" name="phone" placeholder="Phone Number"
                                    value="{{Auth::user()->phone}}" data-msg="Please enter  your phone number"/>
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountAddress">Address</label>
                                <input type="text" class="form-control" id="accountAddress" name="address" placeholder="Your Address"
                                    value="{{Auth::user()->address}}" data-msg="Please enter your address" />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountAddress2">Address Line2</label>
                                <input type="text" class="form-control" id="accountAddress2"
                                    name="address_line2" placeholder="Your Address2"
                                    value="{{Auth::user()->address_line2}}" />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountCity">City</label>
                                <input type="text" class="form-control" id="accountCity" name="city" placeholder="City"
                                    value="{{Auth::user()->city}}" data-msg="Please enter your city" />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountState">State/Province</label>
                                <input type="text" class="form-control" id="accountState" name="state" placeholder="State/Province"
                                    value="{{Auth::user()->state}}" data-msg="Please enter your State" />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountZipCode">Postal/Zip Code</label>
                                <input type="text" class="form-control account-zip-code" id="accountZipCode" name="postal" placeholder="Postal/Zip Code" maxlength="6"
                                    value="{{Auth::user()->postal}}" data-msg="Please enter your Postal"/>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
                            </div>
                        </div>
                    </form>
                    <!--/ form -->
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
    <script src="{{ asset('app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/pages/page-account-settings-account.js') }}"></script>

    <script>
        $(document).ready(function() {
            var flatpickrInstance = flatpickr(".flatpickr_dates", {
                altInput: true,
                allowInput: true
            });
        });
    </script>
@endsection

