@if(session('success'))
    <notification :options="{text: '{{ session('success') }}', type: 'success'}"></notification>
@elseif(session('danger'))
    <notification :options="{text: '{{ session('danger') }}', type: 'danger'}"></notification>
@elseif(session('warning'))
    <notification :options="{text: '{{ session('warning') }}', type: 'warning'}"></notification>
@elseif(session('info'))
    <notification :options="{text: '{{ session('info') }}', type: 'info'}"></notification>
@endif
