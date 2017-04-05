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
    <?php
    // Post thumbnail.
    bcb_post_thumbnail();
    ?>
    <div class="row">
        <?php the_title('<h1 class="page-header">', '</h1>'); ?>
    </div>
    <div class="row">
        <div class="page-content">
            <?php the_content('<p>', '</p>'); ?>
        </div>
    </div>
    <?php
    wp_link_pages(array(
        'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'twentyfifteen') . '</span>',
        'after' => '</div>',
        'link_before' => '<span>',
        'link_after' => '</span>',
        'pagelink' => '<span class="screen-reader-text">' . __('Page', 'twentyfifteen') . ' </span>%',
        'separator' => '<span class="screen-reader-text">, </span>',
    ));
    ?>

</article><!-- #post-## -->