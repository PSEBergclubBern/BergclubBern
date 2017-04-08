<table>
    <tbody>
        <tr>
            <th align="left">Bisher publizierte Touren</th>
            <td>
                <select name="{{ \BergclubPlugin\Tourenberichte\MetaBoxes\CommonTourenberichte::TOUREN }}">
                    @foreach($touren as $tour)
                        @if($tour->ID == $values[ \BergclubPlugin\Tourenberichte\MetaBoxes\CommonTourenberichte::TOUREN])
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