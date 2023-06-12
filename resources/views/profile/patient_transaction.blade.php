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
                                                            <th>Patient Name</th>
                                                            <th>Attorney Name</th>
                                                            <th>Doctor Name</th>
                                                            <th>Status</th>
                                                            <th>Schedule</th>
                                                            <th>Signed doc</th>
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
                                                                            <div><a target="_blank" href="{{ asset('uploads/'.$val->files) }}">{{$val->files}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        @if($value->status == config('const.status_code.Booked'))
                                                                            <form action="{{route('profiles.upload_sign_docs')}}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <input type="hidden" value = {{$value->id}} name="transaction_id" />
                                                                                <input type="file" required name="files[]" accept="application/pdf" /><br/>
                                                                                <button style="margin-top:10px;" type="submit" class="btn btn-sm btn-primary waves-effect">Upload Docs</button>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                </td>
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
                                <!-- reload button -->
                                {{-- <div class="row">
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-sm btn-primary block-element border-0 mb-1">Load More</button>
                                    </div>
                                </div> --}}
                                <!--/ reload button -->
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

