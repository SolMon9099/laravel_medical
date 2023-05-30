@extends('layouts.authLayout')
@section('title', 'Login Page')
@section('page-style')
    
@endsection

@section('content')
<div class="card mb-0">
    <div class="card-body">
        @include('auth.logo')
        <h4 class="card-title mb-1 text-center">Welcome to Referral System 👋</h4>
        <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>
        <form class="auth-login-form mt-2" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-1">
                <label for="login-email" class="form-label">Email</label>
                <input type="email" class="form-control"
                                   id="email" placeholder="Email" name="email"
                                   value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-1">
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="login-password">Password</label>
                    <a href="{{ url('/password/reset') }}">
                        <small>Forgot Password?</small>
                    </a>
                </div>
                <div class="input-group input-group-merge form-password-toggle">
                    <input type="password" class="form-control form-control-merge" 
                        id="password" name="password" tabindex="2" 
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" 
                        aria-describedby="login-password" />
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                </div>

                @include('layouts.error')
            </div>
            
            <div class="mb-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                    id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} tabindex="3" />                    
                    <label class="form-check-label" for="remember">   {{ __('Remember Me') }} </label>
                </div>
            </div>
            <button class="btn btn-primary w-100" tabindex="4">Sign in</button>
        </form>

        {{-- <p class="text-center mt-2">
            <span>New on our platform?</span>
            <a href="#">
                <span>Create an account</span>
            </a>
        </p> --}}

        <div class="my-2">            
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/pages/auth-login.js') }}"></script>
<!-- END: Page JS-->
@endsection