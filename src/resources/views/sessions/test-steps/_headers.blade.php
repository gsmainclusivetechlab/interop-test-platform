<div class="d-flex">
	<div class="w-25 px-4 py-2 border">
		<strong>{{ __('Headers') }}</strong>
	</div>
	<div class="w-75 px-4 py-2 border">
		<div class="mb-0 p-0 bg-transparent">
			<pre class="mb-0 p-0">
				<code v-pre>@json($headers, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)</code>
			</pre>
		</div>
	</div>
</div>
