@extends('layouts.master')

@section('title', 'Clinic Management')

@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="clinic-list-table table">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $value->name }}</td>
                        <td>
                            {{ $value->clinic_adderss }}
                            {{ $value->clinic_adderss_line2 }}
                            {{ $value->clinic_city }}
                            {{ $value->clinic_state }}
                            {{ $value->clinic_postal }}
                        </td>
                        <td>
                            <a class="btn btn-primary mr-1 edit-button" data-item-id = {{$value->id}}>Edit</a>
                                {!! Form::open(['method' => 'DELETE', 'class' => 'delete_form', 'route' => ['clinics.destroy', $value->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

 <!-- Modal to add new clinic starts-->
 <div class="modal fade" id="clinic-modals" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-clinic">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add New Clinic Information</h1>
                </div>

                <form action="{{ route('clinics.store') }}" class="add-new-clinic row gy-1 pt-75" method="POST">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control name" id="name" placeholder="John Doe" name="name" value="{{old('name')}}" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_adderss">Address</label>
                        <input type="text" id="clinic_adderss" name="clinic_adderss" class="form-control"  value="{{old('clinic_adderss')}}" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_adderss_line2">Address Line2</label>
                        <input type="text" id="clinic_adderss_line2" name="clinic_adderss_line2" class="form-control"  value="{{old('clinic_adderss_line2')}}" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_city">City</label>
                        <input type="text" id="clinic_city" name="clinic_city" class="form-control"  value="{{old('clinic_city')}}" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_state">State/Province</label>
                        <input type="text" id="clinic_state" name="clinic_state" class="form-control"  value="{{old('clinic_state')}}" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_postal">Patient Postal/Zip Code</label>
                        <input type="text" id="clinic_postal" name="clinic_postal" class="form-control"  value="{{old('clinic_postal')}}" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="technician_id">Technician</label>
                        <select class="select2 form-select w-100 form-control" name="technician_id">
                            <option value=""></option>
                            @foreach ($technicians as $item)
                                <option value={{$item->id}}>{{$item->name}}</option>
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

<!-- Modal to edit new clinic starts-->
<div class="modal fade" id="clinic-modals-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-clinic">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Edit Clinic Information</h1>
                </div>

                <form action="#" class="edit-clinic row gy-1 pt-75" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="col-12">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control name" id="name_edit" name="name" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_adderss">Address</label>
                        <input type="text" id="clinic_adderss_edit" name="clinic_adderss" class="form-control"  />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_adderss_line2">Address Line2</label>
                        <input type="text" id="clinic_adderss_line2_edit" name="clinic_adderss_line2" class="form-control" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_city">City</label>
                        <input type="text" id="clinic_city_edit" name="clinic_city" class="form-control" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_state">State/Province</label>
                        <input type="text" id="clinic_state_edit" name="clinic_state" class="form-control"  />
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="clinic_postal">Patient Postal/Zip Code</label>
                        <input type="text" id="clinic_postal_edit" name="clinic_postal" class="form-control" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="technician_id">Technician</label>
                        <select class="select2 form-select w-100 form-control" id="technician_id" name="technician_id">
                            <option value=""></option>
                            @foreach ($technicians as $item)
                                <option value={{$item->id}}>{{$item->name}}</option>
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
<!-- Modal to edit new user Ends-->
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        $('.edit-button').click(function() {
            var itemId = $(this).data('item-id');

            $.ajax({
                url: '/get-clinic-data', // Replace with the actual route URL to fetch item data
                method: 'GET',
                data: { itemId: itemId },
                success: function(response) {
                    // Display the modal box with the data
                    displayModalWithData(response);
                },
                error: function(xhr, status, error) {
                    // Handle error if data retrieval fails
                }
            });
        });

        function displayModalWithData(data) {
            console.log('sss', data.technician_id);
             // Populate the modal box with the data
            $('#name_edit').val(data.name); // Set the value of the input field with the retrieved name value
            $('#clinic_adderss_edit').val(data.clinic_adderss);
            $('#clinic_adderss_line2_edit').val(data.clinic_adderss_line2);
            $('#clinic_city_edit').val(data.clinic_city);
            $('#clinic_state_edit').val(data.clinic_state);
            $('#clinic_postal_edit').val(data.clinic_postal);
            $('#technician_id').val(data.technician_id);
            //set action

            var form = $('.edit-clinic'); // Assuming the form has the class "edit-clinic"
            var newAction = '/clinics/' + data.id; // Replace with the appropriate URL for the update action
            form.attr('action', newAction);

            // Display the modal box using the modal library's functionality
            $('#clinic-modals-edit').modal('show');
        }
    });
</script>
@endsection
