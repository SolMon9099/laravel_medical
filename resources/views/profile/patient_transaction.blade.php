@extends('layouts.master')

@section('title', 'Profile')
@section('content')
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Patient Transactions</h4>
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
                                            <div class="table-responsive">
                                                <table class="patients-referral-table table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Date</th>
                                                            <th>Patient</th>
                                                            <th>Attorney</th>
                                                            <th>Doctor</th>
                                                            <th>Status</th>
                                                            <th>Schedule</th>
                                                            <th>Signed doc</th>
                                                            <th>Result</th>
                                                            @if(Auth::user()->roles[0]->name == 'funding company')
                                                            <th>Paid</th>
                                                            @endif
                                                            @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney')
                                                            <th>Invoice</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transaction_data as $key=>$value)
                                                            <tr>
                                                                <td>{{++$key}}</td>
                                                                <td>{{$value->referral_date}}</td>
                                                                <td>{{$value->patient->name}}</td>
                                                                <td>{{$value->attorney->name}}</td>
                                                                <td>{{$value->doctor->name}}</td>
                                                                <td>
                                                                    <span class="{{config('const.status_class')[$value->status]}}">{{config('const.status')[$value->status]}}</span>
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->schedule))
                                                                        <div>{{date('m/d/y H:i', strtotime($value->schedule->start_date))}} ～ {{date('H:i', strtotime($value->schedule->end_date))}}</div>
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
                                                                    @if(isset($value->files) && count($value->files) > 0)
                                                                        @foreach ($value->files as $val)
                                                                            <div><a target="_blank" href="{{ asset('uploads/sign/'.$val->files) }}">{{$val->files}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Booked'))
                                                                            <form action="{{route('profiles.upload_sign_docs')}}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                                <input class="form-control" type="file" required name="files[]" accept="application/pdf" />
                                                                                <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Docs</button>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->result_files) && count($value->result_files) > 0)
                                                                        @foreach ($value->result_files as $val)
                                                                            <div><a target="_blank" href="{{ asset('uploads/results/'.$val->result_file) }}">{{$val->result_file}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Signed') && (Auth::user()->roles[0]->name == 'doctor' || Auth::user()->roles[0]->name == 'technician'))
                                                                        <form action="{{route('profiles.upload_result_docs')}}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                            <input class="form-control" type="file" required name="result_files[]" accept="application/pdf" />
                                                                            <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Result</button>
                                                                        </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->roles[0]->name == 'funding company')
                                                                <td>
                                                                    @if($value->status == config('const.status_code')['Test Done'])
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
                                                                @if(Auth::user()->roles[0]->name == 'funding company' || Auth::user()->roles[0]->name == 'patient' || Auth::user()->roles[0]->name == 'attorney')
                                                                <td>
                                                                    @if(isset($value->invoice_files) && count($value->invoice_files) > 0)
                                                                        @foreach ($value->invoice_files as $val)
                                                                            <div><a target="_blank" href="{{ asset('storage/invoice/'.$val->invoice_file) }}">{{$val->invoice_file}}</a></div>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
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
</style>
@endsection

