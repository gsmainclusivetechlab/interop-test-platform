@extends('layouts.base')

@push('styles')
    <link href="{{ mix('css/vendor.css', 'assets') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ mix('js/app.js', 'assets') }}" defer></script>
@endpush

@section('page')
    <div class="flex-fill">
        @include('layouts.includes.header')
        <div class="my-3 my-md-5">
            <div class="container-fluid">
                @include('layouts.includes.flashes')
                <div class="row">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.includes.footer')
@endsection
