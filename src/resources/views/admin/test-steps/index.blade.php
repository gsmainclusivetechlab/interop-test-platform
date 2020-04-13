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
                        <th class="text-nowrap">{{ __('Api Scheme') }}</th>
                        <th class="text-nowrap">{{ __('Test Scripts') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($testSteps as $testStep)
                    <tr>
                        <td class="text-break">
                            <a href="#">
                                {{ $testStep->forward }} {{ $testStep->backward }}
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
                        <td>
                            @if ($testStep->apiScheme)
                                <a href="#">
                                    {{ $testStep->apiScheme->name }}
                                </a>
                            @endif
                        </td>
                        <td>
                            {{ $testStep->test_scripts_count }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="5">
                            {{ __('No Results') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($testSteps->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($testSteps->currentPage() - 1) * $testSteps->perPage()) + 1,
                            'to' => (($testSteps->currentPage() - 1) * $testSteps->perPage()) + $testSteps->count(),
                            'total' => $testSteps->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $testSteps->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
