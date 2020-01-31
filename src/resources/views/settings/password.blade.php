@extends('layouts.app')

@section('title', __('Change password'))

@section('sidebar', \Illuminate\Support\Facades\View::make('settings.includes.sidebar'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@yield('title')</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.password.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>{{ __('Current password') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                            @error('current_password')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>{{ __('New password') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                            @error('password')
                            <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>{{ __('Confirm new password') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => '**********']) }}">
                            @error('password_confirmation')
                            <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="btn-list mt-4 text-right">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="btn btn-link btn-space">{{ __('I forgot my password') }}</a>
                    @endif
                    <button type="submit" class="btn btn-primary btn-space">{{ __('Update password') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
