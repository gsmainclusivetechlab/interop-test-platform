@extends('layouts.sessions.test-case', $session)

@section('title', $session->name)

@section('content')
	<ul class="list-unstyled mb-0">
		@foreach ($testCase->testSteps as $step)
			<li class="list-group-item-action">
				<div class="mr-1 text-truncate">
					<b>
						{{ __('Step :n', ['n' => $step->position]) }}
					</b>

					<div class="d-flex align-items-baseline text-truncate">
						<span class="d-inline-block ml-1 text-truncate" title="{{ $step->forward }}">
							{{ $step->forward }}
						</span>
						<button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-1-request">{{ __('Request Example') }}</button>
						<button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-1-response">{{ __('Response Example') }}</button>
					</div>
				</div>
			</li>
		@endforeach
	</ul>
@endsection
