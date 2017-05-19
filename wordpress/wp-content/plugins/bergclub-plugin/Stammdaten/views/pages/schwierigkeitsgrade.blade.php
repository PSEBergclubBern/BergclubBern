@extends('template')

@section('content')
    @if( $tourenart != null)
        <select id="tourenart">
            @foreach($tourenarten as $tourid => $tour)
                @if($tourid==$tourenartId)
                    <option selected="selected" value="{{$tourid}}">{{$tour}}</option>
                @else
                    <option value="{{$tourid}}">{{$tour}}</option>
                @endif
            @endforeach

        </select>

        <form method="post">
            <table>
                <thead>
                <tr>
                    <th align="left">Schwierigkeitsgrade für Tourenart {{$tourenart}}</th>
                </thead>
                <tbody>

                @forelse($schwierigkeitsgrade as $key => $grad)
                    <tr>
                        <td align="left"><label for="{{ $key }}">{{ $grad }}</label></td>
                        <td>
                            <a href="admin.php?page=bergclubplugin-stammdaten-schwierigkeitsgradecontroller&action=delete&tourid={{$tourenartId}}&id={{ $key }}"><span
                                        class="dashicons dashicons-trash"></span></a></td>
                    </tr>
                @empty
                    <tr>
                        <td>Keine Schwierigkeitsgrade erfasst für diese Tourenart.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </form>

        <form method="post">
            <h2>Neuer Schwierigkeitsgrad hinzufügen für {{$tourenart}}:</h2>
            <input type="text" id="new_schwierigkeitsgrad" name="new_schwierigkeitsgrad">
            <input type="submit" value="Speichern">
        </form>

    @else
        <p>Momentan sind keine Tourenarten erfasst. Daher können auch keine Schwierigkeitsgrade für Tourenarten erfasst
            werden.</p>
    @endif

@endsection

@section('script')
    <script type="text/javascript">
        jQuery('#tourenart').change(function () {
            document.location.href = '?page=bergclubplugin-stammdaten-schwierigkeitsgradecontroller&tourid=' + jQuery(this).val();
        });
    </script>
@stop

