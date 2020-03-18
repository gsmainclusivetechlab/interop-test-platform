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
            <div class="card-options">
                <a href="{{ route('admin.test-cases.test-steps.import', $testCase) }}" class="btn btn-primary">
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
                        <th class="text-nowrap">{{ __('Source') }}</th>
                        <th class="text-nowrap">{{ __('Target') }}</th>
                        <th class="text-nowrap w-1"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($testSteps as $testStep)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('admin.test-cases.show', $testStep) }}">
                                {{ $testStep->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.components.show', $testStep->source) }}">
                                {{ $testStep->source->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.components.show', $testStep->target) }}">
                                {{ $testStep->target->name }}
                            </a>
                        </td>
                        <td class="text-center text-break">
                            <b-dropdown class="item-action" no-caret right toggle-class="icon text-decoration-none py-0" variant="link" boundary="window">
                                <template v-slot:button-content>
                                    <i class="fe fe-more-vertical"></i>
                                </template>
                                @can('delete', $testStep)
                                    <b-dropdown-form action="{{ route('admin.test-steps.destroy', $testStep) }}" method="POST" form-class="p-0">
                                        @csrf
                                        @method('DELETE')
                                        <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm delete') }}" text="{{ __('Are you sure you want to delete :name?', ['name' => $testStep->name]) }}">
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
        <div class="card-footer">
            @include('components.grid.pagination', ['paginator' => $testSteps])
        </div>
    </div>
@endsection
