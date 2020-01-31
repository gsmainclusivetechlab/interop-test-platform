@extends('layouts.auth')

@section('title', __('Create new account'))
@section('content')
    <form class="card" action="{{ route('register') }}" method="POST">
        @csrf
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <div class="form-group">
                <label class="form-label">{{ __('First name') }}</label>
                <input name="first_name" value="{{ old('first_name') }}" type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => 'John']) }}">
                @error('first_name')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('Last name') }}</label>
                <input name="last_name" value="{{ old('last_name') }}" type="text" class="form-control @error('last_name') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => 'Doe']) }}">
                @error('last_name')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('Email') }}</label>
                <input name="email" value="{{ old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => 'john.doe@email.com']) }}">
                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('Company') }}</label>
                <input name="company" value="{{ old('company') }}" type="text" class="form-control @error('company') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => env('APP_COMPANY_NAME')]) }}">
                @error('company')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('Password') }}</label>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('Confirm password') }}</label>
                <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                @error('password_confirmation')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="custom-control custom-checkbox @error('terms') is-invalid @enderror">
                    <input name="terms" type="checkbox" class="custom-control-input"  {{ old('terms') ? 'checked' : '' }}>
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
