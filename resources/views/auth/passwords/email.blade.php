@extends('layouts.authLayout')

@section('content')
<!-- Forgot Password basic -->
<div class="card mb-0">
    <div class="card-body">
        @include('auth.logo')

        <h4 class="card-title mb-1">Forgot Password? 🔒</h4>
        <p class="card-text mb-2">Enter your email and we'll send you instructions to reset your password</p>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        <form class="auth-forgot-password-form mt-2" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-1">
                <label for="forgot-password-email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email"  value="{{ old('email') }}"
                placeholder="john@example.com" aria-describedby="email" tabindex="1" autofocus />
            </div>
            @include('layouts.error')
           
            <button class="btn btn-primary w-100" tabindex="2">Send reset link</button>
        </form>

        <p class="text-center mt-2">
            <a href="{{ route('login')}}"> <i data-feather="chevron-left"></i> Back to login </a>
        </p>
    </div>
</div>
<!-- /Forgot Password basic -->
@endsection
@section('custom-js')
<!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/pages/auth-forgot-password.js') }}"></script>
<!-- END: Page JS-->
@endsection