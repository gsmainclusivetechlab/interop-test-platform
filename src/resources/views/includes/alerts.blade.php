@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"></button>
        {{ session('success') }}
    </div>
@elseif(session('danger'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"></button>
        {{ session('danger') }}
    </div>
@elseif(session('primary'))
    <div class="alert alert-primary alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"></button>
        {{ session('primary') }}
    </div>
@endif
