<table id="functions">
    @forelse($user->functionary_roles as $role)
        <tr><td>{{  $role->getName() }}</td></tr>
    @empty
        <tr><td>Keine Funktionen erfasst.</td></tr>
    @endforelse
</table>