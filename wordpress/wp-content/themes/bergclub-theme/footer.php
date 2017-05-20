<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Bergclub Bern
 */
?>

</div><!-- .site-content -->

<div class="footer navbar-fixed-bottom">
    <p>&copy; Bergclub Bern 2017</p>
    <div class="footer-menu pull-right">
        <?php wp_nav_menu(['theme_location' => 'footer-menu']); ?>
    </div>
</div>

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>
