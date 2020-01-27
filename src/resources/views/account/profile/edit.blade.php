@extends('layouts.account')

@section('title', __('Profile'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@yield('title')</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('account.profile.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="row align-items-center">
                        <label for="first_name" class="col-sm-2">{{ __('First name') }}:</label>
                        <div class="col-sm-10">
                            <input id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control @error('first_name') is-invalid @enderror">
                            @error('first_name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row align-items-center">
                        <label for="last_name" class="col-sm-2">{{ __('Last name') }}:</label>
                        <div class="col-sm-10">
                            <input id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control @error('last_name') is-invalid @enderror">
                            @error('last_name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row align-items-center">
                        <label for="company" class="col-sm-2">{{ __('Company') }}:</label>
                        <div class="col-sm-10">
                            <input id="company" name="company" value="{{ old('company', $user->company) }}" class="form-control @error('company') is-invalid @enderror">
                            @error('company')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="btn-list mt-4 text-right">
                    <button type="submit" class="btn btn-primary btn-space">{{ __('Save changes') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
