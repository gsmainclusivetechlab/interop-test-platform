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
                    @hasSection('sidebar')
                        <div class="col-md-3">
                            @yield('sidebar')
                        </div>
                        <div class="col-md-9">
                            @yield('content')
                        </div>
                    @else
                        <div class="col-md-12">
                            @yield('content')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('layouts.includes.footer')
@endsection
