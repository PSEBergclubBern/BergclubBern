@foreach( $users as $potentialSpouse )
    @if( $potentialSpouse->ID != $user->ID )
        <input type="radio" name="spouse"
               value="{{ $potentialSpouse->ID }}">{{ $potentialSpouse->last_name }} {{ $potentialSpouse->first_name }}
        <br>
    @endif
@endforeach