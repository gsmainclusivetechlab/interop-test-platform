@extends('layouts.auth')

@section('title', __('Login to your account'))
@section('content')
    <form class="card" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <div class="form-group">
                <label for="email" class="form-label">{{ __('Email address') }}</label>
                <input id="email" name="email" value="{{ old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Enter email address') }}">
                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">
                    {{ __('Password') }}
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="float-right small">
                            {{ __('I forgot password') }}
                        </a>
                    @endif
                </label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Enter password') }}">
                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label id="remember" class="custom-control custom-checkbox">
                    <input id="remember" name="remember" type="checkbox" class="custom-control-input" {{ old('remember') ? 'checked' : '' }}>
                    <span class="custom-control-label">{{ __('Remember me') }}</span>
                </label>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Sign in') }}
                </button>
            </div>
        </div>
    </form>
@endsection
