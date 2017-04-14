@extends('template')

@section('content')
    <table border="0" cellspacing="0" cellpadding="10">
        <tr><td colspan="3"><h2>Adressen</h2></td></tr>
        <tr>
            <td colspan="2">Mitgliederliste (Excel)</td>
            <td><button class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=members">Herunterladen</button></td>
        </tr>
        <tr>
            <td>Versandliste (Excel)</td>
            <td><label><input type="radio" class="download-address" name="download-address" value="addresses"/> Komplett</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" class="download-address" name="download-address" value="shipping" checked/> Programm</label></td>
            <td><button id="button-address" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=shipping">Herunterladen</button></td>
        </tr>
        <tr>
            <td colspan="2">Beitragsliste (Excel)</td>
            <td><button class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=contributions">Herunterladen</button></td>
        </tr>
        <tr><td colspan="3"><h2>Touren</h2></td></tr>
        <tr>
            <td>Übersicht (Excel)</td>
            <td><label><input type="radio" class="download-touren-status" name="download-touren-status" value="any" checked/> Alle</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" class="download-touren-status" name="download-touren-status" value="publish"/> Publizierte</label>&nbsp;&nbsp;&nbsp;<label for="download-touren-from">Von:</label> <input type="text" class="datepicker" id="download-touren-from" value="01.01.{{ date("Y") }}" size="8"/> <label for="download-touren-to">Bis:</label> <input type="text" class="datepicker" id="download-touren-to" value="31.12.{{ date("Y") }}" size="8"/></td>
            <td><button id="button-touren" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=touren&status=any&from=01.01.{{ date('Y') }}&to=31.12.{{ date('Y') }}">Herunterladen</button></td>
        </tr>
        <tr>
            <td>Kalender (PDF)</td>
            <td><label for="download-calendar">Jahr:</label> <input type="number" id="download-calendar" min="2006" max="{{ date("Y") }}" value="{{ date("Y") }}"></td>
            <td><button id="button-calendar" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=calendar">Herunterladen</button></td>
        </tr>
    </table>
    <script type="text/javascript">
        jQuery('.download-address').click(function(){
            jQuery('#button-address').data('href', '?page={{ $_GET['page'] }}&download=' + jQuery(this).val());
        });

        jQuery('.download-touren-status').click(function(){
            updateTourenDownload();
        });

        jQuery('#download-touren-from').bind('keyup mouseup blur change', function(){
            updateTourenDownload();
        });

        jQuery('#download-touren-to').bind('keyup mouseup blur change', function(){
            updateTourenDownload();
        });

        jQuery('#download-calendar').bind('keyup mouseup blur', function(){
            jQuery('#button-calendar').data('href', '?page={{ $_GET['page'] }}&download=calendar&year=' + jQuery(this).val());
        });

        jQuery('.button-download').click(function(){
            window.open(jQuery(this).data('href'));
        });

        function updateTourenDownload() {
            var status = jQuery("input:radio[name = 'download-touren-status']:checked").val();
            var from = jQuery('#download-touren-from').val();
            var to = jQuery('#download-touren-to').val();


            var href = '?page={{ $_GET['page'] }}&download=touren&status=' + status + '&from=' + from + '&to=' + to;
            console.log(href);
            jQuery('#button-touren').data('href', href);
        }
    </script>
@endsection