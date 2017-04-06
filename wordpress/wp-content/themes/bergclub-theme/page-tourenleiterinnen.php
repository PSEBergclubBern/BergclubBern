<?php
/**
 * Created by PhpStorm.
 * User: Murph
 * Date: 06.04.2017
 * Time: 10:27
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
                            <?php
                            if(get_query_var("jugend", "false") == "false"){
                                do_action('bcb_tourenleiterinnen_table', 'tourenleiterinnen');
                            }else{
                                do_action('bcb_tourenleiterinnen_jugend_table', 'tourenleiterinnenjugend');
                            }
                             ?>
                        </table>
                    </div>
                </div>
            </div>
        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer(); ?>