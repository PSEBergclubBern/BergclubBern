<table>
    <tbody>
        <tr>
            <th align="left">Leiter</th>
            <td>
                <select name="{{ \BergclubPlugin\Tourenberichte\MetaBoxes\CommonTourenberichte::LEADER }}">
                    @foreach($leiter as $user)
                        @if($user->ID == $values[ \BergclubPlugin\Tourenberichte\MetaBoxes\CommonTourenberichte::LEADER])
                            <option selected="selected" value="{{ $user->ID }}">{{ $user->post_title }}</option>
                        @else
                            <option value="{{ $user->ID }}">{{ $user->post_title }} </option>
                        @endif

                    @endforeach
                </select>
            </td>
        </tr>

    </tbody>
</table>