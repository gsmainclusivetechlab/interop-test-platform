@extends('layouts.default')

@section('title', __('Tutorials'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        @include('tutorials.includes.scenario-card')
        @include('tutorials.includes.scenario-accordion')
    </div>
    @push('scripts')
        <link href="{{ asset('assets/css/tutorials/tutorials.css') }}" rel="stylesheet">
        <script type="application/javascript" src="{{ asset('assets/js/tutorials/tutorials-create-session-demo.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/js/tutorials/tutorials-service-provider-demo.js') }}"></script>
    @endpush
@endsection
