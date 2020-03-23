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
            <div class="card-options">
                <a href="{{ route('admin.scenarios.test-cases.import', $scenario) }}" class="btn btn-primary">
                    <i class="fe fe-upload mr-2"></i>
                    {{ __('Import') }}
                </a>
            </div>
        </div>
        <div class="table-responsive mb-0">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="text-nowrap">{{ __('Name') }}</th>
                        <th class="text-nowrap">{{ __('Use Case') }}</th>
                        <th class="text-nowrap">{{ __('Test Steps') }}</th>
                        <th class="text-nowrap w-1"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($testCases as $testCase)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('admin.test-cases.show', $testCase) }}">{{ $testCase->name }}</a>
                        </td>
                        <td class="text-break">
                            <a href="#">{{ $testCase->useCase->name }}</a>
                        </td>
                        <td>
                            {{ $testCase->test_steps_count }}
                        </td>
                        <td class="text-center text-break">
                            <b-dropdown class="item-action" no-caret right toggle-class="icon text-decoration-none py-0" variant="link" boundary="window">
                                <template v-slot:button-content>
                                    <i class="fe fe-more-vertical"></i>
                                </template>
                                @can('delete', $testCase)
                                    <b-dropdown-form action="{{ route('admin.test-cases.destroy', $testCase) }}" method="POST" form-class="p-0">
                                        @csrf
                                        @method('DELETE')
                                        <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm delete') }}" text="{{ __('Are you sure you want to delete :name?', ['name' => $testCase->name]) }}">
                                            {{ __('Delete') }}
                                        </confirm-button>
                                    </b-dropdown-form>
                                @endcan
                            </b-dropdown>
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
        @if ($testCases->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($testCases->currentPage() - 1) * $testCases->perPage()) + 1,
                            'to' => (($testCases->currentPage() - 1) * $testCases->perPage()) + $testCases->count(),
                            'total' => $testCases->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $testCases->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
