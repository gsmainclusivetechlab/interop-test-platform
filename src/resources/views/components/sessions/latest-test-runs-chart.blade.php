<chart
	ajax-url="{{ route('sessions.chart', $session) }}"
	type="bar"
	height=360
	:options='@json($chartOptions)'
></chart>
