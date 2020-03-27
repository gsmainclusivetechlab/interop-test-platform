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
        <link href="{{ asset('assets/tutorials/css/tutorials.css') }}" rel="stylesheet">
    @endpush
@endsection
