@extends('layouts.default')

@section('title', __('Scenarios'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        <div class="card-header">
            @include('components.grid.search')
        </div>
        <div class="table-responsive mb-0">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="text-nowrap w-25">{{ __('Name') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Description') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Created') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($scenarios as $scenario)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('admin.scenarios.show', $scenario) }}">{{ $scenario->name }}</a>
                        </td>
                        <td class="text-break">
                            {{ \Illuminate\Support\Str::limit($scenario->description) }}
                        </td>
                        <td>
                            {{ $scenario->created_at }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="3">
                            {{ __('No Results') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            @include('components.grid.pagination', ['paginator' => $scenarios])
        </div>
    </div>
@endsection
