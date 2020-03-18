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
                        <th class="text-nowrap w-25">{{ __('Name') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Test Cases') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($useCases as $useCase)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('admin.use-cases.show', $useCase) }}">{{ $useCase->name }}</a>
                        </td>
                        <td>

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
            @include('components.grid.pagination', ['paginator' => $useCases])
        </div>
    </div>
@endsection
