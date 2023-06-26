@extends('layouts.master')

@section('title', 'Profile')
@section('content')
<?php
    $unpaid_data = [];
    $incompleted_data = [];
    $paid_data = [];
    $settled_data = [];
    foreach ($transaction_data as $key => $value) {
        if ($value->status == config('const.status_code')['Test Done']){
            $unpaid_data[] = $value;
        }
        if ($value->status != config('const.status_code')['Test Done'] &&  $value->status != config('const.status_code')['Settled'] && $value->status != config('const.status_code')['Advance Paid']){
            $incompleted_data[] = $value;
        }
        if ($value->status == config('const.status_code')['Advance Paid']){
            $paid_data[] = $value;
        }
        if ($value->status == config('const.status_code')['Settled']){
            $settled_data[] = $value;
        }
    }
?>
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Patient Referrals</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('layouts.error')
                    </div>
                </div>
                <div class="content-body">
                    <div id="user-profile">
                        <!-- profile info section -->
                        <section id="profile-info">
                            <div class="row">
                                <!-- center profile info section -->
                                <div class="col-lg-12 col-12 order-1 order-lg-2">
                                    <!-- post 1 -->
                                    <div class="card">
                                        <div class="card-body">
                                            @if(count($unpaid_data) > 0)
                                            <h5>Unpaid</h5>
                                            <div class="table-responsive">
                                                <table class="patients-referral-table table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Date</th>
                                                            <th>Patient</th>
                                                            <th>Attorney</th>
                                                            <th>Doctor</th>
                                                            <th>Clinic</th>
                                                            <th>Status</th>
                                                            <th>Schedule</th>
                                                            <th>Referral</th>
                                                            <th>Signed doc</th>
                                                            @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney' || Auth::user()->roles[0]->name == 'admin')
                                                            <th>Invoice</th>
                                                            @endif
                                                            <th>Result</th>
                                                            @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'admin')
                                                            <th>Paid</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($unpaid_data as $key=>$value)
                                                            <tr>
                                                                <td>{{++$key}}</td>
                                                                <td>{{date('m-d-Y', strtotime($value->referral_date))}}</td>
                                                                <td>{{$value->patient->name}}</td>
                                                                <td>{{$value->attorney->name}}</td>
                                                                <td>{{$value->doctor->name}}</td>
                                                                <td>{{isset($value->clinic_doctor) ? $value->clinic_doctor->clinic->name : ''}}</td>
                                                                <td>
                                                                    <span class="{{config('const.status_class')[$value->status]}}">{{config('const.status')[$value->status]}}</span>
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->schedule))
                                                                        <div>{{date('m-d-Y H:i', strtotime($value->schedule->start_date))}} ～ {{date('H:i', strtotime($value->schedule->end_date))}}</div>
                                                                        <div>{{$value->schedule->title}}</div>
                                                                        @if ($value->schedule->description)
                                                                            <div>{{$value->schedule->description}}</div>
                                                                        @endif
                                                                        @if(strtotime("now") > strtotime($value->schedule->start_date))
                                                                            <div class="text-info">Expired</div>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                @if(isset($value->referral_files) && count($value->referral_files) > 0)
                                                                @foreach ($value->referral_files as $val)
                                                                    <div><a class='pdf-link' target="_blank" href="{{ asset('storage/referral/'.$val->referral_file) }}">{{$val->referral_file}}</a></div>
                                                                @endforeach
                                                                @endif
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->files) && count($value->files) > 0)
                                                                        @foreach ($value->files as $val)
                                                                            <div><a class='pdf-link' target="_blank" href="{{ asset('uploads/sign/'.$val->files) }}">{{$val->files}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Booked') && (Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'office manager' || Auth::user()->roles[0]->name == 'technician'))
                                                                            <form action="{{route('profiles.upload_sign_docs')}}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                                <input class="form-control upload-files" type="file" required name="files[]" accept="application/pdf" />
                                                                                <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Docs</button>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney' || Auth::user()->roles[0]->name == 'admin')
                                                                <td>
                                                                    @if(isset($value->invoice_files) && count($value->invoice_files) > 0)
                                                                        @foreach ($value->invoice_files as $val)
                                                                            <div><a class='pdf-link' target="_blank" href="{{ asset('storage/invoice/'.$val->invoice_file) }}">{{$val->invoice_file}}</a></div>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                @endif
                                                                <td>
                                                                    @if(isset($value->result_files) && count($value->result_files) > 0)
                                                                        @foreach ($value->result_files as $val)
                                                                            <div><a target="_blank" class='pdf-link' href="{{ asset('uploads/results/'.$val->result_file) }}">{{$val->result_file}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Signed') && (Auth::user()->roles[0]->name == 'doctor' || Auth::user()->roles[0]->name == 'technician' || Auth::user()->roles[0]->name == 'office manager'))
                                                                        <form action="{{route('profiles.upload_result_docs')}}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                            <input class="form-control upload-files" type="file" required name="result_files[]" accept="application/pdf" />
                                                                            <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Result</button>
                                                                        </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'admin')
                                                                <td>
                                                                    @if($value->status == config('const.status_code')['Test Done'] && Auth::user()->roles[0]->name == 'funding company')
                                                                    <form action="{{route('profiles.set_advanced_paid')}}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                        <button type="submit" class="btn btn-sm btn-primary waves-effect">Advance Paid</button>
                                                                    </form>
                                                                    @endif
                                                                    @if($value->status == config('const.status_code')['Advance Paid'])
                                                                    <form action="{{route('profiles.set_settled')}}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                        <button type="submit" class="btn btn-sm btn-primary waves-effect">Settled</button>
                                                                    </form>
                                                                    @endif
                                                                </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif

                                            @if(count($incompleted_data) > 0)
                                            <h5>Incomplete</h5>
                                            <div class="table-responsive">
                                                <table class="patients-referral-table table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Date</th>
                                                            <th>Patient</th>
                                                            <th>Attorney</th>
                                                            <th>Doctor</th>
                                                            <th>Clinic</th>
                                                            <th>Status</th>
                                                            <th>Schedule</th>
                                                            <th>Referral</th>
                                                            <th>Signed doc</th>
                                                            @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney' || Auth::user()->roles[0]->name == 'admin')
                                                            <th>Invoice</th>
                                                            @endif
                                                            <th>Result</th>
                                                            @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'admin')
                                                            <th>Paid</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($incompleted_data as $key=>$value)
                                                            <tr>
                                                                <td>{{++$key}}</td>
                                                                <td>{{date('m-d-Y', strtotime($value->referral_date))}}</td>
                                                                <td>{{$value->patient->name}}</td>
                                                                <td>{{$value->attorney->name}}</td>
                                                                <td>{{$value->doctor->name}}</td>
                                                                <td>{{isset($value->clinic_doctor) ? $value->clinic_doctor->clinic->name : ''}}</td>
                                                                <td>
                                                                    <span class="{{config('const.status_class')[$value->status]}}">{{config('const.status')[$value->status]}}</span>
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->schedule))
                                                                        <div>{{date('m-d-Y H:i', strtotime($value->schedule->start_date))}} ～ {{date('H:i', strtotime($value->schedule->end_date))}}</div>
                                                                        <div>{{$value->schedule->title}}</div>
                                                                        @if ($value->schedule->description)
                                                                            <div>{{$value->schedule->description}}</div>
                                                                        @endif
                                                                        @if(strtotime("now") > strtotime($value->schedule->start_date))
                                                                            <div class="text-info">Expired</div>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                @if(isset($value->referral_files) && count($value->referral_files) > 0)
                                                                @foreach ($value->referral_files as $val)
                                                                    <div><a class='pdf-link' target="_blank" href="{{ asset('storage/referral/'.$val->referral_file) }}">{{$val->referral_file}}</a></div>
                                                                @endforeach
                                                                @endif
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->files) && count($value->files) > 0)
                                                                        @foreach ($value->files as $val)
                                                                            <div><a class='pdf-link' target="_blank" href="{{ asset('uploads/sign/'.$val->files) }}">{{$val->files}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Booked') && (Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'office manager' || Auth::user()->roles[0]->name == 'technician'))
                                                                            <form action="{{route('profiles.upload_sign_docs')}}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                                <input class="form-control upload-files" type="file" required name="files[]" accept="application/pdf" />
                                                                                <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Docs</button>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney' || Auth::user()->roles[0]->name == 'admin')
                                                                <td>
                                                                    @if(isset($value->invoice_files) && count($value->invoice_files) > 0)
                                                                        @foreach ($value->invoice_files as $val)
                                                                            <div><a class='pdf-link' target="_blank" href="{{ asset('storage/invoice/'.$val->invoice_file) }}">{{$val->invoice_file}}</a></div>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                @endif
                                                                <td>
                                                                    @if(isset($value->result_files) && count($value->result_files) > 0)
                                                                        @foreach ($value->result_files as $val)
                                                                            <div><a target="_blank" class='pdf-link' href="{{ asset('uploads/results/'.$val->result_file) }}">{{$val->result_file}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Signed') && (Auth::user()->roles[0]->name == 'doctor' || Auth::user()->roles[0]->name == 'technician' || Auth::user()->roles[0]->name == 'office manager'))
                                                                        <form action="{{route('profiles.upload_result_docs')}}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                            <input class="form-control upload-files" type="file" required name="result_files[]" accept="application/pdf" />
                                                                            <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Result</button>
                                                                        </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'admin')
                                                                <td>
                                                                    @if($value->status == config('const.status_code')['Test Done'] && Auth::user()->roles[0]->name == 'funding company')
                                                                    <form action="{{route('profiles.set_advanced_paid')}}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                        <button type="submit" class="btn btn-sm btn-primary waves-effect">Advance Paid</button>
                                                                    </form>
                                                                    @endif
                                                                    @if($value->status == config('const.status_code')['Advance Paid'])
                                                                    <form action="{{route('profiles.set_settled')}}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                        <button type="submit" class="btn btn-sm btn-primary waves-effect">Settled</button>
                                                                    </form>
                                                                    @endif
                                                                </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif

                                            @if (count($paid_data) > 0)
                                            <h5>Advance Paid</h5>
                                            <div class="table-responsive">
                                                <table class="patients-referral-table table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Date</th>
                                                            <th>Patient</th>
                                                            <th>Attorney</th>
                                                            <th>Doctor</th>
                                                            <th>Clinic</th>
                                                            <th>Status</th>
                                                            <th>Schedule</th>
                                                            <th>Referral</th>
                                                            <th>Signed doc</th>
                                                            @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney' || Auth::user()->roles[0]->name == 'admin')
                                                            <th>Invoice</th>
                                                            @endif
                                                            <th>Result</th>
                                                            @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'admin')
                                                            <th>Paid</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($paid_data as $key=>$value)
                                                            <tr>
                                                                <td>{{++$key}}</td>
                                                                <td>{{date('m-d-Y', strtotime($value->referral_date))}}</td>
                                                                <td>{{$value->patient->name}}</td>
                                                                <td>{{$value->attorney->name}}</td>
                                                                <td>{{$value->doctor->name}}</td>
                                                                <td>{{isset($value->clinic_doctor) ? $value->clinic_doctor->clinic->name : ''}}</td>
                                                                <td>
                                                                    <span class="{{config('const.status_class')[$value->status]}}">{{config('const.status')[$value->status]}}</span>
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->schedule))
                                                                        <div>{{date('m-d-Y H:i', strtotime($value->schedule->start_date))}} ～ {{date('H:i', strtotime($value->schedule->end_date))}}</div>
                                                                        <div>{{$value->schedule->title}}</div>
                                                                        @if ($value->schedule->description)
                                                                            <div>{{$value->schedule->description}}</div>
                                                                        @endif
                                                                        @if(strtotime("now") > strtotime($value->schedule->start_date))
                                                                            <div class="text-info">Expired</div>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                @if(isset($value->referral_files) && count($value->referral_files) > 0)
                                                                @foreach ($value->referral_files as $val)
                                                                    <div><a class='pdf-link' target="_blank" href="{{ asset('storage/referral/'.$val->referral_file) }}">{{$val->referral_file}}</a></div>
                                                                @endforeach
                                                                @endif
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->files) && count($value->files) > 0)
                                                                        @foreach ($value->files as $val)
                                                                            <div><a class='pdf-link' target="_blank" href="{{ asset('uploads/sign/'.$val->files) }}">{{$val->files}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Booked') && (Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'office manager' || Auth::user()->roles[0]->name == 'technician'))
                                                                            <form action="{{route('profiles.upload_sign_docs')}}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                                <input class="form-control upload-files" type="file" required name="files[]" accept="application/pdf" />
                                                                                <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Docs</button>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney' || Auth::user()->roles[0]->name == 'admin')
                                                                <td>
                                                                    @if(isset($value->invoice_files) && count($value->invoice_files) > 0)
                                                                        @foreach ($value->invoice_files as $val)
                                                                            <div><a class='pdf-link' target="_blank" href="{{ asset('storage/invoice/'.$val->invoice_file) }}">{{$val->invoice_file}}</a></div>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                @endif
                                                                <td>
                                                                    @if(isset($value->result_files) && count($value->result_files) > 0)
                                                                        @foreach ($value->result_files as $val)
                                                                            <div><a target="_blank" class='pdf-link' href="{{ asset('uploads/results/'.$val->result_file) }}">{{$val->result_file}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Signed') && (Auth::user()->roles[0]->name == 'doctor' || Auth::user()->roles[0]->name == 'technician' || Auth::user()->roles[0]->name == 'office manager'))
                                                                        <form action="{{route('profiles.upload_result_docs')}}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                            <input class="form-control upload-files" type="file" required name="result_files[]" accept="application/pdf" />
                                                                            <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Result</button>
                                                                        </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'admin')
                                                                <td>
                                                                    @if($value->status == config('const.status_code')['Test Done'] && Auth::user()->roles[0]->name == 'funding company')
                                                                    <form action="{{route('profiles.set_advanced_paid')}}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                        <button type="submit" class="btn btn-sm btn-primary waves-effect">Advance Paid</button>
                                                                    </form>
                                                                    @endif
                                                                    @if($value->status == config('const.status_code')['Advance Paid'])
                                                                    <form action="{{route('profiles.set_settled')}}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                        <button type="submit" class="btn btn-sm btn-primary waves-effect">Settled</button>
                                                                    </form>
                                                                    @endif
                                                                </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif

                                            @if (count($settled_data) > 0)
                                            <h5>Settled</h5>
                                            <div class="table-responsive">
                                                <table class="patients-referral-table table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Date</th>
                                                            <th>Patient</th>
                                                            <th>Attorney</th>
                                                            <th>Doctor</th>
                                                            <th>Clinic</th>
                                                            <th>Status</th>
                                                            <th>Schedule</th>
                                                            <th>Referral</th>
                                                            <th>Signed doc</th>
                                                            @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney' || Auth::user()->roles[0]->name == 'admin')
                                                            <th>Invoice</th>
                                                            @endif
                                                            <th>Result</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($settled_data as $key=>$value)
                                                            <tr>
                                                                <td>{{++$key}}</td>
                                                                <td>{{date('m-d-Y', strtotime($value->referral_date))}}</td>
                                                                <td>{{$value->patient->name}}</td>
                                                                <td>{{$value->attorney->name}}</td>
                                                                <td>{{$value->doctor->name}}</td>
                                                                <td>{{isset($value->clinic_doctor) ? $value->clinic_doctor->clinic->name : ''}}</td>
                                                                <td>
                                                                    <span class="{{config('const.status_class')[$value->status]}}">{{config('const.status')[$value->status]}}</span>
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->schedule))
                                                                        <div>{{date('m-d-Y H:i', strtotime($value->schedule->start_date))}} ～ {{date('H:i', strtotime($value->schedule->end_date))}}</div>
                                                                        <div>{{$value->schedule->title}}</div>
                                                                        @if ($value->schedule->description)
                                                                            <div>{{$value->schedule->description}}</div>
                                                                        @endif
                                                                        @if(strtotime("now") > strtotime($value->schedule->start_date))
                                                                            <div class="text-info">Expired</div>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                @if(isset($value->referral_files) && count($value->referral_files) > 0)
                                                                @foreach ($value->referral_files as $val)
                                                                    <div><a class='pdf-link' target="_blank" href="{{ asset('storage/referral/'.$val->referral_file) }}">{{$val->referral_file}}</a></div>
                                                                @endforeach
                                                                @endif
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->files) && count($value->files) > 0)
                                                                        @foreach ($value->files as $val)
                                                                            <div><a class='pdf-link' target="_blank" href="{{ asset('uploads/sign/'.$val->files) }}">{{$val->files}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Booked') && (Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'office manager' || Auth::user()->roles[0]->name == 'technician'))
                                                                            <form action="{{route('profiles.upload_sign_docs')}}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                                <input class="form-control upload-files" type="file" required name="files[]" accept="application/pdf" />
                                                                                <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Docs</button>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney' || Auth::user()->roles[0]->name == 'admin')
                                                                <td>
                                                                    @if(isset($value->invoice_files) && count($value->invoice_files) > 0)
                                                                        @foreach ($value->invoice_files as $val)
                                                                            <div><a class='pdf-link' target="_blank" href="{{ asset('storage/invoice/'.$val->invoice_file) }}">{{$val->invoice_file}}</a></div>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                @endif
                                                                <td>
                                                                    @if(isset($value->result_files) && count($value->result_files) > 0)
                                                                        @foreach ($value->result_files as $val)
                                                                            <div><a target="_blank" class='pdf-link' href="{{ asset('uploads/results/'.$val->result_file) }}">{{$val->result_file}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Signed') && (Auth::user()->roles[0]->name == 'doctor' || Auth::user()->roles[0]->name == 'technician' || Auth::user()->roles[0]->name == 'office manager'))
                                                                        <form action="{{route('profiles.upload_result_docs')}}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                            <input class="form-control upload-files" type="file" required name="result_files[]" accept="application/pdf" />
                                                                            <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Result</button>
                                                                        </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                            <!--/ comments -->
                                        </div>
                                    </div>
                                    <!--/ post 1 -->
                                </div>
                                <!--/ center profile info section -->
                            </div>
                        </section>
                        <!--/ profile info section -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .dt-buttons{
        display:none;
    }
    .pdf-link{
        text-overflow:ellipsis;
        overflow: hidden;
        position: relative;
        display: inline-block;
        white-space: nowrap;
        width:90px;
    }
    .upload-files{
        max-width: 200px;
        font-size: 12px;
    }
    h5{
        margin-top:20px;
    }
</style>
@endsection

