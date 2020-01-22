@extends('layouts.errors')

@section('title', __('401 Unauthorized'))
@section('code', '401')
@section('content')
    <p class="h4 text-muted font-weight-normal mb-7">
        {{ __('We are sorry but you are not authorized to access this page') }}
    </p>
    <a class="btn btn-primary" href="{{ url()->previous() }}">
        <i class="fe fe-arrow-left mr-2"></i>{{ __('Go back') }}
    </a>
@endsection
