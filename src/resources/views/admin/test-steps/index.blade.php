@extends('layouts.admin.test-case')

@section('title', $testCase->name)

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ url()->current() }}" method="GET" class="input-icon">
                <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="{{ __('Search') }}...">
                <span class="input-icon-addon">
                    <i class="fe fe-search"></i>
                </span>
            </form>
        </div>
        <div class="table-responsive mb-0">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="text-nowrap">{{ __('Name') }}</th>
                        <th class="text-nowrap">{{ __('Source') }}</th>
                        <th class="text-nowrap">{{ __('Target') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($testSteps as $testStep)
                    <tr>
                        <td class="text-break">
                            <a href="#">
                                {{ $testStep->name }}
                            </a>
                        </td>
                        <td>
                            <a href="#">
                                {{ $testStep->source->name }}
                            </a>
                        </td>
                        <td>
                            <a href="#">
                                {{ $testStep->target->name }}
                            </a>
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
            @include('components.grid.pagination', ['paginator' => $testSteps])
        </div>
    </div>
@endsection
