@extends('layouts.app')

@section('title', __('Sessions'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="table-responsive">
                    @php
                        $columns = [
                            ['data' => 'name', 'type' => 'string'],
                            ['defaultContent' => '', 'orderable' => false, 'searchable' => false],
                            ['defaultContent' => '', 'orderable' => false, 'searchable' => false],
                            ['defaultContent' => '', 'orderable' => false, 'searchable' => false],
                            ['defaultContent' => '', 'orderable' => false, 'searchable' => false],
                        ];
                    @endphp
                    <table class="table table-hover table-outline table-vcenter card-table no-footer"
                           data-datatable=""
                           data-columns=@json($columns)
                           data-ajax="{{ route('settings.sessions.grid') }}"
                           data-processing="true"
                           data-server-side="true">
                        <thead>
                            <tr>
                                <th class="col-md-4">{{ __('Name') }}</th>
                                <th class="col-md-3">{{ __('Status') }}</th>
                                <th class="col-md-2">{{ __('Use Cases') }}</th>
                                <th class="col-md-2">{{ __('Test Cases') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
