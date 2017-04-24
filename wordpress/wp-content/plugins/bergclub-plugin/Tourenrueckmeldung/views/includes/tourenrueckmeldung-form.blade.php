<table>

    <tr><td>Titel</td><td>{{ $tour['title'] }}</td></tr>
    <tr><td>Leiter</td><td>{{ $tour['leader'] }}</td></tr>
    <tr><td>Co-Leiter</td><td><input type="text" id="coLeader" name="coLeader" value="{{ $tour['coLeader'] }}" /></td></tr>
    <tr><td>Externe Tourenleiter</td><td><textarea id="externLeader" name="externLeader" cols="40" rows="4">{{ $tour['externLeader'] }}</textarea></td></tr>
    <tr><td id="label-participants">Teilnehmer BCB</td><td><textarea id="participants" name="participants" cols="40" rows="4">{{ $tour['participants'] }}</textarea></td></tr>
    <tr><td>Externe Teilnehmer</td><td><textarea id="externParticipants" name="externParticipants" cols="40" rows="4">{{ $tour['externParticipants'] }}</textarea></td></tr>

    <tr>
        <td>Durchgeführt:</td>
        <td>
            <select id="executed" name="executed">
                <option value=true @if( $tour['executed'] )selected @endif >Ja</option>
                <option value=false @if( !$tour['executed'] )selected @endif>Nein</option>
            </select>
        </td>
    </tr>

    <tr><td>Abweichungen vom Programm</td><td><textarea id="programDivergence" name="programDivergence" cols="40" rows="4">{{ $tour['programDivergence'] }}</textarea></td></tr>
    <tr><td>Kurzbericht</td><td><textarea id="shortReport" name="shortReport" cols="40" rows="4">{{ $tour['shortReport'] }}</textarea></td></tr>
    <tr><td class="required">Pauschale</td><td><input type="text" id="flatCharge" name="flatCharge" value="{{ $tour['flatCharge'] }}" /></td></tr>
    <tr><td>Reise</td><td><input type="text" id="tour" name="tour" value="{{ $tour['tour'] }}"/></td></tr>

    @if( $tour['isSeveralDays'] )
        <tr><td>Übernachtung</td><td><input type="text" id="sleepOver" name="sleepOver" value="{{ $tour['sleepOver'] }}" /></td></tr>
    @endif

    <tr>
        <td>Auszahlung für:</td>
        <td>
            <select name="paymentIsForLeader">
                <option value=true @if( $tour['paymentIsForLeader'] )selected @endif >Leiter</option>
                <option value=false @if( !$tour['paymentIsForLeader'] )selected @endif >Co-Leiter</option>
            </select>
        </td>
    </tr>

</table>