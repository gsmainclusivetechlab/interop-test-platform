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
                            ['data' => 'use_cases_count', 'type' => 'integer', 'orderable' => false, 'searchable' => false],
                            ['data' => 'test_cases_count', 'type' => 'integer', 'orderable' => false, 'searchable' => false],
                            ['data' => 'status', 'orderable' => false, 'searchable' => false],
                            ['defaultContent' => '', 'orderable' => false, 'searchable' => false],
                            ['defaultContent' => '', 'orderable' => false, 'searchable' => false],
                        ];
                    @endphp
                    <table class="table table-hover table-outline table-vcenter card-table no-footer"
                           data-datatable=""
                           data-columns=@json($columns)
                           data-ajax="{{ route('settings.sessions.datatable') }}"
                           data-processing="true"
                           data-server-side="true">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Use Cases') }}</th>
                                <th>{{ __('Test Cases') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Last Run') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
