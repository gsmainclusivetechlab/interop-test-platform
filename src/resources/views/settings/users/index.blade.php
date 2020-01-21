@extends('layouts.settings')

@section('title', __('Users'))

@section('content')
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
                    <tr>
                        <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(demo/faces/female/17.jpg)">
                                <span class="avatar-status bg-green"></span>
                            </div>
                        </td>
                        <td>
                            <div>Michelle Schultz</div>
                            <div class="small text-muted">
                                Registered: Feb 19, 2019
                            </div>
                        </td>
                        <td>
                            <div class="clearfix">
                                <div class="float-left">
                                    <strong>0%</strong>
                                </div>
                                <div class="float-right">
                                    <small class="text-muted">Jun 11, 2015 - Jul 10, 2015</small>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-red" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="payment payment-googlewallet"></i>
                        </td>
                        <td>
                            <div class="small text-muted">Last login</div>
                            <div>5 minutes ago</div>
                        </td>
                        <td class="text-center">
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.0" data-thickness="3" data-color="blue"><canvas width="40" height="40"></canvas>
                                <div class="chart-circle-value">0%</div>
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
                    <tr>
                        <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(demo/faces/female/21.jpg)">
                                <span class="avatar-status bg-green"></span>
                            </div>
                        </td>
                        <td>
                            <div>Crystal Austin</div>
                            <div class="small text-muted">
                                Registered: Mar 26, 2019
                            </div>
                        </td>
                        <td>
                            <div class="clearfix">
                                <div class="float-left">
                                    <strong>96%</strong>
                                </div>
                                <div class="float-right">
                                    <small class="text-muted">Jun 11, 2015 - Jul 10, 2015</small>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-green" role="progressbar" style="width: 96%" aria-valuenow="96" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="payment payment-mastercard"></i>
                        </td>
                        <td>
                            <div class="small text-muted">Last login</div>
                            <div>a minute ago</div>
                        </td>
                        <td class="text-center">
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.96" data-thickness="3" data-color="blue"><canvas width="40" height="40"></canvas>
                                <div class="chart-circle-value">96%</div>
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
                    <tr>
                        <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(demo/faces/male/32.jpg)">
                                <span class="avatar-status bg-green"></span>
                            </div>
                        </td>
                        <td>
                            <div>Douglas Ray</div>
                            <div class="small text-muted">
                                Registered: Jan 4, 2019
                            </div>
                        </td>
                        <td>
                            <div class="clearfix">
                                <div class="float-left">
                                    <strong>6%</strong>
                                </div>
                                <div class="float-right">
                                    <small class="text-muted">Jun 11, 2015 - Jul 10, 2015</small>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-red" role="progressbar" style="width: 6%" aria-valuenow="6" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="payment payment-shopify"></i>
                        </td>
                        <td>
                            <div class="small text-muted">Last login</div>
                            <div>a minute ago</div>
                        </td>
                        <td class="text-center">
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.06" data-thickness="3" data-color="blue"><canvas width="40" height="40"></canvas>
                                <div class="chart-circle-value">6%</div>
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
                    <tr>
                        <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(demo/faces/female/12.jpg)">
                                <span class="avatar-status bg-green"></span>
                            </div>
                        </td>
                        <td>
                            <div>Teresa Reyes</div>
                            <div class="small text-muted">
                                Registered: Feb 21, 2019
                            </div>
                        </td>
                        <td>
                            <div class="clearfix">
                                <div class="float-left">
                                    <strong>36%</strong>
                                </div>
                                <div class="float-right">
                                    <small class="text-muted">Jun 11, 2015 - Jul 10, 2015</small>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-red" role="progressbar" style="width: 36%" aria-valuenow="36" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="payment payment-ebay"></i>
                        </td>
                        <td>
                            <div class="small text-muted">Last login</div>
                            <div>2 minutes ago</div>
                        </td>
                        <td class="text-center">
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.36" data-thickness="3" data-color="blue"><canvas width="40" height="40"></canvas>
                                <div class="chart-circle-value">36%</div>
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
                    <tr>
                        <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(demo/faces/female/4.jpg)">
                                <span class="avatar-status bg-green"></span>
                            </div>
                        </td>
                        <td>
                            <div>Emma Wade</div>
                            <div class="small text-muted">
                                Registered: Mar 8, 2019
                            </div>
                        </td>
                        <td>
                            <div class="clearfix">
                                <div class="float-left">
                                    <strong>7%</strong>
                                </div>
                                <div class="float-right">
                                    <small class="text-muted">Jun 11, 2015 - Jul 10, 2015</small>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-red" role="progressbar" style="width: 7%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="payment payment-paypal"></i>
                        </td>
                        <td>
                            <div class="small text-muted">Last login</div>
                            <div>a minute ago</div>
                        </td>
                        <td class="text-center">
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.07" data-thickness="3" data-color="blue"><canvas width="40" height="40"></canvas>
                                <div class="chart-circle-value">7%</div>
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
                    <tr>
                        <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(demo/faces/female/27.jpg)">
                                <span class="avatar-status bg-green"></span>
                            </div>
                        </td>
                        <td>
                            <div>Carol Henderson</div>
                            <div class="small text-muted">
                                Registered: Feb 10, 2019
                            </div>
                        </td>
                        <td>
                            <div class="clearfix">
                                <div class="float-left">
                                    <strong>80%</strong>
                                </div>
                                <div class="float-right">
                                    <small class="text-muted">Jun 11, 2015 - Jul 10, 2015</small>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-green" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="payment payment-visa"></i>
                        </td>
                        <td>
                            <div class="small text-muted">Last login</div>
                            <div>9 minutes ago</div>
                        </td>
                        <td class="text-center">
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.8" data-thickness="3" data-color="blue"><canvas width="40" height="40"></canvas>
                                <div class="chart-circle-value">80%</div>
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
                    <tr>
                        <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(demo/faces/male/20.jpg)">
                                <span class="avatar-status bg-green"></span>
                            </div>
                        </td>
                        <td>
                            <div>Christopher Harvey</div>
                            <div class="small text-muted">
                                Registered: Jan 10, 2019
                            </div>
                        </td>
                        <td>
                            <div class="clearfix">
                                <div class="float-left">
                                    <strong>83%</strong>
                                </div>
                                <div class="float-right">
                                    <small class="text-muted">Jun 11, 2015 - Jul 10, 2015</small>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-green" role="progressbar" style="width: 83%" aria-valuenow="83" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="payment payment-googlewallet"></i>
                        </td>
                        <td>
                            <div class="small text-muted">Last login</div>
                            <div>8 minutes ago</div>
                        </td>
                        <td class="text-center">
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.83" data-thickness="3" data-color="blue"><canvas width="40" height="40"></canvas>
                                <div class="chart-circle-value">83%</div>
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
@endsection
