@extends('template')

@section('content')
    <form method="post">
        <p><label for="key">Ein Wert:</label>
        <input type="text" id="key" name="key" value="{{ $key }}"></p>
        <p><input type="submit" value="Speichern"></p>
    </form>
@endsection