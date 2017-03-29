<table>
    <tbody>
        <tr>
            <th align="left">Art</th>
            <td>
                <select name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TYPE }}"
                        id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::TYPE }}">
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
                <select name="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::REQUIREMENTS_TECHNICAL }}"
                        id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::REQUIREMENTS_TECHNICAL }}">
                    @foreach($difficulties as $key => $diffArray)
                        <optgroup id="{{ \BergclubPlugin\Touren\MetaBoxes\Tour::REQUIREMENTS_TECHNICAL }}-opt-{{ $key }}" label="{{ $diffArray['title'] }}">
                            @foreach($diffArray['options'] as $value)
                                @if($value == $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::REQUIREMENTS_TECHNICAL])
                                    <option selected="selected" value="{{ $value }}">{{ $value }}</option>
                                @else
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
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
                    <?php
                    $noCheckBox = (0 == $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::TRAINING ]) ? ' checked="checked"' : '';
                    $yesCheckBox = ('' == $noCheckBox ) ? ' checked="checked"' : '';
                    ?>
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
                    <?php
                    $noCheckBox = (0 == $values[ \BergclubPlugin\Touren\MetaBoxes\Tour::JSEVENT ]) ? ' checked="checked"' : '';
                    $yesCheckBox = ('' == $noCheckBox ) ? ' checked="checked"' : '';
                    ?>
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
                <textarea name="{{ BergclubPlugin\Touren\MetaBoxes\Tour::PROGRAM }}"
                          id = "{{ BergclubPlugin\Touren\MetaBoxes\Tour::PROGRAM }}"
                          >{{ $values[ BergclubPlugin\Touren\MetaBoxes\Tour::PROGRAM ] }}</textarea>
            </td>
        </tr>

        <tr>
            <th align="left">Ausrüstung</th>
            <td>
                <textarea name="{{ BergclubPlugin\Touren\MetaBoxes\Tour::EQUIPMENT }}"
                          id = "{{ BergclubPlugin\Touren\MetaBoxes\Tour::EQUIPMENT }}"
                >{{ $values[ BergclubPlugin\Touren\MetaBoxes\Tour::EQUIPMENT ] }}</textarea>
            </td>
        </tr>


        <tr>
            <th align="left">Kartenmaterial</th>
            <td>
                <input type="text"
                       name="{{ BergclubPlugin\Touren\MetaBoxes\Tour::MAPMATERIAL }}"
                       id = "{{ BergclubPlugin\Touren\MetaBoxes\Tour::MAPMATERIAL }}"
                       value="{{ $values[ BergclubPlugin\Touren\MetaBoxes\Tour::MAPMATERIAL ] }}"
                />
            </td>
        </tr>

        <tr>
            <th align="left">URL Online Route</th>
            <td>
                <input type="text"
                       name="{{ BergclubPlugin\Touren\MetaBoxes\Tour::ONLINEMAP }}"
                       id = "{{ BergclubPlugin\Touren\MetaBoxes\Tour::ONLINEMAP }}"
                       value="{{ $values[ BergclubPlugin\Touren\MetaBoxes\Tour::ONLINEMAP ] }}"
                />
            </td>
        </tr>

        <tr>
            <th align="left">Kosten (CHF)</th>
            <td>
                <input type="text"
                       name="{{ BergclubPlugin\Touren\MetaBoxes\Tour::COSTS }}"
                       id = "{{ BergclubPlugin\Touren\MetaBoxes\Tour::COSTS }}"
                       value="{{ $values[ BergclubPlugin\Touren\MetaBoxes\Tour::COSTS ] }}"
                />
            </td>
        </tr>

        <tr>
            <th align="left">Kostengrund</th>
            <td>
                <input type="text"
                       name="{{ BergclubPlugin\Touren\MetaBoxes\Tour::COSTS_FOR }}"
                       id = "{{ BergclubPlugin\Touren\MetaBoxes\Tour::COSTS_FOR }}"
                       value="{{ $values[ BergclubPlugin\Touren\MetaBoxes\Tour::COSTS_FOR ] }}"
                />
            </td>
        </tr>
    </tbody>
</table>