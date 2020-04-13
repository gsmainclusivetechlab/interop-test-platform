<div id="step-{{ $testStep->id }}-response" class="col-8 p-0 rounded" style="display: none">
	<div class="card mb-0 bg-light">
		<div class="card-header">
			<h2 class="card-title">
				<b>{{ __('Response') }}</b>
			</h2>
		</div>
		<div class="card-body p-0">
			@if($testStep->response_example->status())
				<div class="d-flex">
					<div class="w-25 px-4 py-2 border">
						<strong>{{ __('HTTP status code') }}</strong>
					</div>
					<div class="w-75 px-4 py-2 border">
						<div class="mb-0 p-0 bg-transparent">
							{{ $testStep->response_example->status() }}
						</div>
					</div>
				</div>
			@endif
			@include('sessions.test-steps.includes.headers', [
				'headers' => $testStep->response_example->headerNames()
			])
			@include('sessions.test-steps.includes.body', [
				'body' => $testStep->response_example->json()
			])
		</div>
	</div>
</div>
