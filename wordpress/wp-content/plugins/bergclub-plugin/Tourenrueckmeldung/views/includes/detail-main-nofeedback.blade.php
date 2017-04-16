@if( $edit )

    @if( $tour != null )
        <form method="post">

            <table>
                <tr><td>Titel</td><td>{{ $tour['title'] }}</td></tr>




            </table>



        </form>


    @else
        <p>Die ausgewählte Tour konnte nicht gefunden werden.</p>
    @endif

@else

    @if( $tours != null )

        <table id="tours">

            <thead>
            <tr>
                <th>Datum</th>
                <th>Titel</th>
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

    @else
        <p>Momentan gibt es keine Touren, welche zurückgemeldet werden müssen.</p>
    @endif

@endif








