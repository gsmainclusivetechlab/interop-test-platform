@extends('layouts.default')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom pb-5 align-items-center">
                    <div class="col-6 d-flex flex-wrap">
                        <h1 class="page-title mr-2">
                            <b>{{ $session->name }}</b>
                        </h1>
                    </div>
                    <div class="ml-auto col-2">
                        <div class="mb-1">
                            {{ __('Execution') }}:
                            <i class="fe fe-briefcase"></i>
                            <small>{{ $session->testCases->unique('use_case_id')->count() }}</small>
                            <i class="fe fe-file-text"></i>
                            <small>{{ $session->testCases->count() }}</small>
                        </div>
                        <div style="min-width: 180px">
                            @include('sessions.includes.runs-progress', $session)
                        </div>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-3 mt-3 pr-0">
                        @yield('session-sidebar')
                    </div>
                    <div class="col-9 mt-3">
                        @yield('session-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
