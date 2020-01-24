@extends('layouts.account')

@section('title', __('Profile'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@yield('title')</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row align-items-center">
                    <label class="col-sm-2">To:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
