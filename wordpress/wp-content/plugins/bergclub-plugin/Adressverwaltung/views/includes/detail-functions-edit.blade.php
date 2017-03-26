<table id="functions">
    @foreach($functionary_roles as $role)
        <tr><td>{{  $role->getName() }}</td></tr>
    @endforeach
</table>