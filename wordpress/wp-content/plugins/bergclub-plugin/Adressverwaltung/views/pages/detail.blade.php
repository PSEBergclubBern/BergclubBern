@extends('template')

@section('content')
    @if($edit)
        <form method="post">
            @endif
            <h2 class="nav-tab-wrapper">
                @if($tab=='data' || !$edit)
                    <a href="?page={{ $_GET['page'] }}&view=detail&id={{ $_GET['id'] }}" class="nav-tab @if($tab=='data') nav-tab-active @endif ">Daten</a>
                @endif
                @if($tab=='functions' || !$edit)
                    <a href="?page={{ $_GET['page'] }}&view=detail&tab=functions&id={{ $_GET['id'] }}" class="nav-tab @if($tab=='functions') nav-tab-active @endif ">Funktionen</a>
                @endif
                @if($tab=='history' || !$edit)
                    <a href="?page={{ $_GET['page'] }}&view=detail&tab=history&id={{ $_GET['id'] }}" class="nav-tab @if($tab=='history') nav-tab-active @endif ">Historie</a>
                @endif
            </h2>
            <div class="container">
                @include($tab_file)
                <div class="action-buttons">
                    @if($edit)
                        <input type="submit" class="button-primary" value="Speichern"/>
                        <a class="button-secondary" href="?page={{ $_GET['page'] }}&view={{ $_GET['view'] }}&tab={{ $tab }}&id={{ $_GET['id'] }}">Abbrechen</a>
                    @else
                        <a class="button-primary" href="?page={{ $_GET['page'] }}&view={{ $_GET['view'] }}&tab={{ $tab }}&id={{ $_GET['id'] }}&edit=1">Bearbeiten</a>
                    @endif
                </div>
            </div>
            @if($edit)
        </form>
    @endif
    @if(!$edit)
        <a class="button-primary" href="?page={{ $_GET['page'] }}">&laquo; Zurück</a>
    @endif
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function($) {
            jQuery(".datepicker").datepicker();
        });

        @if($tab=='data' && $edit)
            jQuery(document).ready(function() {
                displayForm();
            });

            jQuery("#address_type").change( function(){
                displayForm();
            });

            function displayForm(){
                var $selected = jQuery("#address_type").val();

                if ( $selected == 'bcb_institution' || $selected == 'bcb_inserent' ){
                    hide('phone_private');
                    hide('birthdate');
                    hide('leaving_reason');
                    show('company');
                }

                else if ( $selected == 'bcb_interessent' || $selected == 'bcb_interessent_jugend' || $selected == 'bcb_aktivmitglied' || $selected == 'bcb_aktivmitglied_jugend' || $selected == 'bcb_ehrenmitglied' ){
                    hide('leaving_reason');
                    hide('company');
                    show('phone_private');
                    show('birthdate');
                }

                else if ($selected == 'bcb_ehemalig'){
                    hide('company');
                    show('phone_private');
                    show('birthdate');
                    show('leaving_reason');
                }

                else{
                    hide('leaving_reason');
                    hide('company');
                    hide('birthdate');
                    hide('phone_private');
                }

            }

            function show(field){
                jQuery(".td-" + field).show();
            }

            function hide(field){
                jQuery(".td-" + field).hide();
                jQuery("#" + field).val('');
            }
        @endif
    </script>
@endsection