jQuery('#upload-button').click(function () {
    jQuery(this).html('Bitte warten...').attr('disabled', true);
    jQuery('#upload-form').submit();
});

jQuery('.save-button').click(function () {
    jQuery(this).html('Bitte warten...');
    jQuery('.save-button').attr('disabled', true);
    jQuery('.delete-button').attr('disabled', true);
    jQuery('#images-form').submit();
});

jQuery('.delete-button').click(function () {
    jQuery(this).html('Bitte warten...');
    jQuery('.save-button').attr('disabled', true);
    jQuery('.delete-button').attr('disabled', true);
    jQuery('#images-action').val('delete');
    jQuery('#images-key').val(jQuery(this).data('key'));
    jQuery('#images-form').submit();
});
