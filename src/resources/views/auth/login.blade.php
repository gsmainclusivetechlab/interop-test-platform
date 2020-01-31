@extends('layouts.auth')

@section('title', __('Login to your account'))
@section('content')
    <form class="card" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Email') }}
                </label>
                <input name="email" value="{{ old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => 'john.doe@email.com']) }}">
                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Password') }}
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="float-right small">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </label>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input name="remember" type="checkbox" class="custom-control-input" {{ old('remember') ? 'checked' : '' }}>
                    <span class="custom-control-label">{{ __('Remember me') }}</span>
                </label>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Login') }}
                </button>
            </div>
        </div>
    </form>
    @if (Route::has('register'))
        <div class="text-center text-muted">
            {{ __("Don't have account yet?") }}
            <a href="{{ route('register') }}">{{ __('Register') }}</a>
        </div>
    @endif
@endsection
