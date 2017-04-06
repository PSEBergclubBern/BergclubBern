<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="container">
        <div class="row">
            <?php the_title('<h1 class="page-header">', '</h1>'); ?>
        </div>
        <div class="row">
            <div class="col-lg-10">
                <?php
                // Post thumbnail.
                bcb_post_thumbnail();
                ?>
                <div class="row">
                    <div class="page-content">
                        <?php the_content('<p>', '</p>'); ?>
                    </div>
                </div>
                <div class="row">
                    <?php bcb_prev_next_links(); ?>
                </div>
            </div>
        </div>
    </div>
</article>