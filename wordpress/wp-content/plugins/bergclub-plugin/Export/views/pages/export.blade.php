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
        <td>Kalender (PDF)</td>
        <td><label for="download-calendar">Jahr:</label> <input type="number" id="download-calendar" min="2006" max="{{ date("Y") }}" value="{{ date("Y") }}"></td>
        <td><button id="button-calendar" class="button button-primary button-download" data-href="?page={{ $_GET['page'] }}&download=calendar">Herunterladen</button></td>
    </tr>
</table>
    <script type="text/javascript">
        jQuery('.download-address').click(function(){
            jQuery('#button-address').data('href', '?page={{ $_GET['page'] }}&download=' + jQuery(this).val());
        });

        jQuery('#download-calendar').bind('keyup mouseup', function(){
            console.log('?page={{ $_GET['page'] }}&download=calendar&year=' + jQuery(this).val());
            jQuery('#button-calendar').data('href', '?page={{ $_GET['page'] }}&download=calendar&year=' + jQuery(this).val());
        });

        jQuery('.button-download').click(function(){
            window.open(jQuery(this).data('href'));
        });
    </script>
@endsection