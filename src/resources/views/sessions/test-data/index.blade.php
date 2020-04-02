@extends('layouts.sessions.test-case', $session)

@section('title', $session->name)

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
                <a href="{{ route('sessions.test-cases.test-data.create', [$session, $testCase]) }}" class="btn btn-outline-primary">
                    <i class="fe fe-plus mr-2"></i>
                    {{ __('New Test Data') }}
                </a>
            </div>
        </div>
        <div class="table-responsive mb-0">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                <tr>
                    <th class="text-nowrap">{{ __('Name') }}</th>
                    <th class="text-nowrap">{{ __('Method') }}</th>
                    <th class="text-nowrap">{{ __('URI') }}</th>
                    <th class="text-nowrap w-1"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($testData as $testDatum)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('sessions.test-cases.test-data.edit', [$session, $testCase, $testDatum]) }}">{{ $testDatum->name }}</a>
                        </td>
                        <td class="text-break">
                            {{ $testDatum->method }}
                        </td>
                        <td class="text-break">
                            {{ $testDatum->uri }}
                        </td>
                        <td class="text-center text-break">
                            <b-dropdown class="item-action" no-caret right toggle-class="icon text-decoration-none py-0" variant="link" boundary="window">
                                <template v-slot:button-content>
                                    <i class="fe fe-more-vertical"></i>
                                </template>
                                @can('run', $testDatum)
                                    <b-dropdown-form action="{{ route('sessions.test-cases.test-data.run', [$session, $testCase, $testDatum]) }}" method="POST" form-class="p-0">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            {{ __('Run') }}
                                        </button>
                                    </b-dropdown-form>
                                @endcan

                                @can('delete', $testDatum)
                                    <b-dropdown-form action="{{ route('sessions.test-cases.test-data.destroy', [$session, $testCase, $testDatum]) }}" method="POST" form-class="p-0">
                                        @csrf
                                        @method('DELETE')
                                        <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm delete') }}" text="{{ __('Are you sure you want to delete :name?', ['name' => $testDatum->name]) }}">
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
        @if ($testData->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($testData->currentPage() - 1) * $testData->perPage()) + 1,
                            'to' => (($testData->currentPage() - 1) * $testData->perPage()) + $testData->count(),
                            'total' => $testData->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $testData->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
