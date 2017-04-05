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
    <main id="main" class="site-main" role="main">
        <div class="container">
            <div class="row">

                <?php if ( have_posts() ){ ?>

                <header class="page-header">
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    ?>
                </header><!-- .page-header -->
                <div class="row">
                    <div class="table-responsive page-content">
                        <table class="table table-hover hide-links">
                            <colgroup>
                                <col class="col-md-2">
                                <col class="col-md-7">
                                <col class="col-md-3">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Titel</th>
                                <th>Autor</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while (have_posts()) : the_post(); ?>
                                <tr class="table-row-hover add-link">
                                    <td>
                                        <?php echo get_the_date(); ?>
                                    </td>
                                    <td>
                                        <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                                    </td>
                                    <td>
                                        <?php the_author(); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                        <?php
                        bcb_pagination();

                        }else { ?>
                            Keine Beiträge vorhanden.
                        <?php } ?>
                    </div>
                </div>
            </div>
    </main><!-- .site-main -->
</div>

<?php get_footer(); ?>
