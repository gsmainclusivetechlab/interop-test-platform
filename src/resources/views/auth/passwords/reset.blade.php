@extends('layouts.auth')

@section('title', __('Reset password'))
@section('content')
    <form class="card" action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <p class="text-muted">
                {{ __('You can set a new password below. Please take the time to choose a secure and difficult to guess password.') }}
            </p>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Email') }}
                </label>
                <input name="email" value="{{ $email ?? old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => 'john.doe@email.com']) }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Password') }}
                </label>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Confirm password') }}
                </label>
                <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                @error('password_confirmation')
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
