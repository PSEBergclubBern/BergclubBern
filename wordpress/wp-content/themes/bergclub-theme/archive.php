<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Berglub Bern
 */

get_header(); ?>


    <div class="container">
        <h1><?php the_archive_title(); ?></h1>
        <?php if (have_posts()) { ?>
            <div class="container-fluid grid-table hide-links row-hover">
                <div class="row row-header hidden-xs">
                    <div class="col-sm-3">
                        Datum
                    </div>
                    <div class="col-sm-9">
                        Titel
                    </div>
                </div>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="row add-link">
                        <div class="col-sm-3 italic-sm">
                            <?php echo get_the_date(); ?>
                        </div>
                        <div class="col-sm-9">
                            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="container">
                <?php bcb_pagination(); ?>
            </div>
        <?php } else { ?>
            <div class="container">
                <p>Keine Beiträge vorhanden.</p>
            </div>
        <?php } ?>
    </div>
<?php get_footer(); ?>