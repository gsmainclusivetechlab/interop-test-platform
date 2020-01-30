@extends('layouts.app')

@section('title', __('Select SUT'))

@section('content')
    @include('sessions.register.includes.header')
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <form class="flow-chart-wrapper" action="{{ route('sessions.register.selection.store') }}" method="POST">
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
                                        <label class="form-label">
                                            {{ __('SUT') }}
                                        </label>
                                        <select class="form-control custom-select">
                                            <option value="sp">{{ __('Service Provider') }}</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('Now only Service Provider can be set as System Under Test.') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('Next') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
