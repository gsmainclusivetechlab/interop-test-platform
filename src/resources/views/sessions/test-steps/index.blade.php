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
				@foreach ($testSteps as $testStep)
					<tr>
						<td>
							{{ __('Step :n', ['n' => $testStep->position]) }}
						</td>
						<td>
							{{ $testStep->source->name }}
						</td>
						<td>
							{{ $testStep->target->name }}
						</td>
						<td>
							{{ $testStep->forward }}
						</td>
						<td>
							@if ($testStep->testRequestExample)
								<button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-{{ $testStep->id }}-request">{{ __('Request') }}</button>
								@include('sessions.test-steps.includes.request')
							@endif
							@if ($testStep->testResponseExample)
								<button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-{{ $testStep->id }}-response">{{ __('Response') }}</button>
								@include('sessions.test-steps.includes.response')
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@if ($testSteps->count())
		<div class="card-footer">
			<div class="row align-items-center">
				<div class="col-md-6">
					{{ __('Showing :from to :to of :total entries', [
						'from' => (($testSteps->currentPage() - 1) * $testSteps->perPage()) + 1,
						'to' => (($testSteps->currentPage() - 1) * $testSteps->perPage()) + $testSteps->count(),
						'total' => $testSteps->total(),
					]) }}
				</div>
				<div class="col-md-6">
					<div class="justify-content-end d-flex">
						{{ $testSteps->appends(request()->all())->links() }}
					</div>
				</div>
			</div>
		</div>
	@endif
@endsection
