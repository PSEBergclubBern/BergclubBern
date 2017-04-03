<table class="edit-list">
    @foreach($functionary_roles as $key => $role)
        <tr><td><input type="checkbox" name="functionary_roles[]" value='{{ $role->getKey() }}' @if(array_key_exists($role->getKey(), $user_functionary_roles)) checked @endif> {{  $role->getName() }}</td></tr>
    @endforeach
</table>