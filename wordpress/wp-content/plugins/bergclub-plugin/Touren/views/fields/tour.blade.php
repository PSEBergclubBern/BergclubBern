<table>
    <tbody>
        <tr>
            <th align="left">Art</th>
            <td>
                <select name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TYPE }}">
                    @foreach($tourenarten as $key => $tourenart)
                        @if($key == $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::TYPE])
                            <option selected="selected" value="{{ $key }}">{{ $tourenart }}</option>
                        @else
                            <option value="{{ $key }}">{{ $tourenart }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
        </tr>


        <tr>
            <th align="left">Anforderungen technisch (Artabhängig)</th>
            <td>
            </td>
        </tr>

        <tr>
            <th align="left">Anforderungen konditionell</th>
            <td>
                <select name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::REQUIREMENTS_CONDITIONAL }}">
                    @foreach($conditions as $key => $condition)
                        @if($key == $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::REQUIREMENTS_CONDITIONAL])
                            <option selected="selected" value="{{ $key }}">{{ $condition }}</option>
                        @else
                            <option value="{{ $key }}">{{ $condition }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <th align="left">Aufstieg Höhenmeter</th>
            <td>
                <input type="text"
                       id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::RISE_UP_METERS }}"
                       name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::RISE_UP_METERS }}"
                       value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::RISE_UP_METERS ] }}"
                />
            </td>
        </tr>

        <tr>
            <th align="left">Abstieg Höhenmeter</th>
            <td>
                <input type="text"
                       id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::RISE_DOWN_METERS }}"
                       name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::RISE_DOWN_METERS }}"
                       value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::RISE_DOWN_METERS ] }}"
                />
            </td>
        </tr>

        <tr>
            <th align="left">Dauer</th>
            <td>
                <input type="text"
                       id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::DURATION }}"
                       name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::DURATION }}"
                       value="{{ $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::DURATION ] }}"
                />
            </td>
        </tr>

        <tr>
            <th align="left">Besonderes</th>
            <td>
                <textarea id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::ADDITIONAL_INFO }}"
                          name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::ADDITIONAL_INFO }}"
                          >{{ $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::ADDITIONAL_INFO ] }}</textarea>
            </td>
        </tr>

        <tr>
            <th align="left">Training</th>
            <td>
                <fieldset>
                    @if(0 == $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::TRAINING])
                        <?php
                        $noCheckBox = ' checked="checked"';
                        $yesCheckBox = '';
                        ?>
                    @else
                        <?php
                        $noCheckBox = '';
                        $yesCheckBox = ' checked="checked"';
                        ?>
                    @endif
                    <input type="radio"
                           id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TRAINING }}-NO"
                           name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TRAINING }}"
                           value="0"<?=$noCheckBox;?> />
                    <label for="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TRAINING }}-NO">Nein</label>
                    <input type="radio"
                           id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TRAINING }}-YES"
                           name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TRAINING }}"
                           value="1"<?=$yesCheckBox;?> />
                    <label for="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TRAINING }}-YES">Ja</label>
                </fieldset>
            </td>
        </tr>

        <tr>
            <th align="left">J+S Event</th>
            <td>
                <fieldset>
                    @if(0 == $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::JSEVENT])
                        <?php
                        $noCheckBox = ' checked="checked"';
                        $yesCheckBox = '';
                        ?>
                    @else
                        <?php
                        $noCheckBox = '';
                        $yesCheckBox = ' checked="checked"';
                        ?>
                    @endif
                    <input type="radio"
                           id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::JSEVENT }}-NO"
                           name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::JSEVENT }}"
                           value="0"<?=$noCheckBox;?> />
                    <label for="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::JSEVENT }}-NO">Nein</label>
                    <input type="radio"
                           id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::JSEVENT }}-YES"
                           name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::JSEVENT }}"
                           value="1"<?=$yesCheckBox;?> />
                    <label for="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::JSEVENT }}-YES">Ja</label>
                </fieldset>

            </td>
        </tr>

        <tr>
            <th align="left">Programm</th>
            <td>

            </td>
        </tr>

        <tr>
            <th align="left">Ausrüstung</th>
            <td>

            </td>
        </tr>


        <tr>
            <th align="left">Kartenmaterial</th>
            <td>

            </td>
        </tr>

        <tr>
            <th align="left">URL Online Route</th>
            <td>

            </td>
        </tr>

        <tr>
            <th align="left">Kosten (CHF)</th>
            <td>

            </td>
        </tr>

        <tr>
            <th align="left">Kostengrund</th>
            <td>

            </td>
        </tr>
    </tbody>
</table>