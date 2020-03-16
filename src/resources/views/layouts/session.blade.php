@extends('layouts.app')

@section('content')
    <div class="row border-bottom">
        <div class="col-9">
            <div class="page-header m-0 pb-5">
                <h1 class="page-title">
                    <b>{{ $session->name }}</b>
                </h1>
                <div class="ml-4 pt-1">
                    {{ __('Execution') }}:
                    <i class="fe fe-briefcase"></i>
                    <small>{{ $session->testCases->unique('use_case_id')->count() }}</small>
                    <i class="fe fe-file-text"></i>
                    <small>{{ $session->testCases->count() }}</small>
                </div>
                <div class="col-2">
                    @include('sessions.includes.runs-progress', $session)
                </div>
            </div>
        </div>
        <div class="col-3">
            @yield('session-header-right')
        </div>
    </div>
    <div class="row align-items-start">
        <div class="col-3 flex-fill bg-white p-0">
            @yield('session-sidebar')
        </div>
        <div class="col-9 mt-3">
            @yield('session-content')
        </div>
    </div>
@endsection
