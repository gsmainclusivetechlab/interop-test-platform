@extends('layouts.default')

@section('title', __('Configure components'))

@section('content')
    @include('sessions.register.includes.header')
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <form class="w-100 flow-chart-wrapper" action="{{ route('sessions.register.configuration.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        @include('sessions.register.includes.components')
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-3">
                                @include('sessions.register.includes.components-info')
                            </div>
                            <div class="col-9">
                                <div class="flow-chart-content">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">@yield('title')</h3>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ __('Please add your custom URL for the component(s) you want to use as SUT and select the existing Simulators for the other Components') }}</p>
                                            <strong>{{ __('Note: at least 1 component should be selected as SUT.') }}</strong>

                                            <div class="mt-4">
                                                <div class="form-group">
                                                    <strong class="d-inline-block mb-1">{{ __('Service Provider') }}</strong>
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <label class="form-label font-weight-normal" for="sp">{{ __('Type') }}</label>
                                                            <select class="form-control custom-select" id="sp" name="sp" @change="handleSessionComponentsSelect">
                                                                <option value="simulator">Simulator</option>
                                                                <option value="">SUT</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-7">
                                                            <label class="form-label font-weight-normal" for="sp-url">{{ __('URL') }}</label>
                                                            <input class="form-control" id="sp-url" name="sp-url" value="https://sp.com" disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <strong class="d-inline-block mb-1">{{ __('Mobile Money Operator 1') }}</strong>
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <label class="form-label font-weight-normal" for="mmo1">{{ __('Type') }}</label>
                                                            <select class="form-control custom-select" id="mmo1" name="mmo1" @change="handleSessionComponentsSelect">
                                                                <option value="">SUT</option>
                                                                <option value="malawi">Malawi v.1.0.0</option>
                                                                <option value="mtn">MTN v.1.2.0</option>
                                                                <option value="vodafone">Vodafone v.2.2.0</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-7">
                                                            <label class="form-label font-weight-normal" for="mmo1-url">{{ __('URL') }}</label>
                                                            <input class="form-control" id="mmo1-url" name="mmo1-url" value="https://mymmo.com">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <strong class="d-inline-block mb-1">{{ __('Mojaloop') }}</strong>
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <label class="form-label font-weight-normal" for="mojaloop">{{ __('Type') }}</label>
                                                            <select class="form-control custom-select" id="mojaloop" name="mojaloop" @change="handleSessionComponentsSelect">
                                                                <option value="mojaloop v.7.0.0">Mojaloop v.7.0.0</option>
                                                                <option value="mojaloop v.8.8.0">Mojaloop v.8.8.0</option>
                                                                <option value="mojaloop v.9.2.0">Mojaloop v.9.2.0</option>
                                                                <option value="">SUT</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-7">
                                                            <label class="form-label font-weight-normal" for="mojaloop-url">{{ __('URL') }}</label>
                                                            <input class="form-control" id="mojaloop-url" name="mojaloop-url" value="https://mojaloop.interop.gsmainclusivetechlab.io" disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <strong class="d-inline-block mb-1">{{ __('Mobile Money Operator 2') }}</strong>
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <label class="form-label font-weight-normal" for="mmo2">{{ __('Type') }}</label>
                                                            <select class="form-control custom-select" id="mmo2" name="mmo2" @change="handleSessionComponentsSelect">
                                                                <option value="mtn">MTN v.1.2.0</option>
                                                                <option value="malawi">Malawi v.1.0.0</option>
                                                                <option value="vodafone">Vodafone v.2.2.0</option>
                                                                <option value="">SUT</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-7">
                                                            <label class="form-label font-weight-normal" for="mmo2-url">{{ __('URL') }}</label>
                                                            <input class="form-control" id="mmo2-url" name="mmo2-url" value="https://mmo2.interop.gsmainclusivetechlab.io" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('sessions.register.selection.create') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
                                        <button type="submit" class="btn btn-primary">{{ __('Next') }}</button>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
