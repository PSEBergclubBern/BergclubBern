<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Bergclub Bern
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site <?php echo (bcb_is_jugend()) ? "jugend" : "" ?>">

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php if (!bcb_is_jugend()) {
                    echo "<a class='navbar-brand' href='" . home_url() . "'>Bergclub Bern</a>";
                } else {
                    echo "<a class='navbar-brand' href='" . bcb_jugend_home() . "'>Bergclub Jugend</a>";
                }
                ?>

            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <?php /* Primary navigation */
                wp_nav_menu(array(
                        'menu' => 'Hauptmenü',
                        'theme_location' => 'header-menu',
                        'depth' => 2,
                        'container' => false,
                        'menu_class' => 'nav navbar-nav',
                        //Process nav menu using our custom nav walker
                        'walker' => new wp_bootstrap_navwalker())
                );
                ?>

                <ul class="nav navbar-nav navbar-right">
                    <?php if (!bcb_is_jugend()) {
                        echo "<li class='switch'><a href='" . bcb_jugend_home() . "'>Jugend &raquo;</a>";
                    } else {
                        echo "<li class='switch'><a href='" . home_url() . "'>Bergclub &raquo;</a>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <div id="content" class="site-content">