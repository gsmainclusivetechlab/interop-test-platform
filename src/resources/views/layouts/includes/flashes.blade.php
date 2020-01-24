@if(session('success'))
    @component('components.alert', ['type' => 'success'])
        {{ session('success') }}
    @endcomponent
@elseif(session('danger'))
    @component('components.alert', ['type' => 'danger'])
        {{ session('danger') }}
    @endcomponent
@elseif(session('warning'))
    @component('components.alert', ['type' => 'warning'])
        {{ session('warning') }}
    @endcomponent
@elseif(session('info'))
    @component('components.alert', ['type' => 'info'])
        {{ session('info') }}
    @endcomponent
@endif
