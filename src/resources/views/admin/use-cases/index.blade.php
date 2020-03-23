@extends('layouts.admin.scenario')

@section('title', $scenario->name)

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
                        <th class="text-nowrap w-25">{{ __('Name') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Test Cases') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($useCases as $useCase)
                    <tr>
                        <td class="text-break">
                            <a href="#">{{ $useCase->name }}</a>
                        </td>
                        <td>
                            {{ $useCase->test_cases_count }}
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
        @if ($useCases->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($useCases->currentPage() - 1) * $useCases->perPage()) + 1,
                            'to' => (($useCases->currentPage() - 1) * $useCases->perPage()) + $useCases->count(),
                            'total' => $useCases->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $useCases->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
