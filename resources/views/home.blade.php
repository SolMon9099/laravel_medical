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
    $pending_data = [];
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
            $all_clinics[$value->clinic_doctor->clinic->name] = [];
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
        if ((int)$value->status <= (int)config('const.status_code')['Test Done']){
            $pending_data[] = $value;
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
?>
@section('content')
<!-- Dashboard Analytics Start -->
<section id="dashboard-analytics">
    @if($user_role !== 'attorney')
        <div class="row match-height">
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
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Scanned Results</h4>
                        {{-- <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i> --}}
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
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4 class="card-title">Statistics by paid</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                <h1 class="font-large-2 fw-bolder mt-2 mb-0">{{count($pending_data) + count($paid_data) + count($settled_data)}}</h1>
                                <p class="card-text">Total</p>
                            </div>
                            <div class="col-sm-10 col-12 d-flex justify-content-center">
                                <div id="support-trackers-chart"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="text-center">
                                <p class="card-text mb-50">Unpaid</p>
                                <span class="font-large-1 fw-bold">{{count($pending_data)}}</span>
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

@section('page-script')
    <script>
        var result_by_doctor = "<?php echo $result_by_doctor;?>";
        var result_by_tech = "<?php echo $result_by_tech;?>";
        var t_total = parseInt(result_by_doctor) + parseInt(result_by_tech);

        var pending_number = "<?php echo count($pending_data);?>";
        var paid_number = "<?php echo count($paid_data);?>";
        var settled_number = "<?php echo count($settled_data);?>";
        var total_number = parseInt(pending_number) + parseInt(paid_number) + parseInt(settled_number);
    </script>
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/pages/dashboard-analytics.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
    <!-- END: Page JS-->
@endsection




