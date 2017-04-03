$("#enquirytype").change(function(){
    updateFields();
});

$(document).ready(function(){
   updateMissingFields();
   updateFields();
});

function updateMissingFields(){
    for(var idx in missingFields){
        $("div." + missingFields[idx] + " label").addClass("error");
    }
}

function updateFields(){
    var type = $("#enquirytype").val();
    for(var id in fieldSettings[type]){
        if(fieldSettings[type].hasOwnProperty(id)){
            if(fieldSettings[type][id].show){
                $("div." + id).show();
            }else{
                $("div." + id).hide();
            }

            if(fieldSettings[type][id].required){
                $("div." + id + " label").addClass("required");
                $("#" + id).prop('required',true);
            }else{
                $("div." + id + " label").removeClass("required");
                $("#" + id).prop('required',false);
            }
        }
    }
}
