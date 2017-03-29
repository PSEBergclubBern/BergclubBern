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
        <?php echo get_the_date(); ?>
    </td>
    <td>
        <div class="th-inner">
            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
        </div>
    </td>
    <td>
        <?php the_author(); ?>
    </td>
</article><!-- #post-## -->