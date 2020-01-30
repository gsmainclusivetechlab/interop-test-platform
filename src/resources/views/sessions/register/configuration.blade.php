@extends('layouts.app')

@section('title', __('Configure SUT'))

@section('content')
    @include('sessions.register.includes.header')
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <form class="flow-chart-wrapper" action="{{ route('sessions.register.configuration.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        @include('sessions.register.includes.components')
                    </div>
                    <div class="d-flex justify-content-between">
                        @include('sessions.register.includes.components-info')
                        <div class="flow-chart-content">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h3 class="card-title">@yield('title')</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <h5>
                                            {{ __('Service Provider') }} > {{ __('Mobile Money Operator 1') }}
                                        </h5>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">
                                            {{ __('URL') }}
                                        </label>
                                        <input type="text" disabled class="form-control" value="https://sandbox.mobilemoneyapi.io/v1.0/passthrough/mm">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">
                                            {{ __('Key') }}
                                        </label>
                                        <input type="text" class="form-control">
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <h5>
                                            {{ __('Mobile Money Operator 1') }} < {{ __('Service Provider') }}
                                        </h5>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">
                                            {{ __('Key') }}
                                        </label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('sessions.register.selection.create') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Next') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
