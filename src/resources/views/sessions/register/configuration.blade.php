@extends('layouts.default')

@section('title', __('Configure SUT'))

@section('content')
    @include('sessions.register.includes.header')
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <form class="w-100 flow-chart-wrapper" action="{{ route('sessions.register.configuration.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        @include('sessions.register.includes.components')
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-3">
                                @include('sessions.register.includes.components-info')
                            </div>
                            <div class="col-9">
                                <div class="flow-chart-content">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">@yield('title')</h3>
                                        </div>
                                        <div class="card-body">
                                            {{ __('No configuration needed') }}
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('sessions.register.selection.create') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
                                        <button type="submit" class="btn btn-primary">{{ __('Next') }}</button>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
