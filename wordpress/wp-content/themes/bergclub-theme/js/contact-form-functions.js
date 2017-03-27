$(document).ready(function(){

    $(".birthday").hide();

    var $inputs = $('input[name=phone-p], input[name=phone-g], input[name=phone-m], input[name=email]');
    $inputs.on('input', function() {
        var val = $('#enquirytype').val();
        if(val==="membership") {
            $inputs.not(this).prop('required', !$(this).val().length);
        }
    } );

    $("#enquirytype").change(function(){
        $('.maybe-required').prop('required',false);

        var val = $('#enquirytype').val();
        $('.' + val + '-required').prop('required', true);
        $('.always-required').prop('required', true);

        if(val==="membership"){
            $('input[name=email]').prop('required', true);
            $(".birthday").show();
        } else {
            $(".birthday").hide();
        }
    })
;});