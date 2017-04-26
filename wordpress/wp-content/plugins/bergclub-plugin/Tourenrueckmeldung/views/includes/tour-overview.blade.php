<table id="tours" class="stripe responsive no-link-underline">

    <thead>
    <tr>
        <th align="left">Datum</th>
        <th align="left">Titel</th>
        <th align="left">Leiter</th>
        <th align="left">Co-Leiter</th>
        @if($showDateFeedback)
            <th align="left">Erstellt</th>
        @endif
        @if($showDateApproved)
            <th align="left">Freigegeben</th>
        @endif
        @if($showDatePay)
            <th align="left">Ausbezahlt</th>
        @endif
        <th align="left"></th>
    </tr>
    </thead>

    <tbody>

        @foreach( $tours as $id => $tour )
            <tr>
                <td>{{ $tour['date'] }}</td>
                <td>{{ $tour['title'] }}</td>
                <td>{{ $tour['leader'] }}</td>
                <td>{{ $tour['coLeader'] }}</td>
                @if($showDateFeedback)
                    <td align="left">{{ $tour['dateFeedback'] }}</td>
                @endif
                @if($showDateApproved)
                    <td align="left">{{ $tour['dateApproved'] }}</td>
                @endif
                @if($showDatePay)
                    <td align="left">{{ $tour['datePay'] }}</td>
                @endif
                <td><a href="?page={{ $_GET['page'] }}&tab={{ $tab }}&id={{ $id }}"><span class="dashicons dashicons-edit"></span></a></td>
            </tr>
        @endforeach
    </tbody>

</table>