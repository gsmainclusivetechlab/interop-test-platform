@extends('layouts.app')

@section('title', __('Users'))

@section('content')
    <div class="page-header">
        <h1 class="page-title">@yield('title')</h1>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                        <thead>
                        <tr>
                            <th class="text-center w-1"><i class="icon-people"></i></th>
                            <th>User</th>
                            <th>Usage</th>
                            <th class="text-center">Payment</th>
                            <th>Activity</th>
                            <th class="text-center">Satisfaction</th>
                            <th class="text-center"><i class="icon-settings"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center">
                                <div class="avatar d-block" style="background-image: url(demo/faces/female/26.jpg)">
                                    <span class="avatar-status bg-green"></span>
                                </div>
                            </td>
                            <td>
                                <div>Elizabeth Martin</div>
                                <div class="small text-muted">
                                    Registered: Mar 7, 2019
                                </div>
                            </td>
                            <td>
                                <div class="clearfix">
                                    <div class="float-left">
                                        <strong>42%</strong>
                                    </div>
                                    <div class="float-right">
                                        <small class="text-muted">Jun 11, 2015 - Jul 10, 2015</small>
                                    </div>
                                </div>
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-yellow" role="progressbar" style="width: 42%" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <i class="payment payment-visa"></i>
                            </td>
                            <td>
                                <div class="small text-muted">Last login</div>
                                <div>4 minutes ago</div>
                            </td>
                            <td class="text-center">
                                <div class="mx-auto chart-circle chart-circle-xs" data-value="0.42" data-thickness="3" data-color="blue"><canvas width="40" height="40"></canvas>
                                    <div class="chart-circle-value">42%</div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="item-action dropdown">
                                    <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-tag"></i> Action </a>
                                        <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Another action </a>
                                        <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-message-square"></i> Something else here</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> Separated link</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
