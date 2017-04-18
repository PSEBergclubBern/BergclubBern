<table id="tours" class="stripe responsive no-link-underline">

    <thead>
    <tr>
        <th>Datum</th>
        <th>Titel</th><th>
        <th></th>
    </tr>
    </thead>

    <tbody>

        @foreach( $tours as $tour )
            <tr>
                <td>{{ $tour['dateFrom'] }}</td>
                <td>{{ $tour['title'] }}</td>
                <td><a href="?page={{ $_GET['page'] }}&tab={{ $tab }}&id={{ $tour['id'] }}"><span class="dashicons dashicons-edit"></span></a></td>
            </tr>
        @endforeach
    </tbody>

</table>