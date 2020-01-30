<b-dropdown-form action="{{ $route }}" method="POST">
    @csrf
    @method($method)
    <confirm class="dropdown-item" type="submit" title="{{ $confirmTitle }}" text="{{ $confirmText }}">{{ $label }}</confirm>
</b-dropdown-form>
