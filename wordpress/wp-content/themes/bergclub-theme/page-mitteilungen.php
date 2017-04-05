<?php
/**
 * Displaying Page Mitteilungen
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
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
                    <?php the_title('<h1 class="page-header">', '</h1>') ?>
                </div>
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
                            <?php
                            $query = new WP_Query(array('category_name' => 'mitteilungen', 'posts_per_page' => 1000));
                            // Start the loop. Retriev Posts in Category "Mitteilungen"
                            while ($query->have_posts()) : $query->the_post(); ?>
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
                                <?php
                                // End the loop.
                            endwhile;
                            wp_reset_postdata();
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main><!-- .site-main -->
    </div><!-- .content-area -->
<?php get_footer(); ?>