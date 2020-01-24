@extends('layouts.app')

@section('title', __('Sessions'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Owner') }}</th>
                                <th>{{ __('Use Cases') }}</th>
                                <th>{{ __('Test Cases') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Last Run') }}</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($sessions as $session)
                            <tr>
                                <td>
                                    <a href="#">{{ $session->name }}</a>
                                </td>
                                <td>
                                    <a href="#">{{ $session->user->name }}</a>
                                </td>
                                <td>0</td>
                                <td>0</td>
                                <td>
                                    @component('components.progress')
                                        {{--    @include('components.progress-bar', ['type' => 'success', 'value' => 35])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'danger', 'value' => 25])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'warning', 'value' => 25])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'secondary', 'value' => 15])--}}
                                    @endcomponent
                                </td>
                                <td></td>
                                <td class="text-center">

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">
                                    {{ __('No Results') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    @include('components.pagination', ['paginator' => $sessions])
                </div>
            </div>
        </div>
    </div>
@endsection
