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
				@foreach ($steps as $step)
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
							@if ($step->testRequestExample)
								<button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-{{ $step->id }}-request">{{ __('Request') }}</button>
								@include('sessions.test-steps.includes.request')
							@endif
							@if ($step->testResponseExample)
								<button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-{{ $step->id }}-response">{{ __('Response') }}</button>
								@include('sessions.test-steps.includes.response')
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@if ($steps->count())
		<div class="card-footer">
			<div class="row align-items-center">
				<div class="col-md-6">
					{{ __('Showing :from to :to of :total entries', [
						'from' => (($steps->currentPage() - 1) * $steps->perPage()) + 1,
						'to' => (($steps->currentPage() - 1) * $steps->perPage()) + $steps->count(),
						'total' => $steps->total(),
					]) }}
				</div>
				<div class="col-md-6">
					<div class="justify-content-end d-flex">
						{{ $steps->appends(request()->all())->links() }}
					</div>
				</div>
			</div>
		</div>
	@endif
@endsection
