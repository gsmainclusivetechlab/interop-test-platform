@extends('layouts.app')

@section('title', __('Profile'))

@section('sidebar', \Illuminate\Support\Facades\View::make('account.includes.sidebar'))

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
                        <label for="first_name" class="col-sm-3">
                            <b>{{ __('First name') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => 'John']) }}">
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
                        <label for="last_name" class="col-sm-3">
                            <b>{{ __('Last name') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => 'Doe']) }}">
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
                        <label for="company" class="col-sm-3">
                            <b>{{ __('Company') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input id="company" name="company" value="{{ old('company', auth()->user()->company) }}" class="form-control @error('company') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => env('APP_COMPANY_NAME')]) }}">
                            @error('company')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="btn-list mt-4 text-right">
                    <button type="submit" class="btn btn-primary btn-space">{{ __('Update profile') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
