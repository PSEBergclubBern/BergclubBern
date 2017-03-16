<?php
/**
 * @package Bergclub-Plugin
 */
/*
Plugin Name: Bergclub-Einstellungen
Plugin URI: https://github.com/PSEBergclubBern/BergclubPlugin
Description: This Plugin is designed to modify an existing wordpress installation to the needs of the customer
Version: 1.0
Author: PSE
Author URI: http://unibe.ch
License: GPLv2 or later
Text Domain: unibe.ch
*/

// Make sure we don't expose any info if called directly
//if ( !function_exists( 'add_action' ) ) {
	//echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	//exit;
//}

// Hook for adding pages to the admin menu
add_action('admin_menu', 'bcb_add_settings_pages');

// Hook to delete "Mitgliederbeitrag"
// Hinweis: Ich glaube nicht, dass das funktioniert => der erste String muss ein existierender WP Hook sein
//add_action('admin_post_delete_mitgliederbeitrag', 'bcb_delete_mitgliederbeitrag');

// Function to add pages to the admin menu
function bcb_add_settings_pages(){

    // Add page for "Mitgliederbeiträge"
    add_options_page('Mitgliederbeiträge', 'Mitgliederbeiträge verwalten', 'read', 'mitgliederbeitraege', 'bcb_display_mitgliederbeitraege_page');

}


// Function that displays the page with the "Mitgliederbeiträge"
function bcb_display_mitgliederbeitraege_page(){
    //Hinweis: wenn du in der Funktionserklärung bei Parameter $variable = wert hast, dann heisst das der Wert ist der
    //default, wenn du nichts angibst. Falls du etwas angibst, dann nur den Wert.
    $mitgliederBeitraege = get_option('bcb_mitgliederbeitraege');

    $saved = false;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        foreach($_POST['beitraege'] as $key => $amount){
            $mitgliederBeitraege[$key]['amount'] = $amount * 1;
        }

        update_option('bcb_mitgliederbeitraege', $mitgliederBeitraege, 'no');

        $saved = true;
    }

    // Import jquery and Datatable
    // Hinweis: Braucht es nicht wenn die Anzahl Einträge fix sind.
    //echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>';
    //echo '<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>';

    // Include selfwritten script
    //echo '<script type="text/javascript" src="../include/mitgliederbeitraege.js"></script>';

    // Display currently recorded "Mitgliederbeiträge"
    //$arr_mitgliederbeitraege = get_option('bcb_mitgliederbeitraege', $default = false);

    echo '<h1>Mitgliederbeiträge verwalten</h1>';

    if($saved) {
        echo '<div class="notice notice-success">';
        echo '<p>Änderungen gespeichert.</p>';
        echo '</div>';
    }

    echo '<form method="post">';
    echo '<table>';
    echo '<thead><tr><th align="left">Kategorie</th><th align="right">Beitrag</th></tr></thead>';
    echo '<tbody>';

    foreach($mitgliederBeitraege as $key => $beitrag){
        echo '<tr>';
        echo '<td align="left">' . $beitrag['name'] . '</td>';
        echo '<td align="right">';
        echo '<input type="text" name="beitraege[' . $key . ']" value="' . number_format($beitrag['amount'], 2, '.', '\'') . '" style="width:60px;text-align:right"></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '<input type="submit" value="Speichern">';
    echo '</form>';


    // Form to add a new category




}




/**

// Hook for adding admin menus
add_action('admin_menu', 'bcp_add_pages');

// action function for above hook
function bcp_add_pages() {
    // Add a new submenu under Settings:
    add_options_page(__('Test Settings','menu-ExampleTestClasses'), __('Test Settings','menu-ExampleTestClasses'), 'manage_options', 'testsettings', 'bcp_settings_page');

    // Add a new submenu under Tools:
    add_management_page( __('Test Tools','menu-ExampleTestClasses'), __('Test Tools','menu-ExampleTestClasses'), 'manage_options', 'testtools', 'bcp_tools_page');

    // Add a new top-level menu (ill-advised):
    add_menu_page(__('Bergclub Tourenverwaltung','menu-ExampleTestClasses'), __('Bergclub Tourenverwaltung','menu-ExampleTestClasses'), 'manage_options', 'bcp-top-level-handle', 'bcp_toplevel_page' );

    // Add a submenu to the custom top-level menu:
    add_submenu_page('bcp-top-level-handle', __('Test Sublevel','menu-ExampleTestClasses'), __('Test Sublevel','menu-ExampleTestClasses'), 'manage_options', 'sub-page', 'bcp_sublevel_page');

    // Add a second submenu to the custom top-level menu:
    add_submenu_page('bcp-top-level-handle', __('Test Sublevel 2','menu-ExampleTestClasses'), __('Test Sublevel 2','menu-ExampleTestClasses'), 'manage_options', 'sub-page2', 'bcp_sublevel_page2');
}

// bcp_settings_page() displays the page content for the Test Settings submenu
//function bcp_settings_page() {
//    echo "<h2>" . __( 'Test Settings', 'menu-ExampleTestClasses' ) . "</h2>";
//}

// bcp_tools_page() displays the page content for the Test Tools submenu
function bcp_tools_page() {
    echo "<h2>" . __( 'Test Tools', 'menu-ExampleTestClasses' ) . "</h2>";
}

// bcp_toplevel_page() displays the page content for the custom Test Toplevel menu
function bcp_toplevel_page() {
    echo "<h2>" . __( 'Test Toplevel', 'menu-ExampleTestClasses' ) . "</h2>";
}

// bcp_sublevel_page() displays the page content for the first submenu
// of the custom Test Toplevel menu
function bcp_sublevel_page() {
    echo "<h2>" . __( 'Test Sublevel', 'menu-ExampleTestClasses' ) . "</h2>";
}

// bcp_sublevel_page2() displays the page content for the second submenu
// of the custom Test Toplevel menu
function bcp_sublevel_page2() {
    echo "<h2>" . __( 'Test Sublevel2', 'menu-ExampleTestClasses' ) . "</h2>";
}


// bcp_settings_page() displays the page content for the Test Settings submenu
function bcp_settings_page() {


    //must check that the user has the required capability
    if (!current_user_can('manage_options'))
    {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names
    $opt_name = 'bcp_favorite_color';
    $hidden_field_name = 'bcp_submit_hidden';
    $data_field_name = 'bcp_favorite_color';

    $repo = new FavColours();


    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
    $repo->colour = $opt_val;
    //$repo->colour = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
        $repo->colour = $opt_val;

        // Put a "settings saved" message on the screen

        ?>
        <div class="updated"><p><strong><?php _e('settings saved.', 'menu-ExampleTestClasses' ); ?></strong></p></div>
        <?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Menu Test Plugin Settings', 'menu-ExampleTestClasses' ) . "</h2>";
    echo "<h3>" . $opt_val . "</h3>";
    echo "<h3> Object value: " . $repo->colour . "</h3>";
    if ($repo->checkIfColoutIsRed() === true){
        echo "<h3> You typed 'red'! "  . "</h3>";
    }

    // settings form

    ?>

    <form name="form1" method="post" action="">
        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

        <p><?php _e("Favorite Color:", 'menu-ExampleTestClasses' ); ?>
            <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
            <!--<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $repo->colour; ?>" size="20">-->
        </p><hr />

        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>

    </form>
    </div>

    <?php

}

*/
?>
