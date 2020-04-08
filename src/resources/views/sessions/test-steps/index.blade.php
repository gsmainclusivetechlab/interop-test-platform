@extends('layouts.sessions.test-case', $session)

@section('title', $session->name)

@section('content')
	<div class="table-responsive mb-0">
		<table class="table table-striped table-hover card-table">
			<thead class="thead-light">
			<tr>
				<th class="text-nowrap w-auto">{{ __('Step') }}</th>
				<th class="text-nowrap w-auto">{{ __('Source') }}</th>
				<th class="text-nowrap w-auto">{{ __('Target') }}</th>
				<th class="text-nowrap w-auto">{{ __('URL') }}</th>
				<th class="text-nowrap w-auto">{{ __('Data examples') }}</th>
			</tr>
			</thead>
			<tbody>
				@foreach ($testCase->testSteps as $step)
					<tr>
						<td>
							{{ __('Step :n', ['n' => $step->position]) }}
						</td>
						<td>
							{{ $step->source->name }}
						</td>
						<td>
							{{ $step->target->name }}
						</td>
						<td>
							{{ $step->forward }}
						</td>
						<td>
							@if ($step->testDataExample)
								<button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-{{ $step->id }}-request">{{ __('Request') }}</button>
								<button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-{{ $step->id }}-response">{{ __('Response') }}</button>
								@if ($step->testDataExample->request)
									@include('sessions.test-steps._request')
								@endif
								@if ($step->testDataExample->response)
									@include('sessions.test-steps._response')
								@endif
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection
