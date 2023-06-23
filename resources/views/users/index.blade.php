@extends('layouts.master')

@section('title', 'User Management')

@section('page-style')
@endsection


@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="user-list-table table">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $user)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-capitalize">
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                            {{ $v }}
                            @endforeach
                        @endif
                        </td>
                        <td>
                            <a class="btn btn-primary mr-1" href="{{ route('users.edit',$user->id) }}">Edit</a>
                                {!! Form::open(['method' => 'DELETE', 'class' => 'delete_form', 'route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

 <!-- Modal to add new user starts-->
 <div class="modal fade" id="user-modals" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add New User Information</h1>
                </div>

                <form action="{{ route('users.store') }}" class="add-new-user row gy-1 pt-75" method="POST">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">Username</label>
                        <input type="text" class="form-control name" id="name" placeholder="John Doe" name="name" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" class="form-control email" placeholder="john.doe@example.com" name="email" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email">phone</label>
                        <input type="text" id="phone" class="form-control phone-number-mask" name="phone" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email">Date of Birth</label>
                        <input type="text" id="date_of_birth" class="form-control date-mask" name="date_of_birth" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" class="form-control password" placeholder="*****" name="password" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" class="form-control confirm_password" placeholder="*****" name="confirm_password" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="adderss_line2">Address Line2</label>
                        <input type="text" id="address_line2" name="address_line2" class="form-control" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="city">City</label>
                        <input type="text" id="city" name="city" class="form-control" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="patient_state">State/Province</label>
                        <input type="text" id="state" name="state" class="form-control" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="postal">Patient Postal/Zip Code</label>
                        <input type="text" id="postal" name="postal" class="form-control" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="patient_gender">Gender</label>
                        <div class="demo-inline-spacing">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="male" checked />
                                <label class="form-check-label" for="inlineRadio1">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="female" />
                                <label class="form-check-label" for="inlineRadio2">Female</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <label class="form-label" for="clinic_id">Clinic</label>
                        <select disabled class="select2 form-select w-100 form-control" id="clinic_id" name="clinic_id">
                            <option value=""></option>
                            @foreach ($clinics as $item)
                                <option value={{$item->id}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="user-role">User Role</label>
                        <select id="roles" name="roles" class="select2 form-select" multiple style="height:120px;">
                            @foreach ($roles as $role)
                                <option value="{{$role}}">{{$role}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Save</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Discard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal to add new user Ends-->
@endsection

@section('page-script')
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>

<script>
    $(document).ready(function(){
        new Cleave($('.phone-number-mask'), {
            phone: true,
            phoneRegionCode: 'US'
        });

        new Cleave($('.date-mask'), {
            date: true,
            delimiter: '-',
            datePattern: ['Y', 'm', 'd']
        });

        $('#roles').change(function(){
            if ($(this).val()[0] == "office manager" || $(this).val()[0] == "doctor"){
                $('#clinic_id').attr('disabled', false);
            } else {
                $('#clinic_id').attr('disabled', true);
                $('#clinic_id').val('');
            }
        });
    });

</script>
@endsection
