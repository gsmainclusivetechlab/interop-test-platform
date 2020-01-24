@extends('layouts.auth')

@section('title', __('Confirm password'))
@section('content')
    <form class="card" action="{{ route('password.confirm') }}" method="POST">
        @csrf
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <p class="text-muted">
                {{ __('Please confirm your password before continuing.') }}
            </p>
            <div class="form-group">
                <label for="password" class="form-label">
                    {{ __('Password') }}
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="float-right small">
                            {{ __('I forgot password') }}
                        </a>
                    @endif
                </label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Confirm Password') }}
                </button>
            </div>
        </div>
    </form>
@endsection
