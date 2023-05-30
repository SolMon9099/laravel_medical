@extends('layouts.authLayout')

@section('content')
<div class="card mb-0">
    <div class="card-body">
        @include('auth.logo')

        <h4 class="card-title mb-1">Reset Password 🔒</h4>
        <p class="card-text mb-2">Your new password must be different from previously used passwords</p>

        <form class="auth-reset-password-form mt-2" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-1">
                <label for="login-email" class="form-label">Email</label>
                <input type="email" class="form-control" readonly
                                   id="email" placeholder="Email" name="email"
                                   value="{{ $email ?? old('email') }}" required>
            </div>
            <div class="mb-1">
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="reset-password-new">New Password</label>
                </div>
                <div class="input-group input-group-merge form-password-toggle">
                    <input type="password" class="form-control form-control-merge" id="password" name="password" 
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" tabindex="1" autofocus />
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                </div>
            </div>
            <div class="mb-1">
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                </div>
                <div class="input-group input-group-merge form-password-toggle">
                    <input type="password" class="form-control form-control-merge" id="password_confirmation" name="password_confirmation" 
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password_confirmation" tabindex="2" />
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                </div>
            </div>
            @include('layouts.error')
            <button class="btn btn-primary w-100" tabindex="3">Set New Password</button>
        </form>

        <p class="text-center mt-2">
            <a href="{{ route('login') }}"> <i data-feather="chevron-left"></i> Back to login </a>
        </p>
    </div>
</div>
@endsection

@section('custom-js')
<!-- BEGIN: Page JS-->
<script src="{{ asset('app-assets/js/scripts/pages/auth-reset-password.js') }}"></script>
<!-- END: Page JS-->
@endsection