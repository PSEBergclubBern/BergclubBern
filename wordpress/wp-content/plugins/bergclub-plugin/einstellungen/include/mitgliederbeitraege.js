/**
 * Created by Dominik_Fankhauser on 14.03.2017.
 */

/** Function to display data table */
$(document).ready( function () {
    $("#mitgliederbeitraege").DataTable({paging:false, searching:false});
});


$('.delete-row').on( 'click', '', function () {
    table
        .row( $(this).parents('tr') )
        .remove()
        .draw();
} );