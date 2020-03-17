@extends('layouts.admin.test-case')

@section('title', $testCase->name)

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
                        <th class="text-nowrap w-auto">{{ __('Created') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($testSteps as $testStep)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('admin.test-cases.show', $testStep) }}">{{ $testStep->name }}</a>
                        </td>
                        <td>
                            {{ $testCase->created_at }}
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
            @include('components.grid.pagination', ['paginator' => $testSteps])
        </div>
    </div>
@endsection
