@extends('layouts.master')

@section('title', 'Calendar')

@section('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-calendar.css') }}">
@endsection
<?php
    $patient_object = array();
    foreach($patient_data as $patient) {
        $patient_object[$patient->patient_id.'_'.$patient->id] = $patient->patient->name.' '. $patient->referral_date;
    }
?>
<style>
    .avatar{
        display: none!important;
    }
</style>
@section('content')
<section>
    <div class="app-calendar overflow-hidden border">
        {{-- <form action="{{route('calendar.store')}}" id = "calendar_form" method="post" name = "calendar_form">
            @csrf
            <input type="hidden" value="" name="booked_schedules" id = 'booked_schedules'/>
            <input type="hidden" value="" name="deleted_schedules" id = 'deleted_schedules'/>
            <button class="btn btn-primary btn-toggle-sidebar" type="submit" style="margin-bottom:8px;">
                <span class="align-middle">Save Schedule</span>
            </button> --}}
            <div class="row g-0">
                <!-- Sidebar -->
                {{-- <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
                    <div class="sidebar-wrapper">
                        <div class="card-body d-flex justify-content-center">
                        </div>
                        <div class="card-body pb-0">
                            <h5 class="section-label mb-1">
                                <span class="align-middle">Filter</span>
                            </h5>
                            <div class="form-check mb-1">
                                <input type="checkbox" class="form-check-input select-all" id="select-all" checked />
                                <label class="form-check-label" for="select-all">View All</label>
                            </div>
                            <div class="calendar-events-filter">
                                <div class="form-check form-check-danger mb-1">
                                    <input type="checkbox" class="form-check-input input-filter" id="personal" data-value="personal" checked />
                                    <label class="form-check-label" for="personal">Personal</label>
                                </div>
                                <div class="form-check form-check-primary mb-1">
                                    <input type="checkbox" class="form-check-input input-filter" id="business" data-value="business" checked />
                                    <label class="form-check-label" for="business">Business</label>
                                </div>
                                <div class="form-check form-check-warning mb-1">
                                    <input type="checkbox" class="form-check-input input-filter" id="family" data-value="family" checked />
                                    <label class="form-check-label" for="family">Family</label>
                                </div>
                                <div class="form-check form-check-success mb-1">
                                    <input type="checkbox" class="form-check-input input-filter" id="holiday" data-value="holiday" checked />
                                    <label class="form-check-label" for="holiday">Holiday</label>
                                </div>
                                <div class="form-check form-check-info">
                                    <input type="checkbox" class="form-check-input input-filter" id="etc" data-value="etc" checked />
                                    <label class="form-check-label" for="etc">ETC</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <img src="{{ asset('app-assets/images/pages/calendar-illustration.png') }}" alt="Calendar illustration" class="img-fluid" />
                    </div>
                </div> --}}
                <!-- /Sidebar -->

                <!-- Calendar -->
                <div class="col position-relative">
                    <div class="card shadow-none border-0 mb-0 rounded-0">
                        <div class="card-body pb-0">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
                <!-- /Calendar -->
                <div class="body-content-overlay"></div>
            </div>
        {{-- </form> --}}
    </div>
    <!-- Calendar Add/Update/Delete event modal-->
    <div class="modal modal-slide-in event-sidebar fade" id="add-new-sidebar">
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">Add Schedule</h5>
                </div>
                <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                    <form class="event-form needs-validation" data-ajax="true" novalidate>
                        <div class="mb-1">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Event Title" />
                        </div>
                        {{-- <div class="mb-1">
                            <label for="select-label" class="form-label">Label</label>
                            <select class="select2 select-label form-select w-100" id="select-label" name="select-label">
                                <option data-label="primary" value="Business" selected>Business</option>
                                <option data-label="danger" value="Personal">Personal</option>
                                <option data-label="warning" value="Family">Family</option>
                                <option data-label="success" value="Holiday">Holiday</option>
                                <option data-label="info" value="ETC">ETC</option>
                            </select>
                        </div> --}}
                        <div class="mb-1 position-relative">
                            <label for="start-date" class="form-label">Start Date</label>
                            <input type="text" class="form-control" id="start-date" name="start-date" placeholder="Start Date" />
                        </div>
                        <div class="mb-1 position-relative">
                            <label for="end-date" class="form-label">End Date</label>
                            <input type="text" class="form-control" id="end-date" name="end-date" placeholder="End Date" />
                        </div>
                        {{-- <div class="mb-1">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input allDay-switch" id="customSwitch3" />
                                <label class="form-check-label" for="customSwitch3">All Day</label>
                            </div>
                        </div> --}}
                        {{-- <div class="mb-1">
                            <label for="event-url" class="form-label">Event URL</label>
                            <input type="url" class="form-control" id="event-url" placeholder="https://www.google.com" />
                        </div> --}}
                        <div class="mb-1 select2-primary">
                            <label for="event-guests" class="form-label">Add Patients</label>
                            <select class="select2 select-add-guests form-select w-100" id="event-guests" name="guests">
                                @foreach ($patient_data as $patient_item)
                                    @if($patient_item->status == config('const.status_code.Pending'))
                                        <option value={{$patient_item->patient_id.'_'.$patient_item->id}}>
                                            {{$patient_item->patient->name. ' '. $patient_item->referral_date}}
                                        </option>
                                    @else
                                        <option value={{$patient_item->patient_id.'_'.$patient_item->id}} disabled>
                                            {{$patient_item->patient->name. ' '. $patient_item->referral_date}}
                                        </option>
                                    @endif
                                @endforeach
                                {{-- <option data-avatar="1-small.png" value="Jane Foster">Jane Foster</option>
                                <option data-avatar="3-small.png" value="Donna Frank">Donna Frank</option>
                                <option data-avatar="5-small.png" value="Gabrielle Robertson">Gabrielle Robertson</option>
                                <option data-avatar="7-small.png" value="Lori Spears">Lori Spears</option>
                                <option data-avatar="9-small.png" value="Sandy Vega">Sandy Vega</option>
                                <option data-avatar="11-small.png" value="Cheryl May">Cheryl May</option> --}}
                            </select>
                        </div>
                        {{-- <div class="mb-1">
                            <label for="event-location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="event-location" placeholder="Enter Location" />
                        </div> --}}
                        <div class="mb-1">
                            <label class="form-label">Description</label>
                            <textarea name="event-description-editor" id="event-description-editor" class="form-control"></textarea>
                        </div>
                        <div class="mb-1 d-flex">
                            <button type="submit" class="btn btn-primary add-event-btn me-1">Add</button>
                            <button type="button" class="btn btn-outline-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary update-event-btn d-none me-1">Update</button>
                            <button class="btn btn-outline-danger btn-delete-event d-none">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Calendar Add/Update/Delete event modal-->
