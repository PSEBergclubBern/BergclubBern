<?php
/**
 * Template for page-vorstand
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="container">
                <div class="row">
                    <?php the_title('<h1 class="page-header">', '</h1>'); ?>
                </div>
                <div class="row">
                    <div class="table-responsive page-content">
                        <table class="table table-hover">
                            <colgroup>
                                <col class="col-md-5">
                                <col class="col-md-4">
                                <col class="col-md-3">
                            </colgroup>
                            <?php do_action('bcb_vorstand_table', 'vorstand'); ?>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <h1 class="page-header">Weitere Chargen</h1>
                </div>
                <div class="row">
                    <div class="table-responsive page-content">
                        <table class="table table-hover">
                            <colgroup>
                                <col class="col-md-5">
                                <col class="col-md-4">
                                <col class="col-md-3">
                                <?php do_action('bcb_vorstand_table_chargen', 'vorstandchargen'); ?>
                            </colgroup>
                        </table>
                    </div>
                </div>

            </div>
        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer(); ?>