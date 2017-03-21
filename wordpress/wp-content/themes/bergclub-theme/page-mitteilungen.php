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
                <?php the_title('<h1 class="page-header">','</h1>')?>
                <table id="mitteilung-table" class="table table-hover">
                    <thead>
                    <tr>
                        <th>
                            <div class="th-inner">Datum</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th colspan="2">
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
                    $query = new WP_Query(array('category_name' => 'mitteilungen', 'posts_per_page' => 1000));
                    // Start the loop. Retriev Posts in Category "Mitteilungen"
                    while ($query->have_posts()) : $query->the_post(); ?>
                        <tr class="mitteilung-table-row">
                            <?php
                            // Include the page content template.
                            get_template_part('content', 'mitteilungen');
                            ?>
                        </tr>
                        <?php
                        // End the loop.
                    endwhile;
                    wp_reset_postdata();
                    ?>
                    </tbody>
                </table>
            </div>
        </main><!-- .site-main -->
    </div><!-- .content-area -->

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#mitteilung-table tr').click(function () {
                var href = $(this).find("a").attr("href");
                if (href) {
                    window.location = href;
                }
            })
        })
    </script>

<?php get_footer(); ?>