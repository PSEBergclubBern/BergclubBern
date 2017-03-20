<?php
/**
 * The template for displaying pages
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

		<?php
		// Start the loop.
        $args = [
            'post_type'      => 'tourenverwaltung',
            'posts_per_page' => 10,
        ];
        $loop = new WP_Query($args);
        while ($loop->have_posts()) {
            $loop->the_post();
            ?>
            <div class="entry-content">
                <?php the_title(); ?>
                <?php the_content(); ?>
            </div>
            <?php
        }
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
