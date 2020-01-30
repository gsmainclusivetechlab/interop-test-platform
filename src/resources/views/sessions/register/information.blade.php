@extends('layouts.app')

@section('title', __('Session info'))

@section('content')
    @include('sessions.register.includes.header')
    <div class="row d-flex justify-content-center">
        <div class="col">
            <form action="{{ route('sessions.register.information.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-header border-0">
                                <h3 class="card-title">@yield('title')</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">
                                        {{ __('Name') }}
                                    </label>
                                    <input name="name" value="{{ old('name') }}" type="text" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        {{ __('SUT') }}
                                    </label>
                                    <input type="text" disabled class="form-control" value="Service Provider">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card-header border-0">
                                <h3 class="card-title">{{ __('Select use cases') }}</h3>
                            </div>

                            <div class="card-body">
                                @foreach($suites as $suite)
                                    <ul class="list-group">
                                        @foreach($suite->cases()->get() as $case)
                                            <li class="list-group-item border-0 py-0">
                                                <label class="custom-control custom-checkbox d-inline-block mb-0 pt-3 pb-2">
                                                    <input name="cases[]" value="{{ $case->id }}" type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label font-weight-medium">
                                                        {{ $case->name }}
                                                    </span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach

                                @error('cases')
                                <span class="text-danger">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('sessions.register.configuration.create') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
