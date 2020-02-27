@extends('layouts.base')

@push('styles')
    <link href="{{ mix('css/vendor.css', 'assets') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ mix('js/app.js', 'assets') }}" defer></script>
@endpush

@section('page')
    <div id="app"></div>
@endsection
