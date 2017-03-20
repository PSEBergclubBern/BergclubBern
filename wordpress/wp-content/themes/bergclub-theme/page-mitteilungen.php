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
                <div class="bootstrap-table">
                    <div class="fixed-table-container">
                        <div class="fixed-table-header">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="th-inner">Datum</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th>
                                        <div class="th-inner">Titel</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th>
                                        <div class="th-inner">Autor</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = new WP_Query(array('category_name' => 'category-mitteilungen'));
                                $count = 0;
                                // Start the loop. Retriev Posts in Category "Mitteilungen"
                                while ($query->have_posts()) : $query->the_post(); ?>
                                    <tr data-index="<?php echo $count ?>"
                                    <?php
                                    // Include the page content template.
                                    get_template_part('content', 'mitteilungen');
                                    $count += 1;
                                    // End the loop.
                                endwhile;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer(); ?>