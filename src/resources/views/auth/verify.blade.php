@extends('layouts.auth')

@section('title', __('Verify Your Email Address'))
@section('content')
    @if (session('resent'))
        <b-alert show variant="success" dismissible>
            {{ __('A fresh verification link has been sent to your email address.') }}
        </b-alert>
    @endif
    <form class="card" action="{{ route('verification.resend') }}" method="POST">
        @csrf
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <p class="text-muted">
                {{ __('Before proceeding, please check your email for a verification link.') }}
            </p>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Click here to request another') }}</button>
            </div>
        </div>
    </form>
@endsection
