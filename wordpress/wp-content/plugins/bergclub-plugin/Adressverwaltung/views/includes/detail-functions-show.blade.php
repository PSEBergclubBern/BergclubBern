<h1>Erfasste Funktionen</h1>
<table class="user-detail">
    @forelse($functions as $key => $function)
        <tr><td>{{  $function }}</td></tr>
    @empty
        <tr><td>Keine Funktionen erfasst.</td></tr>
    @endforelse
</table>