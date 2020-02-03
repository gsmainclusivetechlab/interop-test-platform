@extends('layouts.base')

@push('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ mix('js/app.js') }}" defer></script>
@endpush

@section('page')
    <div class="flex-fill">
        @include('layouts.includes.header')
        <div class="my-3 my-md-5">
            <div class="container">
                @include('layouts.includes.flashes')
                <div class="row">
                    <div class="col-md-3">
                        <h1 class="page-title mb-5">
                            <b>{{ __('Settings') }}</b>
                        </h1>
                        <div>
                            <div class="list-group list-group-transparent mb-0">
                                <a href="{{ route('settings.profile.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('settings.profile.edit')) active @endif">
                                    {{ __('Profile') }}
                                </a>
                                <a href="{{ route('settings.password.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('settings.password.edit')) active @endif">
                                    {{ __('Change password') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.includes.footer')
@endsection
