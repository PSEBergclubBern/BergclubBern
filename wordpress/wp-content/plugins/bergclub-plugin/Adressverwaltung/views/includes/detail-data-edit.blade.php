<table class="user-detail">

    <tr>
        <td><label for="address_type">Adresstyp</label></td>
        <td>
            <select name="address_type" id="address_type">
                <option value="-1"><--Bitte wählen--></option>
                @foreach($address_roles as $role)
                    <option value="{{ $role->getKey() }}" @if($role->getKey() == $user->address_role_key) selected @endif>{{ $role->getName() }}</option>
                @endforeach
            </select>
        </td>

    </tr>

    <tr>
        <td id="label_leaving_reason"><label for="leaving_reason">Austrittsgrund</label></td>
        <td id="input_leaving_reason">
            <select name="leaving_reason">
                <option value="-1"><--Bitte wählen--></option>
                <option value="1" @if($user->leaving_reason=='Ausgetreten') selected="selected" @endif>Ausgetreten</option>
                <option value="2" @if($user->leaving_reason=='Verstorben') selected="selected" @endif>Verstorben</option>
            </select>
        </td>
    </tr>

    <tr>
        <td><label for="program_shipment">Versand Programm</label></td>
        <td>
            <select name="program_shipment">
                <option value="-1"><--Bitte wählen--></option>
                <option value="0" @if($user->program_shipment=='Nein') selected="selected" @endif>Nein</option>
                <option value="1" @if($user->program_shipment=='Ja') selected="selected" @endif>Ja</option>
            </select>
        </td>
    </tr>

    <tr>
        <td></td><td>&nbsp;</td>
    </tr>

    <tr>
        <td id="label_company"><label for="company">Firma</label></td>
        <td id="input_company"><input type="text" name="company" value="{{  $user->company }}" /></td>
    </tr>

    <tr>
        <td><label for="gender">Anrede</label></td>
        <td>
            <select name="gender">
                <option value="-1"><--Bitte wählen--></option>
                <option value="M" @if($user->gender=='Herr') selected="selected" @endif>Herr</option>
                <option value="F" @if($user->gender=='Frau') selected="selected" @endif>Frau</option>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="first_name">Vorname</label></td>
        <td><input type="text" name="first_name" value="{{  $user->first_name }}" /></td>
    </tr>

    <tr>
        <td><label for="last_name">Nachname</label></td>
        <td><input type="text" name="last_name" value="{{  $user->last_name }}" /></td>
    </tr>

    <tr>
        <td><label for="address_addition">Zusatz</label></td>
        <td><input type="text" name="address_addition" value="{{  $user->address_addition }}" /></td>
    </tr>

    <tr>
        <td><label for="street">Strasse</label></td>
        <td><input type="text" name="street" value="{{  $user->street }}" /></td>
    </tr>

    <tr>
        <td><label for="zip">Postleitzahl</label></td>
        <td><input type="text" name="zip" value="{{  $user->zip }}" /></td>
    </tr>

    <tr>
        <td><label for="location">Ort</label></td>
        <td><input type="text" name="location" value="{{  $user->location }}" /></td>
    </tr>

    <tr>
        <td></td><td>&nbsp;</td>
    </tr>

    <tr>
        <td id="label_phone_private"><label for="phone_private">Telefon P</label></td>
        <td id="input_phone_private"><input type="text" name="phone_private" value="{{  $user->phone_private }}" /></td>
    </tr>

    <tr>
        <td><label for="phone_work">Telefon G</label></td>
        <td><input type="text" name="phone_work" value="{{  $user->phone_work }}" /></td>
    </tr>

    <tr>
        <td><label for="phone_mobile">Telefon M</label></td>
        <td><input type="text" name="phone_mobile" value="{{  $user->phone_mobile }}" /></td>
    </tr>

    <tr>
        <td><label for="email">Email</label></td>
        <td><input type="text" name="email" value="{{  $user->email }}" /></td>
    </tr>

    <tr>
        <td></td><td>&nbsp;</td>
    </tr>

    <tr>
        <td id="label_birthdate"><label for="birthdate">Geburtsdatum</label></td>
        <td id="input_birthdate"><input type="text" name="birthdate" value="{{  $user->birthdate }}" /></td>
    </tr>

    <tr>
        <td><label for="comments">Bemerkungen</label></td>
        <td><textarea name="comments" id="comments" value="{{  $user->comments }}"></textarea></td>
    </tr>

</table>



