@if(session()->has('new_badges'))
<div class="alert alert-info">
    <strong>Debug - Session has new_badges:</strong>
    <pre>{{ json_encode(session('new_badges'), JSON_PRETTY_PRINT) }}</pre>
</div>
@endif

@if(session()->has('points_earned'))
<div class="alert alert-success">
    <strong>Debug - Points earned:</strong> {{ session('points_earned') }}
</div>
@endif
