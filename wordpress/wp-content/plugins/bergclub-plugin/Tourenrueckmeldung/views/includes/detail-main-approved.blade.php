@if( $edit )

    @if( $rueckmeldung != null )

        <form method="post">

            <table id="tours">
                <tr><td>Tour</td><td><strong>{{ $rueckmeldung['title'] }}</strong></td></tr>
                <tr><td>Datum</td><td>{{ $rueckmeldung['date'] }}</td></tr>
                <tr><td>Datum Rückmeldung</td><td>{{ $rueckmeldung['dateFeedback'] }}</td></tr>
                <tr><td>Datum Freigabe</td><td>{{ $rueckmeldung['dateApproved'] }}</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td>Durchgeführt:</td><td>@if( $rueckmeldung['executed'] ) Ja @else Nein @endif </td></tr>
                <tr><td>Leiter</td><td>{{ $rueckmeldung['leader'] }}</td></tr>
                <tr><td>Co-Leiter</td><td>{{ $rueckmeldung['coLeader'] }}</td></tr>
                <tr><td>Externe Tourenleiter</td><td>{!! nl2br( $rueckmeldung['externLeader'] ); !!}</td></tr>
                <tr><td>Teilnehmer BCB</td><td>{!! nl2br( $rueckmeldung['participants'] ); !!}</td></tr>
                <tr><td>Externe Teilnehmer</td><td>{!! nl2br( $rueckmeldung['externParticipants'] ); !!}</td></tr>
                <tr><td>Anzahl Teilnehmer:</td><td>{{ $rueckmeldung['numberOfParticipants'] }}</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td>Abweichungen vom Programm</td><td>{!! nl2br( $rueckmeldung['programDivergence'] ); !!}</td></tr>
                <tr><td>Kurzbericht</td><td>{!! nl2br( $rueckmeldung['shortReport'] ); !!}</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td>Pauschale</td><td>{{ $rueckmeldung['flatCharge'] }}</td></tr>
                <tr><td>Reise</td><td>{{ $rueckmeldung['journey'] }}</td></tr>

                @if( $rueckmeldung['isSeveralDays'] )
                    <tr><td>Übernachtung</td><td>{{ $rueckmeldung['sleepOver'] }}</td></tr>
                @endif

                <tr><td>Auszahlung für:</td><td>@if( $rueckmeldung['paymentIsForLeader'] ) Leiter @else Co-Leiter @endif </td></tr>

            </table>

            <p><input type="submit" class="button button-primary" value="Ausbezahlt" /></p>

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


