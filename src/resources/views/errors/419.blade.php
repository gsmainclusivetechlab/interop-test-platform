@extends('layouts.errors')

@section('title', __('419 Page Expired'))
@section('code', '419')
@section('content')
    <p class="h4 text-muted font-weight-normal mb-7">
        {{ __('We are sorry but your session has expired') }}
    </p>
    <a class="btn btn-primary" href="{{ url()->previous() }}">
        <i class="fe fe-arrow-left mr-2"></i>{{ __('Go back') }}
    </a>
@endsection
