@extends('layouts.master')
@section('title', 'Dashboard')

@section('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
<?php
    $user_role = Auth::user()->roles[0]->name;
    $all_clinics = [];
    $all_results = [];
    $all_schedules = [];
    $all_patients = [];
    $data_by_status = [];
    $draft_data = [];
    $pending_data = [];
    $booked_data = [];
    $unpaid_data = [];
    $paid_data = [];
    $settled_data = [];

    $seven_ago = date('Y-m-d', strtotime('-7 days'));
    $thirty_ago = date('Y-m-d', strtotime('-30 days'));
    $sixty_ago = date('Y-m-d', strtotime('-60 days'));
    $ninety_ago = date('Y-m-d', strtotime('-90 days'));

    $temp = [];
    foreach($technicians as $item){
        $temp[$item->id] = $item;
    }
    $technicians = $temp;

    $temp = [];
    foreach($doctors as $item){
        $temp[$item->id] = $item;
    }
    $doctors = $temp;

    $result_by_doctor = 0;
    $result_by_tech = 0;

    foreach ($data as $key => $value) {
        if (isset($value->clinic_doctor)){
            if (!isset($all_clinics[$value->clinic_doctor->clinic->name])){
                $all_clinics[$value->clinic_doctor->clinic->name] = [];
            }
            if (strtotime($value->referral_date) >= strtotime($seven_ago)){
                if (!isset($all_clinics[$value->clinic_doctor->clinic->name]['seven'])){
                    $all_clinics[$value->clinic_doctor->clinic->name]['seven'] = [];
                }
                $all_clinics[$value->clinic_doctor->clinic->name]['seven'][] = $value;
            }
            if (strtotime($value->referral_date) >= strtotime($thirty_ago)){
                if (!isset($all_clinics[$value->clinic_doctor->clinic->name]['thirty'])){
                    $all_clinics[$value->clinic_doctor->clinic->name]['thirty'] = [];
                }
                $all_clinics[$value->clinic_doctor->clinic->name]['thirty'][] = $value;
            }
            if (strtotime($value->referral_date) >= strtotime($sixty_ago)){
                if (!isset($all_clinics[$value->clinic_doctor->clinic->name]['sixty'])){
                    $all_clinics[$value->clinic_doctor->clinic->name]['sixty'] = [];
                }
                $all_clinics[$value->clinic_doctor->clinic->name]['sixty'][] = $value;
            }
            if (strtotime($value->referral_date) >= strtotime($ninety_ago)){
                if (!isset($all_clinics[$value->clinic_doctor->clinic->name]['ninety'])){
                    $all_clinics[$value->clinic_doctor->clinic->name]['ninety'] = [];
                }
                $all_clinics[$value->clinic_doctor->clinic->name]['ninety'][] = $value;
            }
        }

        $all_patients[$value->patient->id] = $value->patient;
        if (!isset($data_by_status[$value->status])){
            $data_by_status[$value->status] = [];
        }
        $data_by_status[$value->status][] = $value;
        if ((int)$value->status == (int)config('const.status_code')['Draft']){
            $draft_data[] = $value;
        }
        if ((int)$value->status == (int)config('const.status_code')['Pending']){
            $pending_data[] = $value;
        }
        if ((int)$value->status == (int)config('const.status_code')['Booked']){
            $booked_data[] = $value;
        }

        if ((int)$value->status == (int)config('const.status_code')['Test Done']){
            $unpaid_data[] = $value;
        }
        if ($value->status == config('const.status_code')['Advance Paid']){
            $paid_data[] = $value;
        }
        if ($value->status == config('const.status_code')['Settled']){
            $settled_data[] = $value;
        }

        if(isset($value->result_files) && count($value->result_files) > 0){
            foreach ($value->result_files as $val){
                if (isset($doctors[$val->updated_by])){
                    $result_by_doctor++;
                } else {
                    $result_by_tech++;
                }
            }
        }
    }

    $schedules_by_date = [];
    $dates = [];
    $first_date = '';
    foreach ($schedules as $key => $value) {
        $date = date('Y-m-d', strtotime($value->start_date));
        $formated_date = date('l, F j, Y', strtotime($value->start_date));
        if ($first_date == ''){
            $first_date = $date;
        }
        if (!isset($schedules_by_date[$date])){
            $schedules_by_date[$date] = [];
            $dates[] = [
                'date' => $date,
                'formated_date' => $formated_date,
            ];
        }
        $item = [
            'patient_transaction_id' => $value->patient_transaction_id,
            'patient_id' => $value->patient_id,
            'start_date' => $value->start_date,
            'start_time' => date('h:i A', strtotime($value->start_date)),
            'end_date' => $value->end_date,
            'end_time' => date('h:i A', strtotime($value->end_date)),
            'title' => $value->title,
            'description' => $value->description,
            'patient_name' => $value->patient->name,
            'referral_date' => date('m-d-Y', strtotime($value->patient_transaction->referral_date)),
        ];
        $schedules_by_date[$date][] = $item;
    }
    // var_dump($schedules_by_date);exit;
?>
@section('content')
<!-- Dashboard Analytics Start -->
<section id="dashboard-analytics">
    @if($user_role !== 'attorney')
        <div class="row match-height">
            @if($user_role !== 'office manager' && $user_role !== 'technician')
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card card-browser-states">
                        <div class="card-body">
                            <div class="browser-states">
                                <div class="d-flex">
                                    {{-- <img src="../../../app-assets/images/icons/google-chrome.png" class="rounded me-1" height="30" alt="Google Chrome" /> --}}
                                    <div class="avatar bg-light-danger me-2">
                                        <div class="avatar-content">
                                            <i style="font-size:15px;" class="fa fa-hospital-o avatar-icon"></i>
                                        </div>
                                    </div>
                                    <h6 class="align-self-center mb-0">Clinics</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{count($all_clinics)}}</div>
                                </div>
                            </div>
                            <div class="browser-states">
                                <div class="d-flex">
                                    <div class="avatar bg-light-warning me-2">
                                        <div class="avatar-content">
                                            <i data-feather="user" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <h6 class="align-self-center mb-0">Patients</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{count($all_patients)}}</div>
                                </div>
                            </div>
                            <div class="browser-states">
                                <div class="d-flex">
                                    <div class="avatar bg-light-warning me-2">
                                        <div class="avatar-content">
                                            <i style="font-size:15px;" class="fa fa-database avatar-icon"></i>
                                        </div>
                                    </div>
                                    <h6 class="align-self-center mb-0">Referrals</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{count($data)}}</div>
                                </div>
                            </div>
                            <div class="browser-states">
                                <div class="d-flex">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <i style="font-size:15px;" class="avatar-icon fa fa-user-md"></i>
                                        </div>
                                    </div>
                                    <h6 class="align-self-center mb-0">Doctors</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{count($doctors)}}</div>
                                </div>
                            </div>
                            <div class="browser-states">
                                <div class="d-flex">
                                    <div class="avatar bg-light-secondary me-2">
                                        <div class="avatar-content">
                                            <i style="font-size:15px;" class="fa fa-wrench avatar-icon"></i>
                                        </div>
                                    </div>
                                    <h6 class="align-self-center mb-0">Technicians</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{count($technicians)}}</div>
                                </div>
                            </div>
                            <div class="browser-states">
                                <div class="d-flex">
                                    <div class="avatar bg-light-secondary me-2">
                                        <div class="avatar-content">
                                            <i style="font-size:15px;" class="fa fa-calendar avatar-icon"></i>
                                        </div>
                                    </div>
                                    <h6 class="align-self-center mb-0">Schedule</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{count($schedules)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card card-browser-states">
                        <div class="card-body">
                            <div class="fc-button-group">
                                <button disabled class="fc-prev-button fc-button fc-button-primary" onclick="movePrevDate()"
                                    type="button" aria-label="prev" id="prev-btn"> < </button>
                                <span id="date" style="margin:0px 5px;font-size:20px;">
                                    @if (count($schedules_by_date) > 0)
                                        {{date('l, F j, Y', strtotime($first_date))}}
                                    @else
                                        {{date('l, F j, Y')}}
                                    @endif
                                </span>
                                @if(count($schedules_by_date) > 1)
                                    <button class="fc-next-button fc-button fc-button-primary" onclick="moveNextDate()"
                                        type="button" aria-label="next" id="next-btn"> > </button>
                                @else
                                    <button disabled class="fc-next-button fc-button fc-button-primary" onclick="moveNextDate()"
                                        type="button" aria-label="next" id="next-btn"> > </button>
                                @endif
                            </div>
                            <div id="sch-area">
                                @if(count($schedules_by_date) > 0)
                                    @foreach($schedules_by_date[$first_date] as $key => $item)
                                    <div class="sch-blog">
                                        <ul>
                                            <li class="time part">{{date('h:i A', strtotime($item['start_date']))}} - {{date('h:i A', strtotime($item['end_date']))}}</li>
                                            <li class="patient part">{{$item['patient_name']}} : {{$item['referral_date']}}</li>
                                            <li class="title part text-primary">{{$item['title']}}</li>
                                            <li class="desc part text-info">{{$item['description']}}</li>
                                        </ul>

                                    </div>
                                    @endforeach
                                @else
                                <div class="no-data text-warning">No appointments</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Scanned Results</h4>
                    </div>
                    <div class="card-body p-0">
                        <div id="goal-overview-radial-bar-chart" class="my-2"></div>
                        <div class="row border-top text-center mx-0">
                            <div class="col-6 border-end py-1">
                                <p class="card-text text-muted mb-0">By Doctors</p>
                                <h3 class="fw-bolder mb-0">{{$result_by_doctor}}</h3>
                            </div>
                            <div class="col-6 py-1">
                                <p class="card-text text-muted mb-0">By Technicians</p>
                                <h3 class="fw-bolder mb-0">{{$result_by_tech}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4 class="card-title">Referral Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                <h1 class="font-large-2 fw-bolder mt-2 mb-0">{{count($draft_data) + count($pending_data) + count($booked_data)}}</h1>
                                <p class="card-text">Total</p>
                            </div>
                            <div class="col-sm-10 col-12 d-flex justify-content-center">
                                <div id="referral-trackers-chart"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="text-center">
                                <p class="card-text mb-50">Draft</p>
                                <span class="font-large-1 fw-bold">{{count($draft_data)}}</span>
                            </div>
                            <div class="text-center">
                                <p class="card-text mb-50">Pending</p>
                                <span class="font-large-1 fw-bold">{{count($pending_data)}}</span>
                            </div>
                            <div class="text-center">
                                <p class="card-text mb-50">Booked</p>
                                <span class="font-large-1 fw-bold">{{count($booked_data)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4 class="card-title">Result Statistics by paid</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                <h1 class="font-large-2 fw-bolder mt-2 mb-0">{{count($unpaid_data) + count($paid_data) + count($settled_data)}}</h1>
                                <p class="card-text">Total</p>
                            </div>
                            <div class="col-sm-10 col-12 d-flex justify-content-center">
                                <div id="support-trackers-chart"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="text-center">
                                <p class="card-text mb-50">Unpaid</p>
                                <span class="font-large-1 fw-bold">{{count($unpaid_data)}}</span>
                            </div>
                            <div class="text-center">
                                <p class="card-text mb-50">Advance Paid</p>
                                <span class="font-large-1 fw-bold">{{count($paid_data)}}</span>
                            </div>
                            <div class="text-center">
                                <p class="card-text mb-50">Settled</p>
                                <span class="font-large-1 fw-bold">{{count($settled_data)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($user_role !== 'office manager')
            <div class="row match-height">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="card card-statistics">
                        <div class="card-header">
                            <h4 class="card-title">Statistics</h4>
                            {{-- <div class="d-flex align-items-center">
                                <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p>
                            </div> --}}
                        </div>
                        <div class="card-body statistics-body">
                            <div class="row">
                                @foreach (config('const.status') as $code => $val)
                                @if ($code != -1)
                                <div class="col-xl-2 col-sm-6 col-12 mb-2 mb-xl-0">
                                    <div class="d-flex flex-row">
                                        <div class="<?php echo config('const.status_bg_class')[$code].' avatar me-2' ?>">
                                            <div class="avatar-content">
                                                <i style="font-size:22px;" class="avatar-icon fa fa-database"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="<?php echo config('const.status_class')[$code]." fw-bolder mb-0" ?>">
                                                {{isset($data_by_status[$code]) ? count($data_by_status[$code]) : 0}}
                                            </h4>
                                            <p class="<?php echo config('const.status_class')[$code]." card-text font-small-3 mb-0" ?>">
                                                {{$val}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <div class="row match-height">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title">Referals by clinic</h4>
                    {{-- <div class="d-flex align-items-center">
                        <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p>
                    </div> --}}
                </div>
                <div class="card-body statistics-body">
                    @foreach ($all_clinics as $name => $clinic_data)
                    <div class="row" style="margin-bottom:35px;">
                        <h4 class="card-title">{{$name}}</h4>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary me-2">
                                    <div class="avatar-content">
                                        <i style="font-size:22px;" class="avatar-icon fa fa-database"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{isset($clinic_data['seven']) ? count($clinic_data['seven']) : 0}}</h4>
                                    <p class="card-text font-small-3 mb-0">Last 7 days</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-secondary me-2">
                                    <div class="avatar-content">
                                        <i style="font-size:22px;" class="avatar-icon fa fa-database"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{isset($clinic_data['thirty']) ? count($clinic_data['thirty']) : 0}}</h4>
                                    <p class="card-text font-small-3 mb-0">Last 30 days</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-info me-2">
                                    <div class="avatar-content">
                                        <i style="font-size:22px;" class="avatar-icon fa fa-database"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{isset($clinic_data['sixty']) ? count($clinic_data['sixty']) : 0}}</h4>
                                    <p class="card-text font-small-3 mb-0">Last 60 days</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-warning me-2">
                                    <div class="avatar-content">
                                        <i style="font-size:22px;" class="avatar-icon fa fa-database"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{isset($clinic_data['ninety']) ? count($clinic_data['ninety']) : 0}}</h4>
                                    <p class="card-text font-small-3 mb-0">Last 90 days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</section>
<!-- Dashboard Analytics end -->
@endsection

<style>
    .fc-button{
        display: inline-block;
        background-color: transparent;
        border-color: transparent;
        font-size: 20px;
    }
    .no-data{
        font-size: 20px;
        text-align: center;
        margin-top:30%;
    }
    #sch-area{
        padding:20px 10px;
    }
    .sch-blog{
        padding:5px 10px;
        margin-bottom: 10px;
    }
    .part{
        list-style-type:none;
    }
    .time{
        font-size:16px;
        list-style-type:disc;
    }
    .patient, .title, .desc{
        padding-left:20px;
    }

</style>

@section('page-script')
    <script>
        var result_by_doctor = "<?php echo $result_by_doctor;?>";
        var result_by_tech = "<?php echo $result_by_tech;?>";
        var t_total = parseInt(result_by_doctor) + parseInt(result_by_tech);

        var unpaid_number = "<?php echo count($unpaid_data);?>";
        var paid_number = "<?php echo count($paid_data);?>";
        var settled_number = "<?php echo count($settled_data);?>";
        var total_number = parseInt(unpaid_number) + parseInt(paid_number) + parseInt(settled_number);

        var draft_number = "<?php echo count($draft_data);?>";
        var pending_number = "<?php echo count($pending_data);?>";
        var booked_number = "<?php echo count($booked_data);?>";
        var total_referral_number = parseInt(draft_number) + parseInt(pending_number) + parseInt(booked_number);

        var current_index = 0;
        var schedules_by_date = <?php echo json_encode($schedules_by_date);?>;
        var dates = <?php echo json_encode($dates);?>;
        function makeHtml(data){
            res = '';
            data.map(item => {
                res += '<div class="sch-blog">';
                res += '<ul>';
                res += '<li class="time part">';
                res += item.start_time + ' - ' + item.end_time;
                res += '</ll>';
                res += '<li class="patient part">';
                res += item.patient_name + ' : ' + item.referral_date;
                res += '</ll>';
                res += '<li class="title part text-primary">';
                res += item.title;
                res += '</ll>';
                res += '<li class="desc part text-info">';
                if (item.description != undefined && item.description != null){
                    res += item.description;
                }
                res += '</ll>';
                res += '</ul>';
                res += '</div>';
            })
            $('#sch-area').html(res);
        }
        function moveNextDate(){
            current_index++;
            if (dates[current_index] == undefined) return;
            if (current_index == dates.length - 1){
                $('#next-btn').attr('disabled', true);
            }
            $('#prev-btn').attr('disabled', false);
            $('#date').html(dates[current_index].formated_date);
            makeHtml(schedules_by_date[dates[current_index].date]);
        }
        function movePrevDate(){
            current_index--;
            if (current_index < 0) return;
            if (current_index == 0){
                $('#prev-btn').attr('disabled', true);
            }
            $('#next-btn').attr('disabled', false);
            $('#date').html(dates[current_index].formated_date);
            makeHtml(schedules_by_date[dates[current_index].date]);
        }
    </script>
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/pages/dashboard-analytics.js') }}"></script>
    {{-- <script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script> --}}
    <!-- END: Page JS-->
@endsection




