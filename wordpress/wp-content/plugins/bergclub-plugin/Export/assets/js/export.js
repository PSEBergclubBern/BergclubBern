 jQuery(document).ready(function($) {
        jQuery(".datepicker").datepicker({
            onSelect: function(){
                jQuery(this).change();
            }
        });
 });