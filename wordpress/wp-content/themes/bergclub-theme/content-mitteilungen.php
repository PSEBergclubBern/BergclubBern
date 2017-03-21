<?php
/**
 * The template used for displaying mitteilungen page content
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <td>
            <div class="th-inner">
                <?php echo get_the_date(); ?>
            </div>
            <div class="fht-cell"></div>
        </td>
        <td colspan="2">
            <div class="th-inner">
                <a href="<?php echo get_post_permalink(); ?>"><?php the_title()?></a>
            </div>
            <div class="fht-cell"></div>
        </td>
        <td>
            <div class="th-inner">
                <?php the_author(); ?>
            </div>
            <div class="fht-cell"></div>
        </td>
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