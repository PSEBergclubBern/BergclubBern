<script>
    jQuery(document).ready(function ($) {
        jQuery(".datepicker").datepicker();
    });

    @if(($tab=='data' && $edit) || $_GET['view'] == 'new')
        jQuery(document).ready(function () {
        displayForm();

        var $arrRequired = <?= json_encode($required) ?>;

        var $unset = $arrRequired['bcb_unset'];
        setFieldsNotRequired($unset);

        var $selected = getAddressType();
        var $required = $arrRequired[$selected];

        jQuery.each($required, function (index, value) {
            setFieldRequired(index);
        });

    });

    jQuery("#address_type").change(function () {
        displayForm();

        var $arrRequired = <?= json_encode($required) ?>;

        var $unset = $arrRequired['bcb_unset'];
        setFieldsNotRequired($unset);

        var $selected = getAddressType();
        var $required = $arrRequired[$selected];

        jQuery.each($required, function (index, value) {
            setFieldRequired(index);
        });

    });

    function displayForm() {
        var $selected = getAddressType();

        if ($selected == 'bcb_institution' || $selected == 'bcb_inserent') {
            hide('phone_private');
            hide('birthdate');
            hide('spouse');
            hide('leaving_reason');
            show('company');
        }

        else if ($selected == 'bcb_interessent' || $selected == 'bcb_interessent_jugend' || $selected == 'bcb_ehrenmitglied' || $selected == 'bcb_freimitglied') {
            hide('leaving_reason');
            hide('company');
            show('phone_private');
            show('birthdate');
            hide('spouse');
        }

        else if ($selected == 'bcb_aktivmitglied' || $selected == 'bcb_aktivmitglied_jugend') {
            hide('leaving_reason');
            hide('company');
            show('phone_private');
            show('birthdate');
            show('spouse');
        }

        else if ($selected == 'bcb_ehemalig') {
            hide('company');
            show('phone_private');
            show('birthdate');
            hide('spouse');
            show('leaving_reason');
        }

        else {
            hide('leaving_reason');
            hide('company');
            hide('birthdate');
            hide('spouse');
            hide('phone_private');
        }

    }

    function show(field) {
        jQuery(".td-" + field).show();
    }

    function hide(field) {
        jQuery(".td-" + field).hide();
        jQuery("#" + field).val('');
    }

    function getAddressType() {
        return jQuery("#address_type").val();
    }

    function setFieldRequired(field) {
        jQuery("#label-" + field).addClass("required");
    }

    function setFieldsNotRequired(arrFields) {
        jQuery.each(arrFields, function (index, value) {
            jQuery("#label-" + index).removeClass("required");
        });
    }

    @endif
</script>