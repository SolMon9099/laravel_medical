@extends('layouts.master')

@section('title', 'Change Password')

@section('content')
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Change Password</h4>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('layouts.error')
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('profiles.store_password') }}" method="POST" class="row gy-1 pt-75 editPasswordForm">
                        @csrf
                        <div class="col-12">
                            <label class="form-label" for="modalEditUserName">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" autofocus />
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="modalEditUserName">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" />
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="modalEditUserName">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" />
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
