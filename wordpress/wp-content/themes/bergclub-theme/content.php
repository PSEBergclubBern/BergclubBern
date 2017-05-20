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
        <h1>
            <img class="pull-right hidden-xs" src="<?= get_template_directory_uri() ?>/img/bergclub-jugend-sm.png"
                 style="margin-left:20px">
            <img class="pull-right hidden-xs" src="<?= get_template_directory_uri() ?>/img/bergclub-sm.png"
                 style="margin-left:20px">
            <?php the_title(); ?>
        </h1>
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