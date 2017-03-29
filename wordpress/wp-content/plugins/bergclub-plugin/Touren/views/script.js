/**
 * Created by kevstuder on 22.03.17.
 */
jQuery(document).ready(function($){
    $('.bcb-date-picker-field').datepicker();
    $('.bcb-time-picker-field').timepicker({
        'scrollDefault': 'now',
        'timeFormat': 'H:i'
    });

    updateDifficultiesByTypes();
    $('#_type').change(function () {
        updateDifficultiesByTypes();
    });

    updateMeetpoint();
    $('#_meetpoint').change(function () {
        updateMeetpoint();
    });


});

function updateMeetpoint() {
    var meetpointValue = jQuery('#_meetpoint').val();
    console.log(meetpointValue);
    if (meetpointValue == 99) {
        jQuery('#_meetpointDifferent').parent().parent().show();
    } else {
        jQuery('#_meetpointDifferent').parent().parent().hide();
    }
}

function updateDifficultiesByTypes() {
    var typeValue = jQuery('#_type').val();
    var difficultiesByTypes = jQuery('#_requirementsTechnical > optGroup');
    var optGroupIds = [];
    for (var i = 0; i < difficultiesByTypes.length; i++) {
        optGroupIds.push(difficultiesByTypes[i].id);
    }

    for (i = 0; i < optGroupIds.length; i++) {
        if (optGroupIds[i].substr(-typeValue.length) === typeValue) {
            jQuery('#' + optGroupIds[i]).show();
        } else {
            jQuery('#' + optGroupIds[i]).hide();
        }
    }
}