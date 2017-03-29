<table>
    <tbody>
        <tr>
            <th align="left">Datum von</th>
            <td>
                <input type="text"
                       class="bcb-date-picker-field"
                       id="{{ \BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_IDENTIFIER }}"
                       name="{{ \BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_IDENTIFIER }}"
                       value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_IDENTIFIER ] }}">
            </td>
        </tr>
        <tr>
            <th align="left">Datum bis</th>
            <td>
                <input type="text"
                       class="bcb-date-picker-field"
                       id="{{ \BergclubPlugin\Touren\MetaBoxes\Common::DATE_TO_IDENTIFIER }}"
                       name="{{ \BergclubPlugin\Touren\MetaBoxes\Common::DATE_TO_IDENTIFIER }}"
                       value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\Common::DATE_TO_IDENTIFIER ] }}">
            </td>
        </tr>
        <tr>
            <th align="left">Leiter</th>
            <td>
                <select name="{{ \BergclubPlugin\Touren\MetaBoxes\Common::LEADER }}">
                    @foreach($leiter as $user)
                        @if($user->ID == $values[ \BergclubPlugin\Touren\MetaBoxes\Common::LEADER])
                            <option selected="selected" value="{{ $user->ID }}">{{ $user->last_name }} {{ $user->first_name }}</option>
                        @else
                            <option value="{{ $user->ID }}">{{ $user->last_name }} {{ $user->first_name }}</option>
                        @endif

                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <th align="left">Co-Leiter</th>
            <td>
                <select name="{{ \BergclubPlugin\Touren\MetaBoxes\Common::CO_LEADER }}">
                    @foreach($coLeiter as $user)
                        @if($user->ID == $values[ \BergclubPlugin\Touren\MetaBoxes\Common::CO_LEADER])
                            <option selected="selected" value="{{ $user->ID }}">{{ $user->last_name }} {{ $user->first_name }}</option>
                        @else
                            <option value="{{ $user->ID }}">{{ $user->last_name }} {{ $user->first_name }}</option>
                        @endif

                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <th align="left">Anmeldung bis</th>
            <td>
                <input type="text"
                       class="bcb-date-picker-field"
                       id="{{ \BergclubPlugin\Touren\MetaBoxes\Common::SIGNUP_UNTIL }}"
                       name="{{ \BergclubPlugin\Touren\MetaBoxes\Common::SIGNUP_UNTIL }}"
                       value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\Common::SIGNUP_UNTIL ] }}">
            </td>
        </tr>

        <tr>
            <th align="left">Anmeldung an</th>
            <td>
                <select name="{{ \BergclubPlugin\Touren\MetaBoxes\Common::SIGNUP_TO }}">
                    @foreach($signUpTo as $user)
                        @if($user->ID == $values[ \BergclubPlugin\Touren\MetaBoxes\Common::SIGNUP_TO])
                            <option selected="selected" value="{{ $user->ID }}">{{ $user->last_name }} {{ $user->first_name }}</option>
                        @else
                            <option value="{{ $user->ID }}">{{ $user->last_name }} {{ $user->first_name }}</option>
                        @endif

                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <th align="left">Übernachtung</th>
            <td>
                <textarea name="{{ \BergclubPlugin\Touren\MetaBoxes\Common::SLEEPOVER }}"
                        id="{{ \BergclubPlugin\Touren\MetaBoxes\Common::SLEEPOVER }}"
                          >{{ $values[ \BergclubPlugin\Touren\MetaBoxes\Common::SLEEPOVER ] }}</textarea>
            </td>
        </tr>
    </tbody>
</table>