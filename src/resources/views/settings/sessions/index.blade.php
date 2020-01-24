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
                    <table class="table table-striped card-table">
                        <thead>
                            <tr>
                                <th class="text-nowrap w-25">{{ __('Name') }}</th>
                                <th class="text-nowrap w-25">{{ __('Owner') }}</th>
                                <th class="text-nowrap w-auto">{{ __('Use Cases') }}</th>
                                <th class="text-nowrap w-auto">{{ __('Test Cases') }}</th>
                                <th class="text-nowrap w-25">{{ __('Status') }}</th>
                                <th class="text-nowrap w-auto">{{ __('Last Run') }}</th>
                                <th class="text-nowrap w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($sessions as $session)
                            <tr>
                                <td class="w-25 text-break">
                                    <a href="#">{{ $session->name }}</a>
                                </td>
                                <td class="w-25 text-break">
                                    <a href="#">{{ $session->user->name }}</a>
                                </td>
                                <td class="w-auto text-break">0</td>
                                <td class="w-auto text-break">0</td>
                                <td class="w-25 text-break">
                                    @component('components.progress')
                                        {{--    @include('components.progress-bar', ['type' => 'success', 'value' => 35])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'danger', 'value' => 25])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'warning', 'value' => 25])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'secondary', 'value' => 15])--}}
                                    @endcomponent
                                </td>
                                <td class="w-auto text-break"></td>
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
