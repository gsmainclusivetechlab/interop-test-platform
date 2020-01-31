@extends('layouts.base')

@push('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ mix('js/app.js') }}" defer></script>
@endpush

@section('page')
    <div class="page-content">
        <div class="container text-center">
            <div class="display-1 text-muted mb-5"><i class="si si-exclamation"></i> @yield('code')</div>
            <h1 class="h2 mb-3">
                {{ __('Oops.. You just found an error page..') }}
            </h1>
            @yield('content')
        </div>
    </div>
@endsection
