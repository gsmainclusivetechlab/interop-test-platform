@extends('layouts.errors')

@section('title', __('400 Unauthorized'))
@section('code', '400')
@section('content')
    <p class="h4 text-muted font-weight-normal mb-7">
        {{ __('We are sorry but your browser sent a request that this server could not understand') }}
    </p>
    <a class="btn btn-primary" href="{{ url()->previous() }}">
        <i class="fe fe-arrow-left mr-2"></i>{{ __('Go back') }}
    </a>
@endsection
