@if( $edit )

    @if( $tour != null )

        <form method="post">

            <table>
                <tr><td>Titel</td><td>{{ $tour['title'] }}</td></tr>
                <tr><td>Leiter</td><td>{{ $tour['leader'] }}</td></tr>
                <tr><td>Co-Leiter</td><td>{{ $tour['coLeader'] }}</td></tr>
                <tr><td>Externe Tourenleiter</td><td>{!! nl2br( $tour['externLeader'] ); !!}</td></tr>
                <tr><td>Teilnehmer BCB</td><td>{!! nl2br( $tour['participants'] ); !!}</td></tr>
                <tr><td>Externe Teilnehmer</td><td>{!! nl2br( $tour['externParticipants'] ); !!}</td></tr>
                <tr><td>Durchgeführt:</td><td>@if( $tour['executed'] ) Ja @Else Nein @Endif </td></tr>
                <tr><td>Abweichungen vom Programm</td><td>{!! nl2br( $tour['programDivergence'] ); !!}</td></tr>
                <tr><td>Kurzbericht</td><td>{!! nl2br( $tour['shortReport'] ); !!}</td></tr>
                <tr><td>Pauschale</td><td>{{ $tour['flatCharge'] }}</td></tr>
                <tr><td>Reise</td><td>{{ $tour['tour'] }}</td></tr>

                @if( $tour['isSeveralDays'] )
                    <tr><td>Übernachtung</td><td>{{ $tour['sleepOver'] }}</td></tr>
                @endif

                <tr><td>Einzahlung für:</td><td>{!! nl2br( $tour['payment'] ); !!}</td></tr>
                <tr><td>Zugunsten von:</td><td>{!! nl2br( $tour['inFavor'] ); !!}</td></tr>
                <tr><td>IBAN</td><td>{{ $tour['iban'] }}</td></tr>

            </table>

            <input type="submit" value="Bezahlt!" />

        </form>

    @else
        <p>Die ausgewählte Tour konnte nicht gefunden werden.</p>
    @endif

@else

    @if( $tours != null )

        @include('includes.tour-overview')

    @else

        <p>Momentan gibt es keine Touren, welche ausbezahlt werden müssen.</p>

    @endif

@endif


