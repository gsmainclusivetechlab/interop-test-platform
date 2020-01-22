@extends('layouts.errors')

@section('title', __('500 Server Error'))
@section('code', '500')
@section('content')
    <p class="h4 text-muted font-weight-normal mb-7">
        {{ __('We are sorry but your request contains bad syntax and cannot be fulfilled') }}
    </p>
    <a class="btn btn-primary" href="{{ url()->previous() }}">
        <i class="fe fe-arrow-left mr-2"></i>{{ __('Go back') }}
    </a>
@endsection
