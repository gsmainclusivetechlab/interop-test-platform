@extends('layouts.errors')

@section('title', __('403 Forbidden'))
@section('code', '403')
@section('content')
    <p class="h4 text-muted font-weight-normal mb-7">
        {{ __($exception->getMessage() ?: 'We are sorry but you do not have permission to access this page') }}
    </p>
    <a class="btn btn-primary" href="{{ url()->previous() }}">
        <i class="fe fe-arrow-left mr-2"></i>{{ __('Go back') }}
    </a>
@endsection
