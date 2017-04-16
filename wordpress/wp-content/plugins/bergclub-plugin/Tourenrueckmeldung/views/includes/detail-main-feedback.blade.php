@if( $tours != null )

    <table id="tours">

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
                    <td><a href="#"><span class="dashicons dashicons-edit"></span></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <p>Momentan gibt es keine Touren, welche überprüft werden müssen.</p>

@endif


