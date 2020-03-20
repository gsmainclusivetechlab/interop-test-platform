@extends('layouts.default')

@section('title', __('Scenarios'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
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
                        <th class="text-nowrap">{{ __('Components') }}</th>
                        <th class="text-nowrap">{{ __('Use Cases') }}</th>
                        <th class="text-nowrap">{{ __('Test Cases') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($scenarios as $scenario)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('admin.scenarios.show', $scenario) }}">{{ $scenario->name }}</a>
                        </td>
                        <td>
                            {{ $scenario->components_count }}
                        </td>
                        <td>
                            {{ $scenario->use_cases_count }}
                        </td>
                        <td>
                            {{ $scenario->test_cases_count }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="4">
                            {{ __('No Results') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($scenarios->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($scenarios->currentPage() - 1) * $scenarios->perPage()) + 1,
                            'to' => (($scenarios->currentPage() - 1) * $scenarios->perPage()) + $scenarios->count(),
                            'total' => $scenarios->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $scenarios->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
