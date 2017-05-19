@extends('template')

@section('content')
    <table border="0" cellspacing="0" cellpadding="10">
        @if(in_array('adressen', $allowed))
            <tr><td colspan="4"><h2>Adressen</h2></td></tr>
            <tr>
                <td colspan="3"><b>Mitgliederliste (Excel)</b></td>
                <td><button class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=members.xls">Herunterladen</button></td>
            </tr>
            <tr>
                <td><b>Versandliste (Excel)</b></td>
                <td colspan="2"><label><input type="radio" class="download-address" name="download-address" value="addresses.xls"/> Komplett</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" class="download-address" name="download-address" value="shipping.xls" checked/> Programm</label></td>
                <td><button id="button-address" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=shipping.xls">Herunterladen</button></td>
            </tr>
            <tr>
                <td colspan="3"><b>Beitragsliste (Excel)</b></td>
                <td><button class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=contributions.xls">Herunterladen</button></td>
            </tr>
        @endif
        @if(in_array('touren', $allowed))
            <tr><td colspan="4"><h2>Touren</h2></td></tr>
            <tr>
                <td rowspan="2"><b>Übersicht (Excel)</b></td>
                <td colspan="2">
                    <label><input type="checkbox" class="touren-data" name="download-touren-status[]" value="publish" checked/> Publizierte</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="checkbox" class="touren-data" name="download-touren-status[]" value="future" checked/> Zukünftige (Publizierungsdatum in Zukunft)</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="checkbox" class="touren-data" name="download-touren-status[]" value="draft" checked/> Entwürfe</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="checkbox" class="touren-data" name="download-touren-status[]" value="pending" checked/> Veröffentlichung beantragt</label>&nbsp;&nbsp;&nbsp;
                </td>
                <td rowspan="2"><button id="button-touren" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=touren.xls&status=publish,future,draft,pending&from=01.01.{{ date('Y') }}&to=31.12.{{ date('Y') }}">Herunterladen</button></td>
            </tr>
            <tr>
                <td colspan="2><label for="download-touren-from">Von:</label> <input type="text" class="datepicker touren-data" id="download-touren-from" value="01.01.{{ date("Y") }}" size="8"/>
                    <label for="download-touren-to">Bis:</label> <input type="text" class="datepicker touren-data" id="download-touren-to" value="31.12.{{ date("Y") }}" size="8"/>
                </td>
            </tr>
            <tr>
                <td><b>Kalender (PDF)</b></td>
                <td colspan="2"><label for="download-calendar">Jahr:</label> <input type="number" id="download-calendar" min="2006" max="{{ date("Y") }}" value="{{ date("Y") }}"></td>
                <td><button id="button-calendar" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=calendar.pdf">Herunterladen</button></td>
            </tr>
        @endif
        @if(in_array('druck', $allowed))
            <tr><td colspan="4"><h2>Druck</h2></td></tr>
            <tr>
                <td><b>Pfarrblatt (Word)</b></td>
                <td colspan="2"><label for="download-pfarrblatt-from">Von:</label> <input type="text" class="datepicker pfarrblatt-data" id="download-pfarrblatt-from" value="01.01.{{ date("Y") }}" size="8"/> <label for="download-pfarrblatt-to">Bis:</label> <input type="text" class="datepicker pfarrblatt-data" id="download-pfarrblatt-to" value="31.12.{{ date("Y") }}" size="8"/></td>
                <td><button id="button-pfarrblatt" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=pfarrblatt.docx&from=01.01.{{ date('Y') }}&to=31.12.{{ date('Y') }}">Herunterladen</button></td>
            </tr>
            <tr>
                <td rowspan="2" valign="top"><b>Programm (Word)</b></td><td valign="bottom">Touren:</td><td><label for="download-program-touren-from">Von:</label> <input type="text" class="datepicker program-data" id="download-program-touren-from" value="{{ date('d.m.Y', strtotime($quarterTouren['from'])) }}" size="8"/> <label for="download-program-touren-to">Bis:</label> <input type="text" class="datepicker program-data" id="download-program-touren-to" value="{{ date('d.m.Y', strtotime($quarterTouren['to'])) }}" size="8"/><br/></td>
                <td rowspan="2"><button id="button-program" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=program.docx&touren-from={{ date('d.m.Y', strtotime($quarterTouren['from'])) }}&touren-to={{ date('d.m.Y', strtotime($quarterTouren['to'])) }}&review-from={{ date('d.m.Y', strtotime($quarterRueckblick['from'])) }}&review-to={{ date('d.m.Y', strtotime($quarterRueckblick['to'])) }}&published={{ date('d.m.Y', strtotime($quarterRueckblick['from'])) }}">Herunterladen</button></td>
            </tr>
            <tr>
                <td valign="bottom">Rückblick:</td><td><label for="download-program-review-from">Von:</label> <input type="text" class="datepicker program-data" id="download-program-review-from" value="{{ date('d.m.Y', strtotime($quarterRueckblick['from'])) }}" size="8"/> <label for="download-program-review-to">Bis:</label> <input type="text" class="datepicker program-data" id="download-program-review-to" value="{{ date('d.m.Y', strtotime($quarterRueckblick['to'])) }}" size="8"/></td>
            </tr>
        @endif
    </table>
    <script type="text/javascript">
        jQuery('.download-address').click(function(){
            jQuery('#button-address').data('href', '?page={{ $_GET['page'] }}&download=' + jQuery(this).val());
        });

        jQuery('.touren-data').bind('click keyup mouseup blur change', function(){
            updateTourenDownload();
        });

        jQuery('#download-calendar').bind('keyup mouseup blur', function(){
            jQuery('#button-calendar').data('href', '?page={{ $_GET['page'] }}&download=calendar.pdf&year=' + jQuery(this).val());
        });

        jQuery('.program-data').bind('keyup mouseup blur change', function(){
            updateProgramDownload();
        });

        jQuery('.button-download').click(function(){
            window.open(jQuery(this).data('href'));
        });

        function updateTourenDownload() {
            var status = jQuery('input[name="download-touren-status[]"]:checked').map(function () {
                return this.value;
            }).get().join(",");

            console.log(status);
            var from = jQuery('#download-touren-from').val();
            var to = jQuery('#download-touren-to').val();


            var href = '?page={{ $_GET['page'] }}&download=touren.xls&status=' + status + '&from=' + from + '&to=' + to;
            jQuery('#button-touren').data('href', href);
        }

        function updatePfarrblattDownload() {
            var from = jQuery('#download-pfarrblatt-from').val();
            var to = jQuery('#download-pfarrblatt-to').val();


            var href = '?page={{ $_GET['page'] }}&download=pfarrblatt.docx&from=' + from + '&to=' + to;
            jQuery('#button-pfarrblatt').data('href', href);
        }

        function updateProgramDownload() {
            var tourenFrom = jQuery('#download-program-touren-from').val();
            var tourenTo = jQuery('#download-program-touren-to').val();
            var reviewFrom = jQuery('#download-program-review-from').val();
            var reviewTo = jQuery('#download-program-review-to').val();

            var href = '?page={{ $_GET['page'] }}&download=program.docx&touren-from=' + tourenFrom + '&touren-to=' + tourenTo + '&review-from=' + reviewFrom + '&review-to=' + reviewTo;
            jQuery('#button-program').data('href', href);
        }
    </script>
@endsection