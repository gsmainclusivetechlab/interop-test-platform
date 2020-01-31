@if(session('success'))
    <b-alert show variant="success" dismissible>{{ session('success') }}</b-alert>
@elseif(session('danger'))
    <b-alert show variant="danger" dismissible>{{ session('danger') }}</b-alert>
@elseif(session('warning'))
    <b-alert show variant="warning" dismissible>{{ session('warning') }}</b-alert>
@elseif(session('info'))
    <b-alert show variant="info" dismissible>{{ session('info') }}</b-alert>
@endif
