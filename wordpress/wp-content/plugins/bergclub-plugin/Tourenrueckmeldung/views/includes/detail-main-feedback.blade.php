@if( $edit )

    @if( $tour != null )

        <form method="post">

            <table>
                <tr><td>Titel</td><td>{{ $tour['title'] }}</td></tr>
                <tr><td>Leiter</td><td>{{ $tour['leader'] }}</td></tr>
                <tr><td>Co-Leiter</td><td><input type="text" id="coLeader" name="coLeader" value="{{ $tour['coLeader'] }}" /></td></tr>
                <tr><td>Externe Tourenleiter</td><td><textarea id="externLeader" name="externLeader" cols="40" rows="4">{{ $tour['externLeader'] }}</textarea></td></tr>
                <tr><td>Teilnehmer BCB</td><td><textarea id="participants" name="participants" cols="40" rows="4">{{ $tour['participants'] }}</textarea></td></tr>
                <tr><td>Externe Teilnehmer</td><td><textarea id="externParticipants" name="externParticipants" cols="40" rows="4">{{ $tour['externParticipants'] }}</textarea></td></tr>

                <tr>
                    <td>Durchgeführt:</td>
                    <td>
                        <select name="executed">
                            <option value=true @if( $tour['executed'] )selected @endif >Ja</option>
                            <option value=false @if( !$tour['executed'] )selected @endif>Nein</option>
                        </select>
                    </td>
                </tr>

                <tr><td>Abweichungen vom Programm</td><td><textarea id="programDivergence" name="programDivergence" cols="40" rows="4">{{ $tour['programDivergence'] }}</textarea></td></tr>
                <tr><td>Kurzbericht</td><td><textarea id="shortReport" name="shortReport" cols="40" rows="4">{{ $tour['shortReport'] }}</textarea></td></tr>
                <tr><td>Pauschale</td><td><input type="text" id="flatCharge" name="flatCharge" value="{{ $tour['flatCharge'] }}" /></td></tr>
                <tr><td>Reise</td><td><input type="text" id="tour" name="tour" value="{{ $tour['tour'] }}"/></td></tr>

                @if( $tour['isSeveralDays'] )
                    <tr><td>Übernachtung</td><td><input type="text" id="sleepOver" name="sleepOver" value="{{ $tour['sleepOver'] }}" /></td></tr>
                @endif

                <tr><td>Einzahlung für:</td><td><textarea id="payment" name="payment" cols="40" rows="4">{{ $tour['payment'] }}</textarea></td></tr>
                <tr><td>Zugunsten von:</td><td><textarea id="inFavor" name="inFavor" cols="40" rows="4">{{ $tour['inFavor'] }}</textarea></td></tr>
                <tr><td>IBAN</td><td><input type="text" name="iban" id="iban" value="{{ $tour['iban'] }}"/></td></tr>

            </table>

            <input type="submit" value="Freigeben!" />

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

    @else
        <p>Momentan gibt es keine Touren, welche überprüft werden müssen.</p>

    @endif

@endif
