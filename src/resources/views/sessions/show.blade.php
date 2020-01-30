@extends('layouts.app')

@section('title', __('Session :name', ['name' => $session->name]))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
@endsection
