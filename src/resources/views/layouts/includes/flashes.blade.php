@if(session('success'))
    <notification :options='@json(['text' => session('success'), 'type' => 'success'])'></notification>
@elseif(session('danger'))
    <notification :options='@json(['text' => session('danger'), 'type' => 'danger'])'></notification>
@elseif(session('warning'))
    <notification :options='@json(['text' => session('warning'), 'type' => 'warning'])'></notification>
@elseif(session('info'))
    <notification :options='@json(['text' => session('info'), 'type' => 'info'])'></notification>
@endif