</section>
@endsection

@section('page-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var schedule_data = <?php echo json_encode($schedule_data);?>;
    var patient_object = <?php echo json_encode($patient_object);?>;

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
    function formatDateTime(datetime){
        var res = '';
        res = datetime.getFullYear() + '-';
        res += datetime.getMonth() < 9 ? ("0" + (datetime.getMonth()+1)) : datetime.getMonth() + 1;
        res += '-';
        res += datetime.getDate() < 10 ? ("0" + datetime.getDate()) : datetime.getDate();
        res += ' ';

        res += datetime.getHours() < 10 ? ("0" + datetime.getHours()) : datetime.getHours();
        res += ':';
        res += datetime.getMinutes() < 10 ? ("0" + datetime.getMinutes()) : datetime.getMinutes();
        return res;
    }
    $('#start-date').change(function(){
        var start_date = $('#start-date').val();
        if (start_date != null && start_date != ''){
            start_date += ":00";
            start_date = new Date(start_date);
            start_date.setHours(start_date.getHours() + 1);
            var end_date = formatDateTime(start_date);
            $('#end-date').val(end_date);
        }

    })
</script>
<script src="{{ asset('app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{ asset('app-assets/js/scripts/pages/app-calendar-events.js')}}"></script>
<script src="{{ asset('app-assets/js/scripts/pages/app-calendar.js')}}"></script>
@endsection
