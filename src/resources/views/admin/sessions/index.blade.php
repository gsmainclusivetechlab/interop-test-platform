@extends('layouts.default')

@section('title', __('Sessions'))

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
                        <th class="text-nowrap w-25">{{ __('Name') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Owner') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Use Cases') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Test Cases') }}</th>
                        <th class="text-nowrap w-25">{{ __('Status') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Last Run') }}</th>
                        <th class="text-nowrap w-1"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($sessions as $session)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('sessions.show', $session) }}">{{ $session->name }}</a>
                        </td>
                        <td class="text-break">
                            <a href="#">{{ $session->owner->name }}</a>
                        </td>
                        <td>
                            {{ $session->testCases->unique('use_case_id')->count() }}
                        </td>
                        <td>
                            {{ $session->testCases->count() }}
                        </td>
                        <td>
                            @include('sessions.includes.runs-progress', $session)
                        </td>
                        <td>
                            @if($session->lastTestRun)
                                {{ $session->lastTestRun->created_at->diffForHumans() }}
                            @endif
                        </td>
                        <td class="text-center">
                            @canany(['delete'], $session)
                                <b-dropdown class="item-action" no-caret right toggle-class="icon text-decoration-none py-0" variant="link" boundary="window">
                                    <template v-slot:button-content>
                                        <i class="fe fe-more-vertical"></i>
                                    </template>
                                    @can('delete', $session)
                                        <b-dropdown-form action="{{ route('sessions.destroy', $session) }}" method="POST" form-class="p-0">
                                            @csrf
                                            @method('DELETE')
                                            <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm delete') }}" text="{{ __('Are you sure you want to delete :name?', ['name' => $session->name]) }}">
                                                {{ __('Delete') }}
                                            </confirm-button>
                                        </b-dropdown-form>
                                    @endcan
                                </b-dropdown>
                            @endcanany
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="7">
                            {{ __('No Results') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($sessions->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($sessions->currentPage() - 1) * $sessions->perPage()) + 1,
                            'to' => (($sessions->currentPage() - 1) * $sessions->perPage()) + $sessions->count(),
                            'total' => $sessions->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $sessions->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
