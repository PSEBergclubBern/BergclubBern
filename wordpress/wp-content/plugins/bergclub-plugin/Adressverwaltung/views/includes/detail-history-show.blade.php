<table class="edit-list">
    <thead>
    <tr>
        <th>Rolle</th>
        <th>von</th>
        <th>bis</th>
    </tr>
    </thead>
    <tbody>
    @foreach($user->history as $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td>{{ $item['date_from'] }}</td>
            <td>{{ $item['date_to'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>