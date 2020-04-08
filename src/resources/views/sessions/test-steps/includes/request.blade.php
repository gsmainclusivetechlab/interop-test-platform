<div id="step-{{ $step->id }}-request" class="col-8 p-0 rounded" style="display: none">
	<div class="card mb-0 bg-light">
		<div class="card-header">
			<h2 class="card-title">
				<b>{{ __('Request') }}</b>
			</h2>
		</div>
		<div class="card-body p-0">
			@include('sessions.test-steps.includes.headers', [
				'headers' => $step->testRequestExample->headers
			])
			@include('sessions.test-steps.includes.body', [
				'body' => $step->testRequestExample->bodyToArray()
			])
		</div>
	</div>
</div>
