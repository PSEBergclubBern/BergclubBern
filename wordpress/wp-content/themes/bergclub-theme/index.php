<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
get_header() ?>

<div class="container">
    <div class="page-content">
        <!-- Start the Loop. -->
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class="post">

                <!-- Display the Title as a link to the Post's permalink. -->

                <h2><?php the_title(); ?></h2>


                <!-- Display the date (November 16th, 2009 format) and a link to other posts by this posts author. -->

                <!-- <small>--><?php //the_time('F jS, Y'); ?><!-- by --><?php //the_author_posts_link(); ?><!--</small>-->


                <!-- Display the Post's content in a div box. -->

                <div class="post-content">
                    <?php the_content(); ?>
                </div>


                <!-- Display a comma separated list of the Post's Categories. -->

                <p class="postmetadata"><?php _e( 'Posted in' ); ?> <?php the_category( ', ' ); ?></p>
            </div> <!-- closes the first div box -->
        <?php endwhile; endif; ?>


    </div><!-- /.page-content -->
</div><!-- /.container -->

<?php get_footer() ?>
