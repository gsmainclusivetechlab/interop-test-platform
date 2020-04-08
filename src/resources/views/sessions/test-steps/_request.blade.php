<div id="step-{{ $step->id }}-request" class="col-8 p-0 rounded" style="display: none">
	<div class="card mb-0 bg-light">
		<div class="card-header">
			<h2 class="card-title">
				<b>{{ __('Request') }}</b>
			</h2>
		</div>
		<div class="card-body p-0">
			@isset($step->testDataExample->request['headers'])
				@include('sessions.test-steps._headers', [
    				'headers' => $step->testDataExample->request['headers']
				])
			@endisset
			@isset($step->testDataExample->request['body'])
				@include('sessions.test-steps._body', [
    				'body' => $step->testDataExample->request['body']
				])
			@endisset
		</div>
	</div>
</div>
