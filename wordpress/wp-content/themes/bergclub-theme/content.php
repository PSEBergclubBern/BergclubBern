<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Bergclub Bern
 */
?>
<div class="container">
    <div class="row">
        <h1><?php the_title(); ?></h1>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <?php bcb_post_thumbnail(); ?>
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