<form action="{{ url()->current() }}" method="GET" class="input-icon">
    <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="{{ __('Search') }}...">
    <span class="input-icon-addon">
        <i class="fe fe-search"></i>
    </span>
</form>
