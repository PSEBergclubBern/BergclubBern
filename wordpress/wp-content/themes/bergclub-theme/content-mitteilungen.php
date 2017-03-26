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
    <?php
    // Post thumbnail.
    twentyfifteen_post_thumbnail();
    ?>
    <td>
        <?php echo get_the_date(); ?>
    </td>
    <td>
        <a href="<?php echo get_post_permalink(); ?>"><?php the_title() ?></a>
    </td>
    <td>
        <?php the_author() ?>
    </td>

</article><!-- #post-## -->