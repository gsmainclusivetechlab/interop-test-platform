@extends('layouts.auth')

@section('title', __('Create new account'))
@section('content')
    <form class="card" action="{{ route('register') }}" method="POST">
        @csrf
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <div class="form-group">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" name="name" value="{{ old('name') }}" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Enter name') }}">
                @error('name')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" name="email" value="{{ old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Enter email') }}">
                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="company" class="form-label">{{ __('Company') }}</label>
                <input id="company" name="company" value="{{ old('company') }}" type="text" class="form-control @error('company') is-invalid @enderror" placeholder="{{ __('Enter company') }}">
                @error('company')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Enter password') }}">
                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">{{ __('Confirm password') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('Confirm password') }}">
                @error('password_confirmation')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="terms" class="custom-control custom-checkbox @error('terms') is-invalid @enderror">
                    <input id="terms" name="terms" type="checkbox" class="custom-control-input"  {{ old('terms') ? 'checked' : '' }}>
                    <span class="custom-control-label">
                        {{ __('Agree the') }}
                        <a href="{{ env('APP_COMPANY_LEGAL_URL') }}" target="_blank">{{ __('terms and policy') }}</a>
                    </span>
                </label>
                @error('terms')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Register') }}
                </button>
            </div>
        </div>
    </form>
    <div class="text-center text-muted">
        {{ __('Already have account?') }}
        <a href="{{ route('login') }}">{{ __('Login') }}</a>
    </div>
@endsection
