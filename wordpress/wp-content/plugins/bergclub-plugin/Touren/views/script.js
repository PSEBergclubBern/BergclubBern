/**
 * Created by kevstuder on 22.03.17.
 */
jQuery(document).ready(function ($) {
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

    updateSignUpTo();
    $('#_leader').change(function () {
        updateSignUpTo();
    });
    $('#_coLeader').change(function () {
        updateSignUpTo();
    });


});

function updateMeetpoint() {
    var meetpointValue = jQuery('#_meetpoint').val();
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

function updateSignUpTo() {
    var leaderId = jQuery('#_leader').val();
    var coLeaderId = jQuery('#_coLeader').val();

    var leaderText = jQuery('#_leader option:selected').text();
    var coLeaderText = jQuery('#_coLeader option:selected').text();

    var selectedId = jQuery('#_signupTo').val();

    jQuery('#_signupTo')
        .find('option')
        .remove()
        .end()
    ;
    jQuery('#_signupTo').append(jQuery('<option>', {
        value: leaderId,
        text: leaderText
    }));
    jQuery('#_signupTo').append(jQuery('<option>', {
        value: coLeaderId,
        text: coLeaderText
    }));

    jQuery('#_signupTo option').filter(function () {
        return this.value === selectedId
    }).prop('selected', true);
}