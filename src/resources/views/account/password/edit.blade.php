@extends('layouts.account')

@section('title', __('Change password'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@yield('title')</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('account.password.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="row align-items-center">
                        <label for="current_password" class="col-sm-3">{{ __('Current password') }}:</label>
                        <div class="col-sm-9">
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror">
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
                        <label for="password" class="col-sm-3">{{ __('New password') }}:</label>
                        <div class="col-sm-9">
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror">
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
                        <label for="password_confirmation" class="col-sm-3">{{ __('Confirm new password') }}:</label>
                        <div class="col-sm-9">
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror">
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
