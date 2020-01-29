@extends('layouts.app')

@section('title', __('Sessions'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        <div class="card-header">
            <form action="">
                <div class="input-icon">
                    @include('components.grids.search')
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
                            <a href="{{ route('sessions.show', ['session' => $session]) }}">{{ $session->name }}</a>
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
                        <td class="text-center" colspan="7">
                            {{ __('No Results') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            @include('components.grids.pagination', ['paginator' => $sessions])
        </div>
    </div>
@endsection
