<table>
    <tr>
        <td>Titel</td>
        <td><strong>{{ $rueckmeldung['title'] }}</strong></td>
    </tr>
    <tr>
        <td>Datum</td>
        <td>{{ $rueckmeldung['date'] }}</td>
    </tr>
    @if(!empty($rueckmeldung['dateFeedback']))
        <tr>
            <td>Datum Rückmeldung</td>
            <td>{{ $rueckmeldung['dateFeedback'] }}</td>
        </tr>
    @endif
    @if(!empty($rueckmeldung['dateApproved']))
        <tr>
            <td>Datum Freigabe</td>
            <td>{{ $rueckmeldung['dateApproved'] }}</td>
        </tr>
    @endif
    @if(!empty($rueckmeldung['datePay']))
        <tr>
            <td>Datum Auszahlung</td>
            <td>{{ $rueckmeldung['datePay'] }}</td>
        </tr>
    @endif
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td id="td-executed" class="required">Durchgeführt:</td>
        <td>
            <select id="executed" name="executed">
                <option value="1" @if( $rueckmeldung['executed'] )selected @endif >Ja</option>
                <option value="0" @if( !$rueckmeldung['executed'] )selected @endif>Nein</option>
            </select>
        </td>
    </tr>
    <tr>
        <td id="td-leader">Leiter</td>
        <td>{{ $rueckmeldung['leader'] }}
            <small><a href="{{ admin_url() . 'post.php?post=' . $_GET['id'] . '&action=edit' }}">Ändern</a></small>
        </td>
    </tr>
    <tr>
        <td id="td-coLeader">Co-Leiter</td>
        <td>@if(empty($rueckmeldung['coLeader'])) Kein Co-Leiter @else {{ $rueckmeldung['coLeader'] }} @endif
            <small><a href="{{ admin_url() . 'post.php?post=' . $_GET['id'] . '&action=edit' }}">Ändern</a></small>
        </td>
    </tr>
    <tr>
        <td id="td-externLeader">Externe Tourenleiter<br/>
            <small>(Nachname & Vorname, ein Eintrag pro Zeile)</small>
        </td>
        <td><textarea id="externLeader" name="externLeader" cols="40"
                      rows="4">{{ $rueckmeldung['externLeader'] }}</textarea></td>
    </tr>
    <tr id="tr-note">
        <td colspan="2"><i>Hinweis: Es muss mindestens ein Teilnehmer BCB oder ein externer Teilnehmer erfasst
                werden.</i></td>
    </tr>
    <tr>
        <td id="td-participants">Teilnehmer BCB<br/>
            <small>(Nachname, Vorname & Jahrgang, ein Eintrag pro Zeile)</small>
        </td>
        <td><textarea id="participants" name="participants" cols="40"
                      rows="4">{{ $rueckmeldung['participants'] }}</textarea></td>
    </tr>
    <tr>
        <td id="td-externParticipants">Externe Teilnehmer<br/>
            <small>(Nachname & Vorname, ein Eintrag pro Zeile)</small>
        </td>
        <td><textarea id="externParticipants" name="externParticipants" cols="40"
                      rows="4">{{ $rueckmeldung['externParticipants'] }}</textarea></td>
    </tr>
    <tr>
        <td id="td-programDivergence">Abweichungen vom Programm</td>
        <td><textarea id="programDivergence" name="programDivergence" cols="40"
                      rows="4">{{ $rueckmeldung['programDivergence'] }}</textarea></td>
    </tr>
    <tr>
        <td id="td-shortReport">Kurzbericht</td>
        <td><textarea id="shortReport" name="shortReport" cols="40"
                      rows="4">{{ $rueckmeldung['shortReport'] }}</textarea></td>
    </tr>
    <tr>
        <td id="td-flatCharge" class="required">Pauschale</td>
        <td><input type="text" id="flatCharge" name="flatCharge" value="{{ $rueckmeldung['flatCharge'] }}"/></td>
    </tr>
    <tr>
        <td id="td-journey">Reise</td>
        <td><input type="text" id="journey" name="journey" value="{{ $rueckmeldung['journey'] }}"/><input type="hidden"
                                                                                                          name="isSeveralDays"
                                                                                                          value="{{ $rueckmeldung['isSeveralDays'] }}"/>
        </td>
    </tr>

    @if( $rueckmeldung['isSeveralDays'] )
        <tr>
            <td id="td-sleepOver">Übernachtung</td>
            <td><input type="text" id="sleepOver" name="sleepOver" value="{{ $rueckmeldung['sleepOver'] }}"/></td>
        </tr>
    @endif
    <tr>
        <td colspan="2"><i>Hinweis: Der Erfasser ist verantwortlich dafür, dass der Kassier die benötigten Angaben
                erhält (Post-/Bankverbindung)</i></td>
    </tr>
    <tr>
        <td id="td-paymentIsForLeader" class="required">Auszahlung für</td>
        <td>
            <select name="paymentIsForLeader">
                <option value="1" @if( $rueckmeldung['paymentIsForLeader'] )selected @endif >Leiter</option>
                @if(!empty($rueckmeldung['coLeader']))
                    <option value="0" @if( !$rueckmeldung['paymentIsForLeader'] )selected @endif >Co-Leiter</option>
                @endif
            </select>
        </td>
    </tr>

</table>

<script type="text/javascript">
    jQuery('#executed').change(function () {
        updateRequired();
    });

    jQuery(document).ready(function () {
        updateRequired();
    });

    function updateRequired() {
        var executed = jQuery('#executed').val();
        if (executed == 1) {
            jQuery('#td-participants').addClass('required');
            jQuery('#td-externParticipants').addClass('required');
            jQuery('#td-journey').addClass('required');
            jQuery('#td-sleepOver').addClass('required');
            jQuery('#td-shortReport').addClass('required');
            jQuery('#tr-note').show();
        } else {
            jQuery('#td-participants').removeClass('required');
            jQuery('#td-externParticipants').removeClass('required');
            jQuery('#td-journey').removeClass('required');
            jQuery('#td-sleepOver').removeClass('required');
            jQuery('#td-shortReport').removeClass('required');
            jQuery('#tr-note').hide();
        }
    }
</script>