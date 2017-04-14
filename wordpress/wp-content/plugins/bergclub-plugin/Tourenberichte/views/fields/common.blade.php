<table>
    <tbody>
        <tr>
            <th align="left">Bisher publizierte Touren (gehört zu Tour mit ID {{$values[ \BergclubPlugin\Tourenberichte\MetaBoxes\Common::TOUREN]}})</th>
            <td>
                <select name="{{ \BergclubPlugin\Tourenberichte\MetaBoxes\Common::TOUREN }}">
                    @foreach($touren as $tour)
                        @if($tour->ID == $values[ \BergclubPlugin\Tourenberichte\MetaBoxes\Common::TOUREN])
                            <option selected="selected" value="{{ $tour->ID }}">{{ $tour->post_title }}</option>
                        @else
                            <option value="{{ $tour->ID }}">{{ $tour->post_title }} </option>
                        @endif

                    @endforeach
                </select>
            </td>
        </tr>

    </tbody>
</table>