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
                    @include('components.grid.search')
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
                            <a href="#">{{ $session->owner->name }}</a>
                        </td>
                        <td>
                            {{ $session->use_cases_count }}
                        </td>
                        <td>
                            {{ $session->cases_count }}
                        </td>
                        <td class="text-break">
                            <b-progress class="rounded-0"></b-progress>
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
            @include('components.grid.pagination', ['paginator' => $sessions])
        </div>
    </div>
@endsection
