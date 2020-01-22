@extends('layouts.errors')

@section('title', __('503 Service Unavailable'))
@section('code', '503')
@section('content')
    <p class="h4 text-muted font-weight-normal mb-7">
        {{ __($exception->getMessage() ?: 'We are sorry but the service is temporarily unavailable') }}
    </p>
@endsection
