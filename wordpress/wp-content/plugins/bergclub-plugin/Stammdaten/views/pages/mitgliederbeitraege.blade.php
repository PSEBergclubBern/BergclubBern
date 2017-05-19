@extends('template')

@section('content')
    <form method="post">
        <table>
            <thead>
            <tr>
                <th align="left">Kategorie</th>
                <th align="right">Beitrag</th>
            </tr>
            </thead>
            <tbody>

            @foreach($mitgliederBeitraege as $key => $beitrag)
                <tr>
                    <td align="left"><label for="beitraege[{{ $key }}]">{{ $beitrag['name'] }}</label></td>
                    <td align="right">
                        <input type="text" id="beitraege[{{ $key }}]" name="beitraege[{{ $key }}]"
                               value='{{ number_format($beitrag['amount'], 2, '.', '\'') }}'
                               style="width:60px;text-align:right"></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <input type="submit" value="Speichern">
    </form>
@endsection