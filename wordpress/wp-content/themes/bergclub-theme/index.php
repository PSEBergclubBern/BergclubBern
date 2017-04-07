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
                <div class="item"></div>
                <div class="item"></div>
            </div>
        </div>

        <img class="img-responsive header-logo"
             src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo<?php if(bcb_is_jugend()){ echo "-jugend"; } ?>.png" alt="Logo">

        <div class="row">

            <div class="col-md-6 col-md-offset-6 col-lg-4 col-lg-offset-8 newest-posts">
                <!--<div class="hidden-lg hidden-md mobile-placeholder">&nbsp;</div>-->
                <div class="well">
                    <h3>Neuste Tourenberichte <?php if(bcb_is_jugend()){ echo "Jugend"; } ?></h3>
                    <ul class="list-group">
                        <a class="list-group-item">Tour 1</a>
                        <a class="list-group-item">Tour 2</a>
                        <a class="list-group-item">Tour 3</a>
                        <a class="list-group-item">Tour 4</a>
                        <a class="list-group-item">Tour 5</a>
                    </ul>
                </div>

                <div class="well">
                    <h3>Neuste Mitteilungen</h3>

                    <ul class="list-group">
                        <?php
                        $query = new WP_Query(array('category_name' => 'mitteilungen', 'posts_per_page' => 3));
                        // Start the loop. Retriev Posts in Category "Mitteilungen"
                        while ($query->have_posts()) : $query->the_post(); ?>
                            <a class="list-group-item" href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                            <?php
                            // End the loop.
                        endwhile;
                        wp_reset_postdata();
                        ?>

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
                <!--                    <!-- <small>--><?php ////the_time('F jS, Y'); ?><!--<!-- by -->
                <?php ////the_author_posts_link(); ?><!--<!--</small>-->
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
                <!--                    <p class="postmetadata">--><?php //_e( 'Posted in' ); ?><!-- -->
                <?php //the_category( ', ' ); ?><!--</p>-->
                <!--                </div> <!-- closes the first div box -->
                <!--            --><?php //endwhile; endif; ?>

            </div>

        </div>
    </div><!-- /.container-fluid -->

<?php get_footer() ?>