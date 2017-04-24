<table class="user-detail">
    @foreach( $required['bcb_all'] as $key => $value)

        @if ( $key == "address_type" )

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

        @elseif ( $key == 'leaving_reason' )

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

        @elseif ( $key == 'program_shipment')

            <tr>
                <td><label for="program_shipment">Versand Programm</label></td>
                <td>
                    <select id="program_shipment" name="program_shipment">
                        <option value="1" @if($user->program_shipment=='Ja') selected="selected" @endif>Ja</option>
                        <option value="0" @if($user->program_shipment=='Nein') selected="selected" @endif>Nein</option>
                    </select>
                </td>
            </tr>

        @elseif( $key == 'gender' )

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

        @elseif( $key == 'comments' )

            <tr>
                <td class="td-{{ $key }}"><label id="label-{{ $key }}" for="{{ $key }}">{{ $value }}</label></td>
                <td><textarea name="{{ $key  }}" id="{{ $key }}">{{  $user->comments }}</textarea></td>
            </tr>

        @elseif( $key == 'spouse')
            @if( $_GET['view']!='new' )
            <tr>
                <td class="td-{{ $key }}"><label id="label-{{ $key }}" for="{{ $key }}">{{ $value }}</label></td>

                @if( $spouse != null )
                    <td class="td-{{ $key }}">{{ $spouse->first_name }} {{ $spouse->last_name  }} <a href="?page={{ $_GET['page'] }}&view=detail&tab=data&id={{ $_GET['id'] }}&edit=1&action=deleteSpouse"><span class="dashicons dashicons-trash"></span></a></td>
                @else
                    <td class="td-{{ $key }}"><a href="?page={{ $_GET['page'] }}&view=detail&tab=spouse&id={{ $_GET['id'] }}&edit=1">Zuweisen</a></td>
                @endif

            </tr>
            @endif
        @else

            <tr>
                <td class="td-{{ $key }}"><label id="label-{{ $key }}" for="{{ $key }}">{{ $value }}</label></td>
                <td class="td-{{ $key }}"><input type="text" id="{{ $key }}" name="{{ $key }}" value='{{ $user->$key }}' /></td>
            </tr>

        @endif

    @endforeach
</table>



