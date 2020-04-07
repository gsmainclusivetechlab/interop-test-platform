@extends('layouts.default')

@section('title', __('Dashboard'))

@section('content')
    <div class="page-header m-0 py-2">
        <h1 class="page-title">
            <b>{{ __('Latest sessions') }}</b>
        </h1>
    </div>
    <div class="row row-cards row-deck">
        @forelse ($sessions as $session)
            <div class="col-xl-3 col-md-4">
                <div class="card">
                    <div class="card-header flex-column align-items-start h-100 border-bottom py-4">
                        <div class="d-flex align-items-center w-100 mb-2">
                            <x-sessions.latest-test-runs-progress :session="$session" />
                        </div>
                        <h2 class="card-title w-100 text-truncate">
                            <a href="{{ route('sessions.show', $session) }}" class="text-decoration-none">
                                <b>{{ $session->name }}</b>
                            </a>
                        </h2>
                        <p class="mb-0">
                            {{ \Illuminate\Support\Str::limit($session->description) }}
                        </p>
                    </div>
                    <div class="card-body flex-shrink-0 py-4">
                        <ul class="list-unstyled">
                            <li>
                                <i class="fe fe-briefcase"></i>
                                {{ $session->testCases->unique('use_case_id')->count() }}
                            </li>
                            <li>
                                <i class="fe fe-file-text"></i>
                                {{ $session->testCases->count() }}
                            </li>
                            @if($session->lastTestRun)
                                <li>
                                    <i class="fe fe-calendar"></i>
                                    {{ $session->lastTestRun->created_at->diffForHumans() }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card card-body">
                    {{ __('No Results') }}
                </div>
            </div>
        @endforelse

        @if ($sessions->count())
            <div class="col-12">
                {{ $sessions->appends(request()->all())->links() }}
            </div>
        @endif
    </div>
@endsection
