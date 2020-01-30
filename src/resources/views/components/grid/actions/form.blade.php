<b-dropdown-form action="{{ $route }}" method="POST">
    @csrf
    @method($method)
    <confirm-button class="dropdown-item" type="submit" title="{{ $confirmTitle }}" text="{{ $confirmText }}">{{ $label }}</confirm-button>
</b-dropdown-form>
