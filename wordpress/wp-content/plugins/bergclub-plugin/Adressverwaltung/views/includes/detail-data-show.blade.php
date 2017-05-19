<table class="user-detail">
    <tr>
        <td>Adresstyp:</td>
        <td>{{ $user->address_role_name }}</td>
    </tr>
    @if($user->leaving_reason)
        <tr>
            <td>Austrittsgrund:</td>
            <td>{{ $user->leaving_reason }}</td>
        </tr>
    @else
        <tr>
            <td>Versand Programm:</td>
            <td>{{ $user->program_shipment }}</td>
        </tr>
    @endif
    <tr>
        <td></td>
        <td>&nbsp;</td>
    </tr>
    @if($user->company)
        <tr>
            <td>Anrede:</td>
            <td>{{ $user->company }}</td>
        </tr>
    @endif
    @if($user->gender)
        <tr>
            <td>Anrede:</td>
            <td>{{ $user->gender }}</td>
        </tr>
    @endif
    @if($user->last_name)
        <tr>
            <td>Nachname:</td>
            <td>{{ $user->last_name }}</td>
        </tr>
    @endif
    @if($user->first_name)
        <tr>
            <td>Vorname:</td>
            <td>{{ $user->first_name }}</td>
        </tr>
    @endif
    @if($user->address_addition)
        <tr>
            <td>Zusatz:</td>
            <td>{{ $user->address_addition }}</td>
        </tr>
    @endif
    @if($user->street)
        <tr>
            <td>Strasse:</td>
            <td>{{ $user->street }}</td>
        </tr>
    @endif
    @if($user->zip && $user->location)
        <tr>
            <td>PLZ / Ort:</td>
            <td>{{ $user->zip . ' ' . $user->location }}</td>
        </tr>
    @endif
    <tr>
        <td></td>
        <td>&nbsp;</td>
    </tr>
    @if($user->phone_private)
        <tr>
            <td>Telefon P:</td>
            <td>{{ $user->phone_private }}</td>
        </tr>
    @endif
    @if($user->phone_work)
        <tr>
            <td>Telefon G:</td>
            <td>{{ $user->phone_work }}</td>
        </tr>
    @endif
    @if($user->phone_mobile)
        <tr>
            <td>Telefon M:</td>
            <td>{{ $user->phone_mobile }}</td>
        </tr>
    @endif
    @if($user->email)
        <tr>
            <td>Email:</td>
            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
        </tr>
    @endif
    <tr>
        <td></td>
        <td>&nbsp;</td>
    </tr>
    @if($user->birthdate)
        <tr>
            <td>Geburtsdatum:</td>
            <td>{{ $user->birthdate }}</td>
        </tr>
    @endif
    <tr>
        <td></td>
        <td>&nbsp;</td>
    </tr>
    @if($user->spouse)
        <tr>
            <td>Ehepartner:</td>
            <td>
                <a href="?page={{ $_GET['page'] }}&view=detail&id={{ $spouse->ID }}">{{ $user->spouse->first_name }} {{ $user->spouse->last_name }}</a>
            </td>
        </tr>
    @endif
    @if($user->comments)
        <tr>
            <td class="comments">Bemerkungen:</td>
            <td class="comments">{{ nl2br(e($user->comments)) }}</td>
        </tr>
    @endif
</table>
