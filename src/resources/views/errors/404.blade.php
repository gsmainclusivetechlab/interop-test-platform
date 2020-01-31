@extends('layouts.errors')

@section('title', __('404 Page Not Found'))
@section('code', '404')
@section('content')
    <p class="h4 text-muted font-weight-normal mb-7">
        {{ __('We are sorry but the page you were looking for does not exist.') }}
    </p>
    <a class="btn btn-primary" href="{{ url()->previous() }}">
        <i class="fe fe-arrow-left mr-2"></i>{{ __('Go back') }}
    </a>
@endsection
