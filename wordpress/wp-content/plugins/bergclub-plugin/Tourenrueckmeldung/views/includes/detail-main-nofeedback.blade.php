@if( $edit )

    @if( $tour != null )
        <form method="post">

            <table>
                <tr><td>Titel</td><td>{{ $tour['title'] }}</td></tr>
                <tr><td>Leiter</td><td>{{ $tour['leader'] }}</td></tr>
                <tr><td>Co-Leiter</td><td><input type="text" id="coLeader" name="coLeader" /></td></tr>
                <tr><td>Externe Tourenleiter</td><td><textarea id="externLeader" name="externLeader" cols="40" rows="4"></textarea></td></tr>
                <tr><td>Teilnehmer BCB</td><td><textarea id="participants" name="participants" cols="40" rows="4"></textarea></td></tr>
                <tr><td>Externe Teilnehmer</td><td><textarea id="externParticipants" name="externParticipants" cols="40" rows="4"></textarea></td></tr>

                <tr>
                    <td>Durchgeführt:</td>
                    <td>
                        <select name="executed">
                            <option value=true>Ja</option>
                            <option value=false>Nein</option>
                        </select>
                    </td>
                </tr>

                <tr><td>Abweichungen vom Programm</td><td><textarea id="programDivergence" name="programDivergence" cols="40" rows="4"></textarea></td></tr>
                <tr><td>Kurzbericht</td><td><textarea id="shortReport" name="shortReport" cols="40" rows="4"></textarea></td></tr>
                <tr><td>Pauschale</td><td><input type="text" id="flatCharge" name="flatCharge" /></td></tr>
                <tr><td>Reise</td><td><input type="text" id="tour" name="tour" /></td></tr>

                @if( $tour['isSeveralDays'] )
                    <tr><td>Übernachtung</td><td><input type="text" id="sleepOver" name="sleepOver" /></td></tr>
                @endif

                <tr><td>Einzahlung für:</td><td><textarea id="payment" name="payment" cols="40" rows="4"></textarea></td></tr>
                <tr><td>Zugunsten von:</td><td><textarea id="inFavor" name="inFavor" cols="40" rows="4"></textarea></td></tr>
                <tr><td>IBAN</td><td><input type="text" name="iban" id="iban" /></td></tr>

            </table>

            <input type="submit" value="Speichern!" />

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








