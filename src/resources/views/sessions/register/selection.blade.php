@extends('layouts.app')

@section('title', __('Select SUT'))

@section('content')
    @include('sessions.register.includes.header')
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div class="flow-chart-wrapper">
                    <div class="flow-chart mb-6">
                        <div class="flow-chart__item">
                            <div class="flow-chart__header">
                                Payer
                            </div>
                            <div class="flow-chart__actions">
                                <span class="flow-chart__btn ic-arrow-right"></span>
                                <span class="flow-chart__btn ic-arrow-right"></span>
                            </div>
                        </div>

                        <div class="flow-chart__item">
                            <a href="./sessions-step-1-2.html" class="flow-chart__header">
                                Service Provider
                            </a>
                            <div class="flow-chart__actions">
                                <span class="flow-chart__btn ic-arrow-right"></span>
                                <span class="flow-chart__btn ic-arrow-right"></span>
                            </div>
                        </div>

                        <div class="flow-chart__item">
                            <div class="flow-chart__header">
                                Mobile Money Operator 1
                            </div>
                            <div class="flow-chart__actions">
                                <span class="flow-chart__btn ic-arrow-right"></span>
                                <span class="flow-chart__btn ic-arrow-right"></span>
                            </div>
                        </div>

                        <div class="flow-chart__item">
                            <div class="flow-chart__header">
                                Mojaloop
                            </div>
                            <div class="flow-chart__actions">
                                <span class="flow-chart__btn ic-arrow-right"></span>
                                <span class="flow-chart__btn ic-arrow-right"></span>
                            </div>
                        </div>

                        <div class="flow-chart__item">
                            <div class="flow-chart__header">
                                Mobile Money Operator 2
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="flow-chart-legend d-flex flex-column">
                            <div class="d-inline-flex align-items-center">
                                <span class="ic-arrow-right mr-2 text-teal"></span>
                                <small>Mobile Money API</small>
                            </div>
                            <div class="d-inline-flex align-items-center">
                                <span class="ic-arrow-right mr-2 text-dark"></span>
                                Mojaloop API
                            </div>
                            <div class="d-inline-flex align-items-center">
                                <span class="ic-arrow-right mr-2"></span>
                                <small>Simulated</small>
                            </div>
                            <div class="d-inline-flex align-items-center">
                                <span class="ic-arrow-right mr-2 border-dashed"></span>
                                <small>Not Simulated</small>
                            </div>
                        </div>
                        <button type="button" class="btn" disabled="">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
