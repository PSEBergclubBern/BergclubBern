<table>
    <tr><td>Titel</td><td><strong>{{ $rueckmeldung['title'] }}</strong></td></tr>
    <tr><td>Datum</td><td>{{ $rueckmeldung['date'] }}</td></tr>
    @if(!empty($rueckmeldung['dateFeedback']))
        <tr><td>Datum Rückmeldung</td><td>{{ $rueckmeldung['dateFeedback'] }}</td></tr>
    @endif
    @if(!empty($rueckmeldung['dateApproved']))
        <tr><td>Datum Freigabe</td><td>{{ $rueckmeldung['dateApproved'] }}</td></tr>
    @endif
    @if(!empty($rueckmeldung['datePay']))
        <tr><td>Datum Auszahlung</td><td>{{ $rueckmeldung['datePay'] }}</td></tr>
    @endif
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
        <td>Durchgeführt:</td>
        <td>
            <select id="executed" name="executed">
                <option value="1" @if( $rueckmeldung['executed'] )selected @endif >Ja</option>
                <option value="0" @if( !$rueckmeldung['executed'] )selected @endif>Nein</option>
            </select>
        </td>
    </tr>
    <tr><td>Leiter</td><td>{{ $rueckmeldung['leader'] }} <small><a href="{{ admin_url() . 'post.php?post=' . $_GET['id'] . '&action=edit' }}">Ändern</a></small></td></tr>
    <tr><td>Co-Leiter</td><td>{{ $rueckmeldung['coLeader'] }} <small><a href="{{ admin_url() . 'post.php?post=' . $_GET['id'] . '&action=edit' }}">Ändern</a></small></td></tr>
    <tr><td>Externe Tourenleiter<br/><small>(Nachname & Vorname, ein Eintrag pro Zeile)</small></td><td><textarea id="externLeader" name="externLeader" cols="40" rows="4">{{ $rueckmeldung['externLeader'] }}</textarea></td></tr>
    <tr><td id="label-participants">Teilnehmer BCB<br/><small>(Nachname, Vorname & Jahrgang, ein Eintrag pro Zeile)</small></td><td><textarea id="participants" name="participants" cols="40" rows="4">{{ $rueckmeldung['participants'] }}</textarea></td></tr>
    <tr><td>Externe Teilnehmer<br/><small>(Nachname & Vorname, ein Eintrag pro Zeile)</small></td><td><textarea id="externParticipants" name="externParticipants" cols="40" rows="4">{{ $rueckmeldung['externParticipants'] }}</textarea></td></tr>

    <tr><td>Abweichungen vom Programm</td><td><textarea id="programDivergence" name="programDivergence" cols="40" rows="4">{{ $rueckmeldung['programDivergence'] }}</textarea></td></tr>
    <tr><td>Kurzbericht</td><td><textarea id="shortReport" name="shortReport" cols="40" rows="4">{{ $rueckmeldung['shortReport'] }}</textarea></td></tr>
    <tr><td class="required">Pauschale</td><td><input type="text" id="flatCharge" name="flatCharge" value="{{ $rueckmeldung['flatCharge'] }}" /></td></tr>
    <tr><td>Reise</td><td><input type="text" id="journey" name="journey" value="{{ $rueckmeldung['journey'] }}"/><input type="hidden" name="isSeveralDays" value="{{ $rueckmeldung['isSeveralDays'] }}" /></td></tr>

    @if( $rueckmeldung['isSeveralDays'] )
        <tr><td>Übernachtung</td><td><input type="text" id="sleepOver" name="sleepOver" value="{{ $rueckmeldung['sleepOver'] }}" /></td></tr>
    @endif

    <tr>
        <td>Auszahlung für:</td>
        <td>
            <select name="paymentIsForLeader">
                <option value=true @if( $rueckmeldung['paymentIsForLeader'] )selected @endif >Leiter</option>
                <option value=false @if( !$rueckmeldung['paymentIsForLeader'] )selected @endif >Co-Leiter</option>
            </select>
        </td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
        <td colspan="2"><i>Hinweis: Der Erfasser ist verantwortlich dafür, dass der Kassier die benötigten Angaben erhält (Post-/Bankverbindung)</i></td>
    </tr>

</table>