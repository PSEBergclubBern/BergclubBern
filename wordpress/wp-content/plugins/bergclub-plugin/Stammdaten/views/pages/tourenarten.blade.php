@extends('template')

@section('content')
    <form method="post">
        <table>
            <thead>
            <tr>
                <th align="left">Tourenart</th>
            </thead>
            <tbody>

            @forelse($tourenarten as $key => $tourenart)
                <tr>
                    <td align="left"><label for="{{ $key }}">{{ $tourenart }}</label></td>
                    <td>
                        <a href="admin.php?page=bergclubplugin-stammdaten-schwierigkeitsgradecontroller&tourid={{ $key }}"><span
                                    class="dashicons dashicons-edit"></span></a></td>
                    <td>
                        <a href="admin.php?page=bergclubplugin-stammdaten-tourenartencontroller&action=delete&id={{ $key }}"><span
                                    class="dashicons dashicons-trash"></span></a></td>
                </tr>
            @empty
                <tr>
                    <td>Keine Tourenarten erfasst.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </form>

    <form method="post">
        <h2>Neue Tourenart hinzufügen:</h2>
        <input type="text" id="new_tourenart" name="new_tourenart">
        <input type="submit" value="Speichern">
    </form>
@endsection