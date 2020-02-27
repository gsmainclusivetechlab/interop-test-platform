@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <div class="page-header m-0 py-2">
        <h1 class="page-title">
            <b>{{ __('Latest sessions') }}</b>
        </h1>
    </div>
    <div class="row">
        @forelse ($sessions as $session)
            <div class="col-xl-3 col-md-6">
                @include('sessions.includes.short-detail', $session)
            </div>
        @empty
            <div class="col-12">
                <div class="card card-body bg-light">
                    {{ __('No Results') }}
                </div>
            </div>
        @endforelse
    </div>
@endsection
