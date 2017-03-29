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
                    hidePhonePrivate();
                    hideBirthdate();
                    showCompany();
                    hideEhemalig();
                }

                else if ( $selected == 'bcb_interessent' || $selected == 'bcb_interessent_jugend' || $selected == 'bcb_aktivmitglied' || $selected == 'bcb_aktivmitglied_jugend' || $selected == 'bcb_ehrenmitglied' ){
                    showPhonePrivate();
                    showBirthdate();
                    hideCompany();
                    hideEhemalig();
                }

                else if ($selected == 'bcb_ehemalig'){
                    showPhonePrivate();
                    showBirthdate();
                    hideCompany();
                    showEhemalig();
                }

                else{
                    hidePhonePrivate();
                    hideBirthdate();
                    hideCompany();
                    hideEhemalig();
                }

            }

            function hideEhemalig(){
                jQuery("#label_leaving_reason").hide();
                jQuery("#input_leaving_reason").hide();
            }

            function showEhemalig(){
                jQuery("#label_leaving_reason").show();
                jQuery("#input_leaving_reason").show();
            }

            function hideCompany(){
                jQuery("#label_company").hide();
                jQuery("#input_company").hide();
            }

            function showCompany(){
                jQuery("#label_company").show();
                jQuery("#input_company").show();
            }

            function hidePhonePrivate(){
                jQuery("#label_phone_private").hide();
                jQuery("#input_phone_private").hide();
            }

            function showPhonePrivate(){
                jQuery("#label_phone_private").show();
                jQuery("#input_phone_private").show();
            }

            function hideBirthdate(){
                jQuery("#label_birthdate").hide();
                jQuery("#input_birthdate").hide();
            }

            function showBirthdate(){
                jQuery("#label_birthdate").show();
                jQuery("#input_birthdate").show();
            }

        @endif
    </script>
@endsection