<form action="{{ $route }}" method="POST">
    @csrf
    @method($method)
    <button class="dropdown-item" type="submit" data-confirm-form="" data-confirm-title="{{ $confirm }}">{{ $label }}</button>
</form>
