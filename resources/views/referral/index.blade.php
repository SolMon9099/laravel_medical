@extends('layouts.master')

@section('title', 'Patient Data Management')

@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="patients-referral-table table">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Patient Name</th>
                    <th>Patient Email</th>
                    <th>Attorney Name</th>
                    <th>Doctor Name</th>
                    <th>Clinic</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key=>$value)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{date('m-d-Y', strtotime($value->referral_date))}}</td>
                        <td>{{$value->patient->name}}</td>
                        <td>{{$value->patient->email}}</td>
                        <td>{{$value->attorney->name}}</td>
                        <td>{{$value->doctor->name}}</td>
                        <td>{{isset($value->clinic_doctor) ? $value->clinic_doctor->clinic->name : ''}}</td>
                        <td>
                            <span class="{{config('const.status_class')[$value->status]}}">{{config('const.status')[$value->status]}}</span>
                        </td>
                        <td>
                            <div class="d-inline-flex">
                                <a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">
                                    <i data-feather='more-vertical' class="font-small-4"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('referral.edit', $value->id) }}" class="dropdown-item">
                                        <i data-feather="edit-2" class="me-50"></i>
                                        <span>Edit</span>
                                    </a>
                                    <a href="#" class="dropdown-item deleteBtn" data-id = {{$value->id}}>
                                        <i data-feather="trash" class="me-50"></i>
                                        <span>Delete</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('page-script')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {
        $('.page-item').click(function(){
            feather.replace();
        })
    })

    $(document).on('click', '.deleteBtn', function(e) {
        console.log('clicked');
        let id = $(this).data('id');
        var url = "{{ route('referral.destroy', ':id') }}".replace(':id', id);

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this record!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success( response.message, 'Success!', { "showDuration": 500, positionClass: 'toast-top-right' });
                        location.reload();
                    },
                    error: function(xhr) {
                        // Handle error response
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });

   
</script>
@endsection
