<div id="step-{{ $step->id }}-response" class="col-8 p-0 rounded" style="display: none">
	<div class="card mb-0 bg-light">
		<div class="card-header">
			<h2 class="card-title">
				<b>{{ __('Response') }}</b>
			</h2>
		</div>
		<div class="card-body p-0">
			@isset($step->testDataExample->response['code'])
				<div class="d-flex">
					<div class="w-25 px-4 py-2 border">
						<strong>{{ __('HTTP code') }}</strong>
					</div>
					<div class="w-75 px-4 py-2 border">
						<div class="mb-0 p-0 bg-transparent">
							{{ $step->testDataExample->response['code'] }}
						</div>
					</div>
				</div>
			@endisset
			@isset($step->testDataExample->response['headers'])
				@include('sessions.test-data-examples.examples._headers', [
    				'headers' => $step->testDataExample->response['headers']
				])
			@endif
			@isset($step->testDataExample->response['body'])
				@include('sessions.test-data-examples.examples._body', [
    				'body' => $step->testDataExample->response['body']
				])
			@endif
		</div>
	</div>
</div>
