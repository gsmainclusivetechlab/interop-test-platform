@extends('layouts.admin.scenario')

@section('title', $scenario->name)

@section('content')
    <div class="card">
        <div class="card-header">
            @include('components.grid.search')
        </div>
        <div class="table-responsive mb-0">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="text-nowrap">{{ __('Name') }}</th>
                        <th class="text-nowrap">{{ __('API Service') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($components as $component)
                    <tr>
                        <td class="text-break">
                            <a href="#">{{ $component->name }}</a>
                        </td>
                        <td class="text-break">
                            @if ($component->apiService)
                                {{ $component->apiService->name }} {{ $component->apiService->version }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="2">
                            {{ __('No Results') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            @include('components.grid.pagination', ['paginator' => $components])
        </div>
    </div>
@endsection
