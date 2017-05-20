<table>
    <tbody>
    <tr>
        <th align="left">Treffpunkt</th>
        <td>
            <select name="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::MEETPOINT }}"
                    id="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::MEETPOINT }}"
                    style="width:100%;max-width:200px">
                @foreach($meetingPoints as $meetingPoint)
                    @if($meetingPoint['id'] == $values[ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::MEETPOINT])
                        <option selected="selected"
                                value="{{ $meetingPoint['id'] }}">{{ $meetingPoint['text'] }}</option>
                    @else
                        <option value="{{ $meetingPoint['id'] }}">{{ $meetingPoint['text'] }}</option>
                    @endif

                @endforeach

            </select>
        </td>
    </tr>

    <tr>
        <th align="left">Treffpunkt Anderer</th>
        <td>
            <input type="text"
                   id="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::MEETPOINT_DIFFERENT }}"
                   name="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::MEETPOINT_DIFFERENT }}"
                   value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::MEETPOINT_DIFFERENT ] }}"
                   style="width:100%;max-width:200px"
            />
        </td>
    </tr>
    <tr>
        <th align="left">Zeit</th>
        <td>
            <input type="text"
                   class="bcb-time-picker-field"
                   id="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::TIME }}"
                   name="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::TIME }}"
                   value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::TIME ] }}"
                   style="width:100%;max-width:200px"
            />
        </td>
    </tr>

    <tr>
        <th align="left">Rückkehr (Bern an)</th>
        <td>
            <input type="text"
                   id="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::RETURNBACK }}"
                   name="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::RETURNBACK }}"
                   value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::RETURNBACK ] }}"
                   style="width:100%;max-width:200px"
            />
        </td>
    </tr>

    <tr>
        <th align="left">Verpflegung</th>
        <td>
            <input type="text"
                   id="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::FOOD }}"
                   name="{{ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::FOOD }}"
                   value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\MeetingPoint::FOOD ] }}"
                   style="width:100%;max-width:200px"
            />
        </td>
    </tr>
    </tbody>
</table>