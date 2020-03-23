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
                                {{ $component->apiService->name }}
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
        @if ($components->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($components->currentPage() - 1) * $components->perPage()) + 1,
                            'to' => (($components->currentPage() - 1) * $components->perPage()) + $components->count(),
                            'total' => $components->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $components->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
