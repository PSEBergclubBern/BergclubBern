<table class="user-detail">

    <tr>
        <td><label class="required" for="address_type">Adresstyp</label></td>
        <td>
            <select name="address_type" id="address_type">
                <option value="">-- Bitte wählen --</option>
                @foreach($address_roles as $role)
                    <option value="{{ $role->getKey() }}" @if($role->getKey() == $user->address_role_key) selected @endif>{{ $role->getName() }}</option>
                @endforeach
            </select>
        </td>

    </tr>

    <tr>
        <td class="td-leaving_reason"><label id="label-leaving_reason" for="leaving_reason">Austrittsgrund</label></td>
        <td class="td-leaving_reason">
            <select id="leaving_reason" name="leaving_reason">
                <option value="">-- Bitte wählen --</option>
                <option value="1" @if($user->leaving_reason=='Ausgetreten') selected="selected" @endif>Ausgetreten</option>
                <option value="2" @if($user->leaving_reason=='Verstorben') selected="selected" @endif>Verstorben</option>
            </select>
        </td>
    </tr>

    <tr>
        <td><label for="program_shipment">Versand Programm</label></td>
        <td>
            <select id="program_shipment" name="program_shipment">
                <option value="1" @if($user->program_shipment=='Ja') selected="selected" @endif>Ja</option>
                <option value="0" @if($user->program_shipment=='Nein') selected="selected" @endif>Nein</option>
            </select>
        </td>
    </tr>

    <tr>
        <td></td><td>&nbsp;</td>
    </tr>

    <tr>
        <td class="td-company"><label id="label-company" for="company">Firma</label></td>
        <td class="td-company"><input type="text" id="company" name="company" value="{{  $user->company }}" /></td>
    </tr>

    <tr>
        <td><label id="label-gender" for="gender">Anrede</label></td>
        <td>
            <select id="gender" name="gender">
                <option value="">-- Bitte wählen --</option>
                <option value="M" @if($user->gender=='Herr') selected="selected" @endif>Herr</option>
                <option value="F" @if($user->gender=='Frau') selected="selected" @endif>Frau</option>
            </select>
        </td>
    </tr>
    <tr>
        <td><label id="label-first_name" for="first_name">Vorname</label></td>
        <td><input type="text" id="first_name" name="first_name" value="{{  $user->first_name }}" /></td>
    </tr>

    <tr>
        <td><label id="label-last_name" for="last_name">Nachname</label></td>
        <td><input type="text" id="last_name" name="last_name" value="{{  $user->last_name }}" /></td>
    </tr>

    <tr>
        <td><label for="address_addition">Zusatz</label></td>
        <td><input type="text" id="address_addition" name="address_addition" value="{{  $user->address_addition }}" /></td>
    </tr>

    <tr>
        <td><label id="label-street" for="street">Strasse</label></td>
        <td><input type="text" id="street" name="street" value="{{  $user->street }}" /></td>
    </tr>

    <tr>
        <td><label id="label-zip" for="zip">Postleitzahl</label></td>
        <td><input type="text" id="zip" name="zip" value="{{  $user->zip }}" /></td>
    </tr>

    <tr>
        <td><label id="label-location" for="location">Ort</label></td>
        <td><input type="text" id="location" name="location" value="{{  $user->location }}" /></td>
    </tr>

    <tr>
        <td></td><td>&nbsp;</td>
    </tr>

    <tr>
        <td class="td-phone_private"><label for="phone_private">Telefon P</label></td>
        <td class="td-phone_private"><input type="text" id="phone_private" name="phone_private" value="{{  $user->phone_private }}" /></td>
    </tr>

    <tr>
        <td><label for="phone_work">Telefon G</label></td>
        <td><input type="text" id="phone_work" name="phone_work" value="{{  $user->phone_work }}" /></td>
    </tr>

    <tr>
        <td><label for="phone_mobile">Telefon M</label></td>
        <td><input type="text" id="phone_mobile" name="phone_mobile" value="{{  $user->phone_mobile }}" /></td>
    </tr>

    <tr>
        <td><label for="email">Email</label></td>
        <td><input type="text" id="email" name="email" value="{{  $user->email }}" /></td>
    </tr>

    <tr>
        <td></td><td>&nbsp;</td>
    </tr>

    <tr>
        <td class="td-birthdate"><label id="label-birthdate" for="birthdate">Geburtsdatum</label></td>
        <td class="td-birthdate"><input type="text" id="birthdate" name="birthdate" value="{{  $user->birthdate }}" /></td>
    </tr>

    <tr>
        <td><label for="comments">Bemerkungen</label></td>
        <td><textarea name="comments" id="comments">{{  $user->comments }}</textarea></td>
    </tr>

</table>



