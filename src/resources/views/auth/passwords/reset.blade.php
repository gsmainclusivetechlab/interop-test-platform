@extends('layouts.auth')

@section('title', __('Reset password'))
@section('content')
    <form class="card" action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <p class="text-muted">
                {{ __('Enter your email address and your password will be reset and emailed to you.') }}
            </p>
            <div class="form-group">
                <label for="email" class="form-label">
                    {{ __('Email') }}
                </label>
                <input id="email" name="email" value="{{ $email ?? old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Enter email') }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">
                    {{ __('Password') }}
                </label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Enter password') }}">
                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    {{ __('Confirm Password') }}
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="{{ __('Enter password again') }}">
                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Reset password') }}
                </button>
            </div>
        </div>
    </form>
@endsection
