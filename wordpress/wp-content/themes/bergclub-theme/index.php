<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
get_header() ?>

    <div class="container-fluid">

        <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active"></div>
                <div class="item"></div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4 logo-col">
                <img class="img-responsive header-logo" src="<?php echo esc_url( get_template_directory_uri() ); ?>/BergclubBernLogo.png" alt="Logo">
            </div>

            <div class="col-md-4 col-md-offset-4 newest-posts">

                <div class="well well-sm">
                    <h3>Tourenberichte</h3>
                    <ul class="list-group">
                        <a class="list-group-item">Tour 1</a>
                        <a class="list-group-item">Tour 2</a>
                        <a class="list-group-item">Tour 3</a>
                        <a class="list-group-item">Tour 4</a>
                        <a class="list-group-item">Tour 5</a>
                    </ul>
                </div>

                <div class="well well-sm">
                    <h3>Mitteilungen</h3>
                    <ul class="list-group">
                        <a class="list-group-item">Mitteilung 1</a>
                        <a class="list-group-item">Mitteilung 2</a>
                        <a class="list-group-item">Mitteilung 3</a>
                    </ul>
                </div>

                <!--            <!-- Start the Loop. -->
                <!--            --><?php //if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <!--                <div class="post">-->
                <!---->
                <!--                    <!-- Display the Title as a link to the Post's permalink. -->
                <!---->
                <!--                    <h2>--><?php //the_title(); ?><!--</h2>-->
                <!---->
                <!---->
                <!--                    <!-- Display the date (November 16th, 2009 format) and a link to other posts by this posts author. -->
                <!---->
                <!--                    <!-- <small>--><?php ////the_time('F jS, Y'); ?><!--<!-- by --><?php ////the_author_posts_link(); ?><!--<!--</small>-->
                <!---->
                <!---->
                <!--                    <!-- Display the Post's content in a div box. -->
                <!---->
                <!--                    <div class="post-content">-->
                <!--                        --><?php //the_content(); ?>
                <!--                    </div>-->
                <!---->
                <!---->
                <!--                    <!-- Display a comma separated list of the Post's Categories. -->
                <!---->
                <!--                    <p class="postmetadata">--><?php //_e( 'Posted in' ); ?><!-- --><?php //the_category( ', ' ); ?><!--</p>-->
                <!--                </div> <!-- closes the first div box -->
                <!--            --><?php //endwhile; endif; ?>

            </div>

        </div>
    </div><!-- /.container-fluid -->

    <script>jQuery('.carousel').carousel();</script>

<?php get_footer() ?>