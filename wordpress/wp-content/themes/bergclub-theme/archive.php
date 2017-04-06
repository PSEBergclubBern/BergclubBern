<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <div class="container">
        <?php the_archive_title('<h1 class="page-header">', '</h1>'); ?>
        <?php if ( have_posts() ){ ?>
            <div class="container-fluid grid-table hide-links row-hover">
                <div class="row row-header hidden-xs">
                    <div class="col-sm-3">
                        Datum
                    </div>
                    <div class="col-sm-6">
                        Titel
                    </div>
                    <div class="col-sm-3">
                        Author
                    </div>
                </div>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="row add-link">
                        <div class="col-sm-3 italic-sm">
                            <?php echo get_the_date(); ?>
                        </div>
                        <div class="col-sm-6">
                            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                        </div>
                        <div class="col-sm-3 italic-sm">
                            <?php the_author(); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="container">
                <?php bcb_pagination(); ?>
            </div>
        <?php }else{ ?>
            <div class="container">
                <p>Keine Beiträge vorhanden.</p>
            </div>
        <?php } ?>

        <?php get_footer(); ?>
