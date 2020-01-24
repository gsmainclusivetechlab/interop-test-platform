@extends('layouts.app')

@section('title', __('Sessions'))

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @yield('title')
        </h1>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form action="">
                        <div class="input-icon">
                            <input type="text" class="form-control" placeholder="Search for...">
                            <span class="input-icon-addon">
                              <i class="fe fe-search"></i>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover card-table">
                        <thead class="thead-light">
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
                                <td class="text-break">
                                    <a href="#">{{ $session->name }}</a>
                                </td>
                                <td class="text-break">
                                    <a href="#">{{ $session->user->name }}</a>
                                </td>
                                <td class="text-break">0</td>
                                <td class="text-break">0</td>
                                <td class="text-break">
                                    @component('components.progress')
                                        {{--    @include('components.progress-bar', ['type' => 'success', 'value' => 35])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'danger', 'value' => 25])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'warning', 'value' => 25])--}}
                                        {{--    @include('components.progress-bar', ['type' => 'secondary', 'value' => 15])--}}
                                    @endcomponent
                                </td>
                                <td class="text-break"></td>
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
