@extends('layouts.master')

@section('title', 'Profile')
<?php
    $schedule_object = [];
    foreach($schedule_data as $sch_item){
        $schedule_object[$sch_item->patient_transaction_id ] = $sch_item;
    }
?>
@section('content')
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Patient Profile</h4>
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
                                <!-- left profile info section -->
                                <div class="col-lg-3 col-12 order-2 order-lg-1">
                                    <!-- about -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="sub-title">About</h3>
                                            <div class="mt-2">
                                                <h5 class="mb-75">Name:</h5>
                                                <p class="card-text">{{Auth::user()->name}}</p>
                                            </div>
                                            <div class="mt-2">
                                                <h5 class="mb-75">Gender:</h5>
                                                <p class="card-text">{{Auth::user()->gender}}</p>
                                            </div>
                                            <div class="mt-2">
                                                <h5 class="mb-75">Email:</h5>
                                                <p class="card-text">{{Auth::user()->email}}</p>
                                            </div>
                                            <div class="mt-2">
                                                <h5 class="mb-75">Phone:</h5>
                                                <p class="card-text">{{Auth::user()->phone}}</p>
                                            </div>
                                            <div class="mt-2">
                                                <h5 class="mb-75">Address:</h5>
                                                <p class="card-text">{{Auth::user()->address. (Auth::user()->address_line2 != null? ', '. Auth::user()->address_line2:'' )}}, {{Auth::user()->city}}, {{Auth::user()->state}}, {{Auth::user()->postal}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ about -->
                                </div>
                                <!--/ left profile info section -->

                                <!-- center profile info section -->
                                <div class="col-lg-9 col-12 order-1 order-lg-2">
                                    <!-- post 1 -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="sub-title">Referal</h3>
                                            <div class="table-responsive">
                                                <table class="referral-table table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Date</th>
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
                                                                <td>{{$value->attorney->name}}</td>
                                                                <td>{{$value->doctor->name}}</td>
                                                                <td>
                                                                    <span class="text-primary">{{config('const.status')[$value->status]}}</span>
                                                                </td>
                                                                <td>
                                                                    @if(isset($schedule_object[$value->id]))
                                                                        <div>{{date('m/d/y H:i', strtotime($schedule_object[$value->id]->start_date))}} ～ {{date('H:i', strtotime($schedule_object[$value->id]->end_date))}}</div>
                                                                        <div>{{$schedule_object[$value->id]->title}}</div>
                                                                        @if ($schedule_object[$value->id]->description)
                                                                            <div>{{$schedule_object[$value->id]->description}}</div>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if(isset($value->files) && count($value->files) > 0)
                                                                        @foreach ($value->files as $val)
                                                                            <div><a href="{{ asset('uploads/'.$val->files) }}">{{$val->files}}</a></div>
                                                                        @endforeach
                                                                    @else
                                                                        {{-- <input type="file" name="files[]" accept="application/pdf"/> --}}
                                                                        <button>Upload Docs</button>
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
@endsection

