@extends('layouts.app')

@section('title', __('Sessions'))

@section('content')
    <div class="page-header">
        <h1 class="page-title">@yield('title')</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Use Cases') }}</th>
                                <th>{{ __('Test Cases') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
