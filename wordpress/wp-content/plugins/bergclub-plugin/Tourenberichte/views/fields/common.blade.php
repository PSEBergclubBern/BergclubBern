<table>
    <tbody>
        <tr>
            <th align="left">Bisher publizierte Touren (gehört zu Tour mit ID {{$values[ \BergclubPlugin\Tourenberichte\MetaBoxes\Common::TOUREN]}})</th>
            <td>
                <select name="{{ \BergclubPlugin\Tourenberichte\MetaBoxes\Common::TOUREN }}">
                    @foreach($touren as $tour)
                        @if($tour->ID == $values[ \BergclubPlugin\Tourenberichte\MetaBoxes\Common::TOUREN])
                            <option selected="selected" value="{{ $tour->ID }}">
                                [{{ get_post_meta($tour->ID, \BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_IDENTIFIER, true) }}] {{ $tour->post_title }}
                            </option>
                        @else
                            <option value="{{ $tour->ID }}">
                                [{{ get_post_meta($tour->ID, \BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_IDENTIFIER, true) }}] {{ $tour->post_title }}
                            </option>
                        @endif

                    @endforeach
                </select>
            </td>
        </tr>
    </tbody>
</table>